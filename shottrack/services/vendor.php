<?php
require_once ("Rest.inc.php");

class Vendor extends REST
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
            case 'addOrUpdateVendor':
                $this->addOrUpdateVendor();
                break;
            case 'removeVendor':
                $this->removeVendor();
                break;
            case 'retrieveVendor':
                $this->retrieveVendor();
                break;
            case 'retrieveEntityDeptAssoc':
                $this->retrieveEntityDeptAssoc();
                break;
            case 'saveEntityDeptAssoc':
                $this->saveEntityDeptAssoc();
                break;
            case 'retrieveEntityStatus':
                $this->retrieveEntityStatus();
                break;
                
                
                
                
        }
    }
    
    function addOrUpdateVendor()
    {
        $vendorId = $_POST['vendorId'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];
        $address1 = $_POST['addressline1'];
        $address2 = $_POST['addressline2'];
        $city = $_POST['city'];
        $state = $_POST['state'];      
        $country = $_POST['country'];
        $department = implode(',', $_POST['departmentid']);  
        $departmentArray = $_POST['departmentid'];
        $type = $_POST['type'];
        $status = $_POST['entitystatus'];
        
        $userid = $_COOKIE['userid'];
        $date = date("Y-m-d");
        $localVendorId = $vendorId;
        
       // echo "id is ";
     //   echo $localVendorId;
     //   echo 
        // print("localVendorId".$localVendorId);
        if($vendorId == null or $vendorId = '') {
            $vendor_insert_query="insert into vendor (name,email,mobile,type, department, address_line1,address_line2,city,state,country,status,created_by,created_date) values('$name','$email','$mobile','$type', '$department', '$address1','$address2','$city','$state','$country','$status','$userid','$date')";
            $this->mysqli->query($vendor_insert_query);
            //print("vendor_insert_query".$vendor_insert_query);
            $localVendorId = mysqli_insert_id($this->mysqli);
            
            $this->addEntityMethodAssoc($departmentArray, $userid, $date,  $localVendorId);
        }
        else {
            $vendor_update_query = "update vendor set name='$name',email='$email',mobile='$mobile', type='$type', department='$department', address_line1='$address1',address_line2='$address2',city='$city',state='$state',country='$country',status='$status',modified_by='$userid',modified_date='$date' where id='$localVendorId'";
           // print("vendor_update_query".$vendor_update_query);           
            $this->mysqli->query($vendor_update_query);
            $this->updateEntityDeptAssoc($departmentArray, $userid , $localVendorId);
        }
        
        if ($localVendorId) {
            $data['success'] = true;
            $data['vendorId'] = $localVendorId;
            $data['message'] = 'Record Saved Successfully';
        }else{
            $data['failure'] = true;
            $data['message'] = 'Record is not properly saved in Database';
        }
        $this->response(json_encode($data), 200);
        
        
        
        
       // $this->response(json_encode($localVendorId), 200); 
    }
    
    /* Update Entity Assac */
    
    private function updateEntityDeptAssoc($departmentArray, $userid, $localVendorId)
    {
        $getDepartmentListQuery = "select department_id from entity_department_assoc where entity_id= $localVendorId and entity_type_id= 4";
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
                $insertDepartmentQuery = "INSERT INTO entity_department_assoc (entity_type_id, entity_id, department_id,status, created_by) values
                    (4, $localVendorId, $dept, 1, $userid)";
      //          print($insertDepartmentQuery);
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
                $deleteDepartmentQuery = "delete from entity_department_assoc where entity_id= $localVendorId
                 and entity_type_id= 4 and department_id=$dbDept1";
    //            print($deleteDepartmentQuery);
                $this->mysqli->query($deleteDepartmentQuery);
            }
        }
    }
    
    
    /*Insert Entity Dept Assac  */
    
    private function addEntityMethodAssoc($departmentArray, $userid, $date,  $localVendorId)
        {
        
            $entity_dept_assoc = "INSERT INTO entity_department_assoc (entity_type_id, entity_id, department_id,status, created_by) values ";
            
            foreach ($departmentArray as $dept) {
                $entity_dept_assoc.= " (4, $localVendorId, $dept, 1, $userid),";
            }
            $entity_dept_assoc =  rtrim($entity_dept_assoc,',');
            // print($entity_dept_assoc);
            $this->mysqli->query($entity_dept_assoc);
            if ($localVendorId) {
                $data['success'] = true;
                $data['vendorId'] = $localVendorId;
                $data['message'] = 'Record Saved Successfully';
            }else{
                $data['failure'] = true;
                $data['message'] = 'Record is not properly saved in Database';
            }
            $this->response(json_encode($data), 200);
            //$this->response(json_encode($localVendorId), 200); 
            
            
       /*  print("entity function");
        $entity_dept_assoc = "INSERT INTO entity_department_assoc (entity_type_id, entity_id, department_id,status, created_by) values ";
       // print("insert query for entity assac "+$entity_dept_assoc);
        foreach ($departmentArray as $dept) {
            $entity_dept_assoc.= " (4, $localVendorId, $dept, 1, $userid),";
        }
        $entity_dept_assoc =  rtrim($entity_dept_assoc,',');
        $this->mysqli->query($entity_dept_assoc); */
    }
    
    
    /*  */
    
    
    /*  */
    private function saveEntityDeptAssoc(){
        $userid = $_COOKIE["userid"];
        $date = date("Y-m-d");
        
        $entity_id = $_POST['vendorDepartmentId'];
        $departmentId = $_REQUEST['department_id'];
        $status = $_POST['status'];
        $price = $_POST['price'];
        $manday = $_POST['manday'];
        $priority = $_POST['priority'];
        $entity_dept_assoc = "update entity_department_assoc  set entity_type_id = 4, entity_id = $entity_id,
             department_id = $departmentId,status = $status, price = $price, no_of_manday = '$manday',
             priority = $priority,  modified_by = $userid, modified_date = $date
             where entity_type_id = 4 and entity_id = $entity_id and
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
    
    
    
    function retrieveVendor()
    {
        $userid = $_COOKIE["userid"];
        
        if(isset($_GET['vendorId'])) {
            $vendorId = $_GET['vendorId'];
           // echo $vendorId;
            // print("get userId".$userId);
        }else {
            $vendorId = null;
        }
        $retrieveVendor_query = "select id, name, email, mobile, type, department as departmentid , address_line1 as addressline1, address_line2 as addressline2, city, state, country,status as entitystatus from vendor";
        if($vendorId != null && $vendorId != ''){
            $retrieveVendor_query.= " where id='$vendorId'";
        }
       // print($retrieveVendor_query);
        $r3 = $this->mysqli->query($retrieveVendor_query);
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
    
    private function retrieveEntityDeptAssoc(){
        if(isset($_GET['vendorId'])) {
            $vendorId = $_GET['vendorId'];
            //print("get clientId".$clientId);
        }else {
            $vendorId = null;
        }
        $query3V = "select entity_id, entity_type_id, department_id, priority, price, no_of_manday as manday, status
                    from entity_department_assoc where entity_id = $vendorId and entity_type_id=4";
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
    function removeVendor()
    {
        if(isset($_GET['vendorId'])) {
            $vendorId = $_GET['vendorId'];
        }else {
            $vendorId = null;
        }
       // $removeEntity_query = "DELETE FROM entity_department_assoc WHERE entity_id in ($vendorId) and entity_type_id=4";
       // $r1 = $this->mysqli->query($removeEntity_query);
        
        $removeVendor_query = "UPDATE VENDOR SET STATUS='2' WHERE ID in ($vendorId)";
        $r = $this->mysqli->query($removeVendor_query);
        if ($r) {
            $this->response(json_encode("success"), 200);
        }else {
            $this->response(json_encode("failure"), 200);
        }
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

$api = new Vendor();
$api->functionconnect();