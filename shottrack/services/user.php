<?php
require_once ("Rest.inc.php");

class User extends REST
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
            case 'addOrUpdateUser':
                $this->addOrUpdateUser();
                break;
            case 'removeUser':
                $this->removeUser();
                break;
            case 'retrieveUser':
                $this->retrieveUser();
                break;
            case 'saveUserEntityAssoc':
                $this->saveUserEntityAssoc();
                break;
            case 'saveUserEntityFieldAssoc':
                $this->saveUserEntityFieldAssoc();
                break;
        }
    }

    /* Function for Add or Update User Details */
    function addOrUpdateUser()
    {
        if (isset($_POST['userId'])) {
            $userId = $_POST['userId'];
        } else {
            $userId = '';
        }
        if (isset($_POST['name'])) {
            $name = $_POST['name'];
        } else {
            $name = '';
        }
        if (isset($_POST['email'])) {
            $email = $_POST['email'];
        } else {
            $email = '';
        }
        if (isset($_POST['password'])) {
            $password = $_POST['password'];
        } else {
            $password = '';
        }
        if (isset($_POST['roleid'])) {
            $userroleid = $_POST['roleid'];
        } else {
            $userroleid = '';
        }
        if (isset($_POST['entitystatus'])) {
            $entitystatus = $_POST['entitystatus'];
        } else {
            $entitystatus = '';
        }
        
        $loginUserid = $_COOKIE["userid"];
        $date = date("Y-m-d H:i:s");
        $localUserId = $userId;
        // print("localUserId". $localUserId);
        
        $company_name = '';
        if (isset($_POST['companyId']) && $_POST['companyId'] != "") {
            $company_name = $_POST['companyId'];
        } else {
            if ($localUserId != null && $localUserId != '') {
                $company_name = $this->getCompanyIdFromUser($localUserId);
            }
            if ($company_name == null || $company_name == '') {
                // print("loginUserid".$loginUserid);
                $company_name = $this->getCompanyIdFromUser($loginUserid);
            } else {
                $company_name = '';
            }
        }
        
        if ($userId == null or $userId = '') {
            if($email!=null)
            {
                $emailQueryResult = $this->getMailCount($email);
                $count = (int)$emailQueryResult;
                
            }
            else
            {
                $count=0;
            }
           //print("count".$count);
            if ($count <= 0) {
                $insertnewuser = "insert into users (name, password, email, company_name, status, userroleid,created_by) values ('$name', '$password', '$email', '$company_name','$entitystatus' ,'$userroleid','$loginUserid')";
                // print($insertnewuser);
                $r = $this->mysqli->query($insertnewuser);
                $localUserId = mysqli_insert_id($this->mysqli);
            } else {
                $data['failure'] = true;
                $data['message'] = 'The Email id Already available in System';
                $this->response(json_encode($data), 200);
            }
        } else {
            $edituserId = $_POST['userId'];
            if($email!=null)
            {
                $emailQueryResult = $this->getMailCount($email);
                $count = (int)$emailQueryResult;
                $countQueryResult = $this->getIdCount($email,$edituserId);
                $count_id = (int)$countQueryResult;
            }
            else
            {
                $count=0;
            }
            //print("count".$count);
            if(($count <= 0)||($count_id==1))
            {
            $updateuser = "UPDATE users SET name='$name',password='$password',company_name='$company_name', userroleid='$userroleid',
                 modified_by='$loginUserid',modified_date='$date',status='$entitystatus',email='$email' WHERE id='$edituserId' ";           
           // print($updateuser);
            $updateUserResult = $this->mysqli->query($updateuser);
            //print($this->mysqli->affected_rows);
            }
           
            else{
                $data['failure'] = true;
                $data['message'] = 'The Email id Already available in System';
                /* $data['message'] = 'You cannot edit the email address'; */
                $this->response(json_encode($data), 200);
            }
        }
        
        if ($localUserId) {
            $data['success'] = true;
            $data['userId'] = $localUserId;
            $data['message'] = 'Record Saved Successfully';
        } else {
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
        $emailQuery = "SELECT count(*) as total FROM users WHERE email = '$email'";
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
     */private function getIdCount($email,$editUserId)
     {
         //print($email);
         $emailQuery = "SELECT count(*) as total FROM users WHERE email = '$email' and id='$editUserId'";
         $r3 = $this->mysqli->query($emailQuery);
         if ($r3->num_rows > 0) {
             while ($row1 = $r3->fetch_assoc()) {
                 $countQueryResult = $row1['total'];
             }
         }
         // print($emailQueryResult);
         return $countQueryResult;
    }
    
    
    /* function to get particular user details */
    function retrieveUser()
    {
        $loginUserId = $_COOKIE["userid"];
        $loginUserRole = $_COOKIE["userRole"];
        if (isset($_GET['userId'])) {
            $userId = $_GET['userId'];
        } else {
            $userId = null;
        }
        // print($loginUserId);
        $company_name = $this->getCompanyIdFromUser($loginUserId);
        $retrieveUser_query = "select * from users where is_deleted='0'";
        // print($loginUserRole);
        /* if ($loginUserRole != '' && $loginUserRole != "0" && $company_name != '') {
            $retrieveUser_query .= " and company_name= '$company_name'";
        } */
        
        if ($userId != null && $userId != '') {
            $retrieveUser_query .= " and id='$userId'";
        }
        // print($retrieveUser_query);
        $r3 = $this->mysqli->query($retrieveUser_query);
        // print("number of rows".$r3->num_rows);
        if ($r3->num_rows > 0) {
            $result = array();
            while ($row1 = $r3->fetch_assoc()) {
                $result[] = $row1;
            }
        }
        $this->response(json_encode($result), 200); // send user details
        $this->response('', 204);
    }

    private function getCompanyIdFromUser($userid)
    {
        $companyId = '';
        $retrieve_Company_Name = "SELECT COMPANY_NAME FROM USERS WHERE ID=$userid";
        // print('retrieve_Company_Name'.$retrieve_Company_Name);
        $r3 = $this->mysqli->query($retrieve_Company_Name);
        if ($r3->num_rows > 0) {
            while ($row1 = $r3->fetch_assoc()) {
                $companyId = $row1['COMPANY_NAME'];
            }
        }
        // print($companyId);
        return $companyId;
    }

    function removeUser()
    {
        if (isset($_GET['userId'])) {
            $userId = $_GET['userId'];
        } else {
            $userId = null;
        }
        $removeUser_query = "UPDATE USERS SET status = 2 WHERE ID IN ($userId)";
        //print($removeUser_query);
        $r = $this->mysqli->query($removeUser_query);
        if ($r) {
            $this->response(json_encode("success"), 200);
        } else {
            $this->response(json_encode("failure"), 200);
        }
    }
       
    function saveUserEntityAssoc(){
        $loginUserId = $_COOKIE["userid"];
        $loginUserRole = $_COOKIE["userRole"];
        if(isset($_GET['userId'])){
            $userId = $_GET['userId'];
        }else {
            $userId = null;
        }
        
        if (isset($_GET['checkedValue'])) {
            $checkedValueList = $_GET['checkedValue'];
        } else {
            $checkedValueList = null;
        }
        
       // print("checkedValueList".$checkedValueList);
        $date = date("Y-m-d H:i:s");
        
        $checkedValueArray = explode(",",$checkedValueList);
       
        //$r = $this->mysqli->query($deleteUserEntityAssoc);
        if(count($checkedValueArray) > 0) {
            $deleteUserEntityAssoc = "delete from user_entity_field_assoc where user_id = $userId and entity_id in (";
            $userEntityFieldAssoc = "insert into user_entity_field_assoc (user_id, entity_id, field_id, created_by, created_date)
             values "; 
            $insertValueArray = array();
            $deleteValueArray = array();
            foreach($checkedValueArray as $checkedValue){
                //print($checkedValue."---");
                $checkedValueSpilit = explode(":",$checkedValue);
               
                if($checkedValueSpilit[1] == "true") {                  
                    $insertValueArray[] = $checkedValueSpilit[0];
                } else {                    
                    $deleteValueArray[] = $checkedValueSpilit[0];
                }                
            }
            //print(count($deleteValueArray));
            foreach($insertValueArray as $insertValue){
                $dataAvailabilityCheckQuery = "select id from user_entity_field_assoc where user_id = $userId and entity_id = $insertValue";
                $departmentList = $this->mysqli->query($dataAvailabilityCheckQuery);
                $data=$departmentList->num_rows;                
                if ($data == 0) {                  
                    $getFieldDetailsQuery = "select f.id from fields f, fields_entity_assoc fea where fea.entity_id = $insertValue and fea.fields_id = f.id";
                    $getFieldDetailsOut = $this->mysqli->query($getFieldDetailsQuery);                   
                    if($getFieldDetailsOut->num_rows > 0){
                        while($row = $getFieldDetailsOut->fetch_assoc()) {
                            $fieldId = $row["id"];
                            $userEntityFieldAssoc .="($userId, $insertValue, $fieldId, $loginUserId, '$date')";
                            $userEntityFieldAssoc .= ",";
                        }
                    }else {
                        $userEntityFieldAssoc .="($userId, $insertValue, 0, $loginUserId, '$date')";
                        $userEntityFieldAssoc .= ",";
                    }
                }
            }
            
            foreach($deleteValueArray as $deleteValue){
                $deleteUserEntityAssoc .="$deleteValue";
                $deleteUserEntityAssoc .= ",";
            }
           
            $insertEntityFieldAssocFinal = rtrim($userEntityFieldAssoc,',');
            $deleteUserEntityAssocFinal = rtrim($deleteUserEntityAssoc,',');
            $deleteUserEntityAssocFinal.=")";
            //print($deleteUserEntityAssocFinal);
            //print($insertEntityFieldAssocFinal);
            $r1 = $this->mysqli->query($deleteUserEntityAssocFinal);
            $r1 = $this->mysqli->query($insertEntityFieldAssocFinal);
        }
        $this->response(json_encode("The Entity associated with User Successfully"), 200);
        
    }
    
    
    function saveUserEntityFieldAssoc()
    {
        $loginUserId = $_COOKIE["userid"];
        $loginUserRole = $_COOKIE["userRole"];
        if(isset($_GET['userId'])){
            $userId = $_GET['userId'];
        }else {
            $userId = null;
        }
        
        if(isset($_GET['entity'])){
            $entityId = $_GET['entity'];
        }else {
            $entityId = null;
        }
        
        if (isset($_GET['checkedValue'])) {
            $checkedValueList = $_GET['checkedValue'];
        } else {
            $checkedValueList = null;
        }
        
         //print("checkedValueList".$checkedValueList);
        $date = date("Y-m-d H:i:s");
        
        $checkedValueArray = explode(",",$checkedValueList);
        
        //$r = $this->mysqli->query($deleteUserEntityAssoc);
        if(count($checkedValueArray) > 0) {
            $deleteUserEntityAssoc = "delete from user_entity_field_assoc where user_id = $userId and entity_id = $entityId  and field_id in (";
            $userEntityFieldAssoc = "insert into user_entity_field_assoc (user_id, entity_id, field_id, created_by, created_date)
             values ";
            $insertValueArray = array();
            $deleteValueArray = array();
            foreach($checkedValueArray as $checkedValue){
                //print($checkedValue."---");
                $checkedValueSpilit = explode(":",$checkedValue);
                
                if($checkedValueSpilit[1] == "true") {
                    $insertValueArray[] = $checkedValueSpilit[0];
                } else {
                    $deleteValueArray[] = $checkedValueSpilit[0];
                }
            }
           // print(count($deleteValueArray));
           // print("insert array length".count($insertValueArray));
            foreach($insertValueArray as $insertValue){
               // print("userId".$userId);
                $dataAvailabilityCheckQuery = "select * from user_entity_field_assoc where user_id = $userId
                 and entity_id = $entityId and field_id = $insertValue";
               // print("dataAvailabilityCheckQuery".$dataAvailabilityCheckQuery);
                $r1 = $this->mysqli->query($dataAvailabilityCheckQuery);
                if ($r1->num_rows <= 0) {
                    $userEntityFieldAssoc .="($userId, $entityId, $insertValue, $loginUserId, '$date')";
                    $userEntityFieldAssoc .= ",";
                }
            }
            
            foreach($deleteValueArray as $deleteValue){
                $deleteUserEntityAssoc .="$deleteValue";
                $deleteUserEntityAssoc .= ",";
            }
            
            $insertEntityFieldAssocFinal = rtrim($userEntityFieldAssoc,',');
            $deleteUserEntityAssocFinal = rtrim($deleteUserEntityAssoc,',');
            $deleteUserEntityAssocFinal.=")";
           // print($deleteUserEntityAssocFinal);
          //  print($insertEntityFieldAssocFinal);
            $r1 = $this->mysqli->query($deleteUserEntityAssocFinal);
            $r2 = $this->mysqli->query($insertEntityFieldAssocFinal);
        }
        $this->response(json_encode("The Fields associated with Entity Successfully"), 200);        
    }

    private function json($data)
    {
        if (is_array($data)) {
            return json_encode($data, JSON_NUMERIC_CHECK);
        }
    }
}

// Initiiate Library

$api = new User();
$api->functionconnect();
