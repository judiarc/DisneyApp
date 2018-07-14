<?php
require_once ("Rest.inc.php");

class Artist extends REST
{

    public $data = "";

    const DB_SERVER = "127.0.0.1";

    const DB_USER = "root";

    const DB_PASSWORD = "";

    const DB = "project_management";

    private $db = NULL;

    private $mysqli = NULL;

    public function __construct()
    {
        parent::__construct(); // Init parent contructor
        $this->dbConnect(); // Initiate Database connection
                                // $this->functionconnect();
    }

    /*
     * Connect to Database
     */
    private function dbConnect()
    {
        $this->mysqli = new mysqli(self::DB_SERVER, self::DB_USER, self::DB_PASSWORD, self::DB);
    }

    /*
     * Dynmically call the method based on the query string
     */
    public function functionconnect()
    {
        $action = $_GET['function'];
        switch ($action) {
            case 'addOrUpdateArtist':
                $this->addOrUpdateArtist();
                break; 
            case 'removeArtist':
                $this->removeArtist();
                break; 
            case 'retrieveArtist':
                $this->retrieveArtist();
                break; 
            case 'addUser':
                $this->addUser();
                break;
            case 'removeUser':
                $this->removeUser();
                break;
        }
    }

    function addOrUpdateArtist()
    {
        $artistId = $_POST['artistId'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $dob = $_POST['dob'];
        $mobile = $_POST['mobile'];
        $address1 = $_POST['addressline1'];
        $address2 = $_POST['addressline2'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $country = $_POST['country'];
        $doj = $_POST['doj'];
        $experience = $_POST['experience'];
        $gender = $_POST['gender'];
        $ctc = $_POST['ctc'];
        $outputmanday = $_POST['outputmanday'];
        $dol = $_POST['dol'];
        $role = $_POST['role'];
        $lead = $_POST['lead'];
        $supervisor = $_POST['supervisor'];        
        $level = $_POST['level'];
        $status = $_POST['status'];
        $userid = $_COOKIE["userid"];
        $companyName = $_POST['companyName'];
        $date = date("Y-m-d H:i:s");
        $departmentArray = $_POST['departmentid'];
        $department = implode(',', $_POST['departmentid']);   
        $localArtistId = $artistId;
        //print("artistId".$artistId);
              
        if($artistId == null or $artistId = '') {
            if($email!=null)
            {
            $emailQueryResult = $this->getMailCount($email);
            $count = (int)$emailQueryResult;
            
            }
            else
            {
                $count=0;
            }
                if ($count <= 0) {
            $artist_insert_query = "INSERT INTO ARTIST (NAME, DOB, EMAIL, MOBILE, ADDRESS_LINE1, ADDRESS_LINE2, CITY,
            STATE, COUNTRY, DOJ, EXPERIENCE, GENDER, COMPANY_NAME, CTC, ROLE, DEPARTMENTID, SUPERVISOR, LEAD, LEVEL_ID, OUTPUT_MANDAY,
            STATUS, DOL, CREATED_DATE, CREATED_BY) VALUES('$name', '$dob','$email','$mobile','$address1','$address2',
            '$city','$state','$country','$doj','$experience','$gender','$companyName','$ctc','$role','$department','$supervisor', '$lead',
           '$level','$outputmanday','$status', '$dol', '$date', '$userid')";
            
            $this->mysqli->query($artist_insert_query);           
            $localArtistId = mysqli_insert_id($this->mysqli);
            $this->addUser($name,$email,$companyName,$role,$localArtistId,$status,$userid);
            }
            else {
                $data['failure'] = true;
                $data['message'] = 'The Email id Already available in System';
                $this->response(json_encode($data), 200);
            }
            
        }else {
            if($email!=null)
            {
                $emailQueryResult = $this->getMailCount($email);
                $count = (int)$emailQueryResult;
                $countQueryResult = $this->getIdCount($email,$localArtistId);
                $count_id = (int)$countQueryResult;
            }
            else
            {
                $count=0;
            }
            //print("count".$count);
            if(($count <= 0)||($count_id==1))
            {
            $artist_update_query = "UPDATE ARTIST SET NAME = '$name', DOB = '$dob', EMAIL = '$email', MOBILE='$mobile', ADDRESS_LINE1='$address1',
            ADDRESS_LINE2 = '$address2', CITY='$city', STATE='$state', COUNTRY='$country', DOJ='$doj', EXPERIENCE='$experience', GENDER='$gender', COMPANY_NAME='$companyName', CTC='$ctc',
            ROLE='$role', DEPARTMENTID = '$department',SUPERVISOR='$supervisor', LEAD='$lead', LEVEL_ID='$level', OUTPUT_MANDAY='$outputmanday',STATUS='$status',
            modify_by = '$userid', modify_date = '$date' WHERE ID='$localArtistId'";
            //print("artist_update_query".$artist_update_query);
           // $artistId = $artistId;
            $this->mysqli->query($artist_update_query);
            $this->updateUser($name,$email,$companyName,$role,$localArtistId,$status,$userid);
            }
            
            else{
                $data['failure'] = true;
                $data['message'] = 'The Email id Already available in System';
                /* $data['message'] = 'You cannot edit the email address'; */
                $this->response(json_encode($data), 200);
            }
            
            
         }       
         if ($localArtistId) {
                    $data['success'] = true;                   
                    $data['artistId'] = $localArtistId;
                    $data['message'] = 'Record Saved Successfully';                    
                }else{
                    $data['failure'] = true;
                    $data['message'] = 'Record is not properly saved in Database';    
                }
            $this->response(json_encode($data), 200);
    }
    
    /**
     * @param email
     */private function getMailCount($email)
     {
         //print($email);
         $emailQuery = "SELECT count(*) as total FROM artist WHERE email = '$email'";
         $r3 = $this->mysqli->query($emailQuery);
         if ($r3->num_rows > 0) {
             while ($row1 = $r3->fetch_assoc()) {
                 $emailQueryResult = $row1['total'];
             }
         }
         // print($emailQueryResult);
         return $emailQueryResult;
    }
    

    /**
     * @param email
     */private function getIdCount($email,$localArtistId)
     {
         //print($email);
         $emailQuery = "SELECT count(*) as total FROM artist WHERE email = '$email' and id='$localArtistId'";
         $r3 = $this->mysqli->query($emailQuery);
         if ($r3->num_rows > 0) {
             while ($row1 = $r3->fetch_assoc()) {
                 $countQueryResult = $row1['total'];
             }
         }
         // print($emailQueryResult);
         return $countQueryResult;
    }
    
    private function addUser($name,$email,$companyName,$role,$localArtistId,$status,$userid)
    {
        
        $insert_query="INSERT INTO users(name,email,company_name,userroleid,artistid,status,created_by) values('$name','$email','$companyName','$role','$localArtistId','$status','$userid')";
        
        //  $po_shot_assoc = "INSERT INTO po_shot_assoc (shot_work_details_identify_id,po_identify_id,status, created_by) values($shotWork,$po_IdentifyId,$status,$loginUserid) ";
        
        
        //$invoice_shot_assoc =  rtrim($entity_dept_assoc,',');
        //  print($invoice_identifyId);
        $this->mysqli->query($insert_query);
        
        if ($localArtistId) {
            $data['success'] = true;
            $data['artistId'] = $localArtistId;
            $data['message'] = 'Record Saved Successfully';
        }else{
            $data['failure'] = true;
            $data['message'] = 'Record is not properly saved in Database';
        }
        $this->response(json_encode($data), 200);
    }
    
    private function updateUser($name,$email,$companyName,$role,$localArtistId,$status,$userid)
    {
        $update_query="UPDATE users SET name='$name',email='$email',company_name='$companyName',userroleid='$role',status='$status',modified_by='$userid' where artistid='$localArtistId'";
        
       // $insert_query="INSERT INTO users(name,email,company_name,userroleid,artistid,status,created_by) values('$name','$email','$companyName','$role','$localArtistId','$status','$userid')";
        
        //  $po_shot_assoc = "INSERT INTO po_shot_assoc (shot_work_details_identify_id,po_identify_id,status, created_by) values($shotWork,$po_IdentifyId,$status,$loginUserid) ";
        
        
        //$invoice_shot_assoc =  rtrim($entity_dept_assoc,',');
        //  print($invoice_identifyId);
        $this->mysqli->query($update_query);
        
        if ($localArtistId) {
            $data['success'] = true;
            $data['artistId'] = $localArtistId;
            $data['message'] = 'Record Saved Successfully';
        }else{
            $data['failure'] = true;
            $data['message'] = 'Record is not properly saved in Database';
        }
        $this->response(json_encode($data), 200);}
    
    
    
    
    function retrieveArtist()
    {
       
        $loginUserRole = $_COOKIE["userRole"];
        if(isset($_GET['artistId'])) {
            $artistId = $_GET['artistId'];
            //print("get clientId".$clientId);
        }else {
            $artistId = null;
        }
        $userid = $_COOKIE["userid"];
        
        $company_id =  $this->getCompanyIdFromUser($userid);       
        
        $retrieveArtist_query = "SELECT id, name, dob, email, mobile, address_line1 as addressline1, address_line2 as addressline2, city,
            state, country, doj, experience, gender, ctc, role, departmentid, supervisor, lead, project_head as projecthead, level_id, output_manday as outputmanday,
            status, dol, company_name FROM ARTIST where status in (1,2)";
        
        if($loginUserRole != '' && $loginUserRole != "0" && $company_id !=''){
            $retrieveUser_query.= " and company_name= '$company_id'";
        }
        if($artistId != null && $artistId !=''){
           $retrieveArtist_query .= " AND ID='$artistId'";
         }
         $r3 = $this->mysqli->query($retrieveArtist_query);
         $result = array();
        if($r3->num_rows > 0){            
            while($row1 = $r3->fetch_assoc()){
                $result[] = $row1;
            }
        }
        $this->response(json_encode($result), 200); // send client details
        $this->response('',204);
    }
    
    function removeArtist(){        
        if(isset($_GET['artistId'])) {
            $artistId = $_GET['artistId'];
        }else {
            $artistId = null;
        }         
        $removeArtist_query = "UPDATE ARTIST SET STATUS='2' WHERE ID IN ($artistId)";
        $r = $this->mysqli->query($removeArtist_query);
        $this->removeUser($artistId);
        if ($r) {
            $this->response(json_encode("success"), 200);
        }else {
            $this->response(json_encode("failure"), 204);
        }
    }
    
    private function removeUser($artistId)
    {
        $removeUser_Query="UPDATE USERS SET STATUS='2' WHERE ARTISTID IN ($artistId)";
        $r = $this->mysqli->query($removeUser_Query);
        if ($r) {
            $this->response(json_encode("success"), 200);
        }else {
            $this->response(json_encode("failure"), 204);
        }
    }
    private function getCompanyIdFromUser($userid){
        $companyId = '';
        $retrieve_Company_Name = "SELECT COMPANY_NAME FROM USERS WHERE ID='$userid'";
        $companyIdOutput = $this->mysqli->query($retrieve_Company_Name);
        if($companyIdOutput->num_rows > 0){
            while($row1 = $companyIdOutput->fetch_assoc()){
                $companyId = $row1['COMPANY_NAME'];
               //print("companyId".$companyId);
            }
        }
    }
}

// Initiiate Library

$api = new Artist();
$api->functionconnect();
