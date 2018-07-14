<?php
require_once ("Rest.inc.php");

class Project extends REST
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
            case 'addOrUpdateProject':
                $this->addOrUpdateProject();
                break;
            case 'removeProject':
                $this->removeProject();
                break;
            case 'retrieveProject':
                $this->retrieveProject();
                break;
        }
    }
    
    
    /*
     * insertion and updation for project details
     */
    function addOrUpdateProject()
    {
            $name = $_POST['name'];
             
            $clientid = $_POST['client'];
            
            if (isset($_POST['projectreceiveddate'])) {
                $projectreceiveddate = $_POST['projectreceiveddate'];
            }else {
                $projectreceiveddate = null;
            }
            
            if (isset($_POST['targetdate'])) {
                $targetdate = $_POST['targetdate'];
            }else {
                $targetdate = null;
            }
            
            if (isset($_POST['internaltargetdate'])) {
                $internaltargetdate = $_POST['internaltargetdate'];
            }else {
                $internaltargetdate = null;
            }
            
            
            if (isset($_POST['shotcount'])) {
                $shotcount = $_POST['shotcount'];
            } else {
                $shotcount = NULL;
            }
            
            if (isset($_POST['cost'])) {
                $cost = $_POST['cost'];
            } else {
                $cost = NULL;
            }
            
            if (isset($_POST['entitystatus'])) {
                $entitystatus = $_POST['entitystatus'];
            } else {
                $entitystatus = NULL;
            }
            $entityid = $_POST['entityid'];
            $editprojectdetid = $_POST['projectId'];
            $username = $_COOKIE["userid"];
            $date = date("Y-m-d H:i:s");
            $data = array();
            $localProjectId=$editprojectdetid;
            if ($editprojectdetid <= 0) {
                try {
                        $projectInsertQuery = "INSERT INTO project_details (name,projectdetailsclientid,receiveddate,targetdate,
                                     internaltargetdate, shot_count, cost, status, created_by, created_date)
                             VALUES ('$name','$clientid','$projectreceiveddate', '$targetdate',
                                 '$internaltargetdate', '$shotcount','$cost','$entitystatus', '$username', '$date')";
                      //  print($projectInsertQuery);
                        $querydet = $this->mysqli->query($projectInsertQuery);
                        $localProjectId = mysqli_insert_id($this->mysqli);
                        
                        //print("querydet Value is" . $querydet);
                        if ($querydet) {
                            $data['success'] = true;
                            $data['projectId']=$localProjectId;
                            $data['message'] = 'Record Saved Successfully!';
                        }
                  
                    
                } catch (Exception $e) {
                    print("Exception in insertion" . $e);
                }
            } else {
                
                $projdetails_update = "UPDATE project_details SET name='$name',projectdetailsclientid='$clientid',
                    receiveddate='$projectreceiveddate',targetdate = '$targetdate', internaltargetdate = '$internaltargetdate',
                     shot_count = '$shotcount', cost='$cost', status = '$entitystatus', 
                    modified_by='$username',modified_date='$date' WHERE id='$editprojectdetid' ";
              //  print($projdetails_update);
                $r = $this->mysqli->query($projdetails_update);
                
                if ($r) {
                    $data['success'] = true;
                    $data['message'] = 'Record Updated Successfully!';                    
                }
            }  
            $this->response(json_encode($data), 200); // send user details
            $this->response('',204);
    }
    
    function retrieveProject()
    {
        $userid = $_COOKIE["userid"];
        
        if(isset($_GET['projectId'])) {
            $projectId = $_GET['projectId'];
           // echo $vendorId;
            // print("get userId".$userId);
        }else {
            $projectId = null;
        }
        $retrieveProject_query = "select id, name, projectdetailsclientid as client,receiveddate as projectreceiveddate,targetdate as targetdate,
                                     internaltargetdate as internaltargetdate, shot_count as shotcount, cost, status as entitystatus from project_details";
        if($projectId != null && $projectId != ''){
            $retrieveProject_query.= " where id='$projectId'";
        }
       // print($retrieveProject_query);
        $r3 = $this->mysqli->query($retrieveProject_query);
        if($r3->num_rows > 0){
            // print("count".$r3->num_rows);
            $result = array();
            while($row1 = $r3->fetch_assoc()){
                $result[] = $row1;
            }
            
        }
        $this->response(json_encode($result), 200); // send user details
        $this->response('',204);
        
    }
    
    function removeProject()
    {
        if(isset($_GET['projectId'])) {
            $projectId = $_GET['projectId'];
        }else {
            $projectId = null;
        }
        $removeProject_query = "UPDATE PROJECT_DETAILS SET STATUS='2' WHERE ID in ($projectId)";
        $r = $this->mysqli->query($removeProject_query);
        if ($r) {
            $this->response(json_encode("success"), 200);
        }else {
            $this->response(json_encode("failure"), 200);
        }
    }
    
    
    private function getCompanyIdFromUser($userid){
        $companyId = '';
        $retrieve_Company_Name = "SELECT COMPANY_NAME FROM USERS WHERE ID=$userid";
        //  print($retrieve_Company_Name);
        $companyIdOutput = $this->mysqli->query($retrieve_Company_Name);
        if($companyIdOutput->num_rows > 0){
            while($row1 = $companyIdOutput->fetch_assoc()){
                $companyId = $row1['COMPANY_NAME'];
            }
        }
        return $companyId;
    }
   
    /*
     * Encode array into JSON
     */
    private function json($data)
    {
        if (is_array($data)) {
            return json_encode($data, JSON_NUMERIC_CHECK);
        }
    }
}

// Initiiate Library

$api = new Project();
$api->functionconnect();