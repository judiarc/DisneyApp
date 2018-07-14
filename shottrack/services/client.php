<?php
require_once ("Rest.inc.php");

class Client extends REST
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
            case 'addOrUpdateClient':
                $this->addOrUpdateClient();
                break;
            case 'removeClient':
                $this->removeClient();
                break;
            case 'retrieveClient':               
                $this->retrieveClient();
                break;                
            case 'getClientType':
                $this->getClientType();
                break;
            case 'retrieveEntityDeptAssoc':
                $this->retrieveEntityDeptAssoc();
                break;
            case 'saveEntityDeptAssoc':
                $this->saveEntityDeptAssoc();
                break;
            case 'getClientNameList':
                $this->getClientNameList();
                break;
        }
    }
    
    function addOrUpdateClient()
    {
        $clientId = $_POST['clientId'];
        $name = $_POST['name'];
        $email = $_POST['email'];       
        $mobile = $_POST['mobile'];        
        $address1 = $_POST['addressline1'];
        $address2 = $_POST['addressline2'];  
        $city = $_POST['city'];
        $state = $_POST['state'];
        $country = $_POST['country'];
        $currency = $_POST['currencyid'];
        $poc = $_POST['poc'];
        $type = $_POST['typeid'];
        $status = $_POST['entitystatus'];
        $departmentArray = $_POST['departmentid'];
        $department = implode(',', $_POST['departmentid']);       
        $userid = $_COOKIE["userid"];
        $date = date("Y-m-d");
        $localClientId = $clientId;
       // print("department".$department);
        if($clientId == null or $clientId = '') {
            $company_id =  $this->getCompanyIdFromUser($userid);
            $client_insert_query = "INSERT INTO CLIENT (NAME, EMAIL, PHONE, COMPANY_NAME, ADDRESS_LINE1, ADDRESS_LINE2,CITY,
            STATE, COUNTRY, CURRENCY, POC, TYPE, STATUS,DEPARTMENT_ID, CREATED_DATE, CREATED_BY) VALUES('$name', '$email','$mobile',
            '$company_id', '$address1','$address2', '$city',
            '$state','$country','$currency','$poc','$type','$status', '$department', '$date', '$userid')";
            //print("client_insert_query".$client_insert_query);
            $this->mysqli->query($client_insert_query);
            $localClientId = mysqli_insert_id($this->mysqli);
            
            $this->addEntityMethodAssoc($departmentArray, $userid, $date, $localClientId);   
            
        }else {            
            $client_update_query = "UPDATE CLIENT SET NAME = '$name', EMAIL = '$email', PHONE='$mobile', ADDRESS_LINE1='$address1',
            ADDRESS_LINE2 = '$address2', CITY='$city', STATE='$state', COUNTRY='$country', CURRENCY='$currency',POC='$poc', TYPE='$type',STATUS='$status',
            department_id = '$department', modified_by = '$userid', modified_date = '$date' WHERE ID='$localClientId'";
            //print($client_update_query);
            $this->mysqli->query($client_update_query);    
            
            $this->updateEntityDeptAssoc($departmentArray, $userid, $date, $localClientId);
            
        }
        if ($localClientId) {
            $data['success'] = true;
            $data['clientId'] = $localClientId;
            $data['message'] = 'Record Saved Successfully';
        }else{
            $data['failure'] = true;
            $data['message'] = 'Record is not properly saved in Database';
        }
        $this->response(json_encode($data), 200);
    }
    /**
     * @param departmentArray
     * @param userid
     * @param date
     * @param localClientId
     * @param row1
     * @param availableFlag
     * @param insertDepartmentQuery
     * @param deleteDepartmentQuery
     */
     private function updateEntityDeptAssoc($departmentArray, $userid, $date, $localClientId)
    {
        $getDepartmentListQuery = "select department_id from entity_department_assoc where entity_id= $localClientId and entity_type_id= 5";
        $r3 = $this->mysqli->query($getDepartmentListQuery);
        $result = array();
        if($r3->num_rows > 0){                
            while($row1 = $r3->fetch_assoc()){
                $result[] = $row1['department_id'];
            }
        }
        foreach ($departmentArray as $dept) {
           
            $availableFlag = false; 
            foreach($result as $dbDept) { 
               // print("dept1" + $dept1 + "dbDept1" + $dbDept1);
                if($dept == $dbDept) {
                    $availableFlag = true;
                    break;
                }
            } 
            if(!$availableFlag) {
               // print("insertDept".$dept);
                $insertDepartmentQuery = "INSERT INTO entity_department_assoc (entity_type_id, entity_id, department_id,status, created_by, CREATED_DATE) values 
                    (5, $localClientId, $dept, 1, $userid, $date)";
                print($insertDepartmentQuery);
                $this->mysqli->query($insertDepartmentQuery);
            }
        }
        
        foreach ($result as $dbDept1) {
            
            $availableFlag = false;
            foreach($departmentArray as $dept1) {
              //  print("dept1" + $dept1 + "dbDept1" + $dbDept1);
                if($dept1 == $dbDept1) {
                    $availableFlag = true;
                    break;
                }
            }
            if(!$availableFlag) {
                //print("deleteDept".$dbDept);
                $deleteDepartmentQuery = "delete from entity_department_assoc where entity_id= $localClientId
                 and entity_type_id= 5 and department_id=$dbDept1";
                print($deleteDepartmentQuery);
                $this->mysqli->query($deleteDepartmentQuery);
            }
        }
    }

    /**
     * @param departmentArray
     * @param userid
     * @param date
     * @param localClientId
     * @param entity_dept_assoc
     */private function addEntityMethodAssoc($departmentArray, $userid, $date, $localClientId)
    {
        $entity_dept_assoc = "INSERT INTO entity_department_assoc (entity_type_id, entity_id, department_id,status, created_by, CREATED_DATE) values ";
        
        foreach ($departmentArray as $dept) {
            $entity_dept_assoc.= " (5, $localClientId, $dept, 1, $userid, $date),";
        }
           $entity_dept_assoc =  rtrim($entity_dept_assoc,',');           
        $this->mysqli->query($entity_dept_assoc);
    
    
        if ($localClientId) {
            $data['success'] = true;
            $data['clientId'] = $localClientId;
            $data['message'] = 'Record Saved Successfully';
        }else{
            $data['failure'] = true;
            $data['message'] = 'Record is not properly saved in Database';
        }
        $this->response(json_encode($data), 200);
    
    }
    
    private function saveEntityDeptAssoc(){
        $userid = $_COOKIE["userid"];
        $date = date("Y-m-d");        
        
        $entity_id = $_POST['clientDeptId'];
        $departmentId = $_REQUEST['department_id'];
        $status = $_POST['status'];
        $price = $_POST['price'];
        $priority = $_POST['priority'];
        $entity_dept_assoc = "update entity_department_assoc  set entity_type_id = 5, entity_id = $entity_id,
             department_id = $departmentId,status = $status, price = $price,
             priority = $priority,  modified_by = $userid, modified_date = $date 
             where entity_type_id = 5 and entity_id = $entity_id and
             department_id = $departmentId";
       // print($entity_dept_assoc);
        $this->mysqli->query($entity_dept_assoc);
        
        if ($entity_id) {
            $data['success'] = true;            
            $data['message'] = 'Record Saved Successfully';
        }else{
            $data['failure'] = true;
            $data['message'] = 'Record is not properly saved in Database';
        }
        $this->response(json_encode($data), 200);
    } 

    
    
    function getClientNameList()
    {
        $userid = $_COOKIE["userid"];      
        $loginUserRole = $_COOKIE["userRole"];
        $company_id =  $this->getCompanyIdFromUser($userid);
        $retrieveClient_query = "SELECT * from client where status='1'";
        if($loginUserRole != '' && $loginUserRole != "0" && $company_id !=''){
            $retrieveClient_query.= " and company_name= '$company_id'";
        }
        
        // print($retrieveClient_query);
        $r3 = $this->mysqli->query($retrieveClient_query);
        if($r3->num_rows > 0){
            // print("count".$r3->num_rows);
            $result = array();
            while($row1 = $r3->fetch_assoc()){
                $result[] = $row1;
            }
            $this->response(json_encode($result), 200); // send client details
        }
        
        $this->response('',204);
    }
    
    function retrieveClient()
    {
        $userid = $_COOKIE["userid"];
        $loginUserRole = $_COOKIE["userRole"];
       // print($userid);      
        if(isset($_GET['clientId'])) {
            $clientId = $_GET['clientId'];
            //print("get clientId".$clientId);
        }else {
            $clientId = null;
        }
        
        $company_id =  $this->getCompanyIdFromUser($userid);
        $retrieveClient_query = "SELECT id, name, email, phone as mobile, address_line1 as addressline1, address_line2 as addressline2,
            city, state, country, currency as currencyid, poc, type as typeid, department_id as departmentid, status as entitystatus from client where status in (0,1,2) ";
        if($loginUserRole != '' && $loginUserRole != "0" && $company_id !=''){
            $retrieveClient_query.= " and company_name= '$company_id'";
        }
        if($clientId != null && $clientId != ''){
            $retrieveClient_query.= " AND ID='$clientId'";
        }
      // print($retrieveClient_query);
        $r3 = $this->mysqli->query($retrieveClient_query);
        if($r3->num_rows > 0){
           // print("count".$r3->num_rows);
            $result = array();
            while($row1 = $r3->fetch_assoc()){
                $result[] = $row1;
            }
            $this->response(json_encode($result), 200); // send client details
        }
              
        $this->response('',204);
    }
    
    function removeClient()
    {
        if(isset($_GET['clientId'])) {
            $clientId = $_GET['clientId'];           
        }else {
            $clientId = null;
        } 
        
      //  $removeEntity_query = "DELETE FROM entity_department_assoc WHERE entity_id in ($clientId) and entity_type_id=5";
        //$r1 = $this->mysqli->query($removeEntity_query);
        
        $removeClient_query = "UPDATE CLIENT SET STATUS='2' WHERE ID in ($clientId)";
       // print($removeClient_query);
        $r = $this->mysqli->query($removeClient_query);
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
    private function getClientType(){
        
        $query3V = "select id, value from client_type";
        $r3V = $this->mysqli->query($query3V) or die($this->mysqli->error . __LINE__);
        if ($r3V->num_rows > 0) {
            $result3V = array();
            while ($row3V = $r3V->fetch_assoc()) {
                $result3V[] = $row3V;
            }
            // print($result3V);
            $this->response($this->json($result3V), 200); // send user details
        }
        $this->response('', 204);
    }
    
    private function retrieveEntityDeptAssoc(){
        if(isset($_GET['clientId'])) {
            $clientId = $_GET['clientId'];
            //print("get clientId".$clientId);
        }else {
            $clientId = null;
        }
        $query3V = "select entity_id, entity_type_id, department_id, priority, price, no_of_manday, status 
                    from entity_department_assoc where entity_id = '$clientId' and entity_type_id=5" ;
       // print($query3V);
        $r3V = $this->mysqli->query($query3V) or die($this->mysqli->error . __LINE__);
        if ($r3V->num_rows > 0) {
            $result3V = array();
            while ($row3V = $r3V->fetch_assoc()) {
                $result3V[] = $row3V;
            }
            // print($result3V);
            $this->response($this->json($result3V), 200); // send user details
        }
        $this->response('', 204);
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

$api = new Client();
$api->functionconnect();