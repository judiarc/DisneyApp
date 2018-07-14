<?php
require_once ("Rest.inc.php");

class PurchaseOrder extends REST
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
            case 'raiseOrder':
                $this->raiseOrder();
                break;
            case 'orderDetails':
                $this->orderDetails();
                break;
            case 'retriveRaisedOrder':
                $this->retriveRaisedOrder();
                break;
            case 'getStatus':
                $this->getStatus();
                break;
            case 'balancePayament':
                $this->balancePayament();
                break;
            case 'retrivePendingPurchaseOrder':
                $this->retrivePendingPurchaseOrder();
                break;
            case 'getPaymentHistory':
                $this->getPaymentHistory();
                break;
                
            case 'addEntityMethodAssoc':
                $this->addEntityMethodAssoc();
                break;
                
                
        }
    }
    
    /* Function for Add or Update User Details */
    function raiseOrder()
    {
            

        if (isset($_REQUEST['vendorId'])) {
            $vendor_id = $_REQUEST['vendorId'];
        } else {
            $vendor_id = '';
        }
        if (isset($_REQUEST['status'])) {
            $status = strtolower($_REQUEST['status']);
        } else {
            $status = '';
        }
        if (isset($_REQUEST['manday'])) {
            $manday = $_REQUEST['manday'];
        } else {
            $manday = '';
        }
        if (isset($_REQUEST['totalAmount'])) {
            $totalAmount = $_REQUEST['totalAmount'];
        } else {
            $totalAmount = '';
        }
        if (isset($_REQUEST['shotWork'])) {
            $shotWork = $_REQUEST['shotWork'];
        } else {
            $shotWork = '';
        }
        
        
    //  print($identifyId);
    //  print($vendor_id);
    //  print($totalAmount);
   //  print($status);
    //    
        //$date = date("Y-m-d");
        
      $loginUserid = $_COOKIE["userid"];
      $maxIdentifyId=null;
      $maxIdentifyId=$this->getMaxIdentifyId();
     
      $maxIdentifyId = $maxIdentifyId + 1;
      
     // print("fetched value ".$maxIdentifyId);
      $purchaseorder="insert into purchase_order(identify_id,amount,status,created_by) values($maxIdentifyId,$totalAmount,$status,$loginUserid)";
      
       // $purchaseorder="insert into purchase_order(vendor_id,identify_id,amount,status,created_by) values($vendor_id,$identifyId,$totalAmount,$status,$loginUserid)";
     
      // print($purchaseorder);
        //$purchaseorder="INSERT INTO purchase_order('vendor_id', 'identify_id', 'amount', 'status','po_date','created_by','created_date','modified_by','modufied_date','extradield_1') VALUES (5,5,5,5,1993,'40',2018,'40',2018,'2')";
        
        
        
        $r = $this->mysqli->query($purchaseorder);
   
        $po_IdentifyId = mysqli_insert_id($this->mysqli);
        
        $this->addEntityMethodAssoc( $maxIdentifyId,$status,$loginUserid,$shotWork);
        
        if ($po_IdentifyId) {
            $data['success'] = true;
        } else {
            $data['failure'] = true;
           $data['message'] = 'Record is not properly saved in Database';
        }       
        
        
        $this->response(json_encode($data), 200); 
    }
    
    
    private function getMaxIdentifyId()
    {
        $get_max_query = "select max(identify_id) from purchase_order";
        $purchase_order_row = $this->mysqli->query($get_max_query);
        $max_identify = '0';
        while ($row3V = $purchase_order_row->fetch_assoc()) {
            $max_identify = $row3V["max(identify_id)"];
        }
        return $max_identify;
    }
    
    private function addEntityMethodAssoc( $po_IdentifyId,$status,$loginUserid,$shotWork)
    {
        $po_shot_assoc = "INSERT INTO po_shot_assoc (shot_work_details_identify_id,po_identify_id,status, created_by) values($shotWork,$po_IdentifyId,$status,$loginUserid) ";
        
        $this->mysqli->query($po_shot_assoc);

        if ($po_IdentifyId) {
            $data['success'] = true;
            $data['message'] = 'PO Raised Successfully';
            $this->response(json_encode($data), 200);
        } else {
            $data['success'] = false;
            $this->response(json_encode($data), 204);
        }
    }
    
    
    
    
    /* Retrive Pending PurchaseOrder */
    
    private function retrivePendingPurchaseOrder() {
        $loginUserId = $_COOKIE["userid"];
        
        // print("retrive pending order");
        /*  $query3V = "SELECT * FROM shot_details sh, entity_department_assoc ety, vendor vh , (SELECT status,identify_id,
         worker_team_type, worker_id, shot_dept_det_id, MAX(version) AS maxversion FROM shot_work_details WHERE worker_team_type IN (2,3)
         GROUP BY worker_id,worker_team_type,shot_dept_det_id,identify_id ) output WHERE output.shot_dept_det_id=sh.id AND
         output.worker_team_type=ety.entity_type_id and output.worker_id = ety.entity_id AND ety.entity_id =vh.id group by sh.shotcode"; */
        
        /*  $query3V = "SELECT * FROM shot_details sh, entity_department_assoc ety, vendor vh ,shot_dept_details sdd,po_shot_assoc psa, shot_work_details swd, (SELECT status as shotstatus,identify_id ,
         worker_team_type, worker_id, shot_dept_det_id,shot_dept_details_identify_id, MAX(version) AS maxversion FROM shot_work_details WHERE worker_team_type IN (4)
         GROUP BY worker_id,worker_team_type,shot_dept_det_id,identify_id ) output WHERE output.identify_id!=psa.shot_work_details_identify_id and output.maxversion=swd.version and  swd.shot_dept_details_identify_id=sdd.identify_id and sdd.shot_det_id=sh.id AND
         swd.worker_team_type=ety.entity_type_id and swd.worker_id = ety.entity_id AND ety.entity_id =vh.id group by sh.shotcode,ouput.identify_id"; */
        
        $existQueryResult = $this->getTotalCount($loginUserId);
        $count = (int)$existQueryResult;
   //     print("count".$count);
        if ($count == 0) {
            $query3V = "
        SELECT sh.id, sh.shotallocationprojectdetailsid, sh.shotcode, s.status as shot_status,sh.receiveddate, ety.entity_type_id, ety.entity_id, ety.price,
            sdd.internalmandays, vh.id as vendorid, output.shotstatus, vh.name, sdd.shot_det_id, sdd.identify_id as shotDept,
            output.shotWork_Identify FROM shot_details sh, entity_department_assoc ety, vendor vh,
            shot_dept_details sdd, shot_work_details swd, status s,(SELECT status as shotstatus,
                identify_id as shotWork_Identify, worker_team_type, worker_id, shot_dept_det_id, shot_dept_details_identify_id,
                MAX(version) AS maxversion FROM shot_work_details WHERE worker_team_type IN(4) GROUP BY worker_id, worker_team_type,
                shot_dept_det_id, identify_id)
        output WHERE
        output.shot_dept_details_identify_id = sdd.identify_id and sdd.shot_det_id = sh.id AND s.id=output.shotstatus AND 
        output.worker_team_type = ety.entity_type_id and output.worker_id = ety.entity_id AND ety.entity_id = vh.id group by
        sh.shotcode, output.shotWork_Identify ";
            
            // print($query3V);
            $r3V = $this->mysqli->query($query3V) or die($this->mysqli->error . __LINE__);
            if ($r3V->num_rows > 0) {
                $result3V = array();
                while ($row3V = $r3V->fetch_assoc()) {
                    $result3V[] = $row3V;
                }
                // print($result3V);
                $this->response($this->json($result3V), 200); // send PO details
            }
            $this->response('', 204);
            
        }
        
   
    else {
        $query3V = "
 SELECT sh.id, sh.shotallocationprojectdetailsid, sh.shotcode, sh.receiveddate, ety.entity_type_id, ety.entity_id, ety.price,
        sdd.internalmandays, vh.id as vendorid, output.shotstatus, vh.name, sdd.shot_det_id, s.status as shot_status, sdd.identify_id as shotDept,
        output.shotWork_Identify FROM shot_details sh, entity_department_assoc ety, vendor vh,
        shot_dept_details sdd, shot_work_details swd, status s,(SELECT status as shotstatus,
            identify_id as shotWork_Identify, worker_team_type, worker_id, shot_dept_det_id, shot_dept_details_identify_id,
            MAX(version) AS maxversion FROM shot_work_details WHERE worker_team_type IN(4) GROUP BY worker_id, worker_team_type,
            shot_dept_det_id, identify_id)
    output WHERE output.shotWork_Identify not in (select psa.shot_work_details_identify_id from po_shot_assoc psa)  and
    output.shot_dept_details_identify_id = sdd.identify_id and sdd.shot_det_id = sh.id AND output.shotstatus=s.id AND 
    output.worker_team_type = ety.entity_type_id and output.worker_id = ety.entity_id AND ety.entity_id = vh.id group by
    sh.shotcode, output.shotWork_Identify";
        
        // print($query3V);
        $r3V = $this->mysqli->query($query3V) or die($this->mysqli->error . __LINE__);
        if ($r3V->num_rows > 0) {
            $result3V = array();
            while ($row3V = $r3V->fetch_assoc()) {
                $result3V[] = $row3V;
            }
            // print($result3V);
            $this->response($this->json($result3V), 200); // send PO details
        }
        $this->response('', 204);
    }
    
}
    
    
    
    
    
    
    /**
     * @param email
     */private function getTotalCount($loginUserId)
     {
         $existQuery = "SELECT count(*) as total FROM po_shot_assoc";
         //print("query".$existQuery);
         $r3 = $this->mysqli->query($existQuery);
         if ($r3->num_rows > 0) {
             while ($row1 = $r3->fetch_assoc()) {
                 $existQueryResult = $row1['total'];
             }
         }
         
         return $existQueryResult;
    }
    
    
    /* function to get particular PO details */
    function orderDetails()
    {
      /*   $loginUserId = $_COOKIE["userid"];
        $loginUserRole = $_COOKIE["userRole"];
       */ 
        
        if (isset($_GET['purchaseId'])) {
            $orderId = $_GET['purchaseId'];
        } else {
            $orderId = null;
        }
        $retrieveOrder_query = "select po.identify_id, sd.shotcode, sdd.dept_id, po.amount, output.paid,po.po_date,po.status, v.name as vendorname from purchase_order po, po_shot_assoc psa, shot_dept_details sdd, shot_details sd,  vendor v,shot_work_details swd,(select SUM(pea.paid_amount)as paid from payment_entity_assoc pea where pea.entity_type=1 and pea.identify_id='$orderId')output where po.identify_id = psa.po_identify_id and psa.shot_work_details_identify_id = swd.identify_id and swd.worker_id=v.id and po.identify_id= '$orderId' group by po.identify_id";
      //  print($retrieveOrder_query);
        $r3V = $this->mysqli->query($retrieveOrder_query);
        if ($r3V->num_rows > 0) {
            $result3V = array();
            while ($row3V = $r3V->fetch_assoc()) {
                $result3V[] = $row3V;
            }
            // print($result3V);
            $this->response($this->json($result3V), 200); // send PO details
        }
        $this->response('', 204);
        
    }
    
    
    function retriveRaisedOrder()
    {
     
        //$query3V = "SELECT po.identify_id,po.po_date,swd.worker_id,po.status,po.amount, v.name as vendorname,v.id, swd.worker_id FROM purchase_order po, vendor v ,po_shot_assoc psa,shot_work_details swd where swd.identify_id=psa.shot_work_details_identify_id and po.identify_id=psa.po_identify_id and v.id=swd.worker_id group by po.identify_id";
        $query3V ="SELECT po.identify_id,po.po_date,swd.worker_id,po.status,po.amount, v.name as vendorname,v.id,s.status as shot_status, swd.worker_id FROM purchase_order po, vendor v ,po_shot_assoc psa,shot_work_details swd,status s where swd.identify_id=psa.shot_work_details_identify_id and po.identify_id=psa.po_identify_id and v.id=swd.worker_id and s.id=po.status group by po.identify_id";
          //  $query3V = "SELECT po.identify_id,po.po_date,swd.worker_id,po.status, po.amount, v.name as vendorname,v.id, swd.worker_id FROM purchase_order po, vendor v ,shot_work_details swd  where swd.identify_id=po.identify_id  and v.id=swd.worker_id group by po.identify_id ";
        $r3V = $this->mysqli->query($query3V);
        if ($r3V->num_rows > 0) {
            $result3V = array();
            while ($row3V = $r3V->fetch_assoc()) {
                $result3V[] = $row3V;
            }
            // print($result3V);
            $this->response($this->json($result3V), 200); // send PO details
        }
        $this->response('', 204);
        
        

        
    }
    
    
    
    
    

    
     function getStatus(){
        
         $query3V = "select * from status";
         $r3V = $this->mysqli->query($query3V);
         if ($r3V->num_rows > 0) {
             $result3V = array();
             while ($row3V = $r3V->fetch_assoc()) {
                 $result3V[] = $row3V;
             }
             // print($result3V);
             $this->response($this->json($result3V), 200); // send PO details
         }
         $this->response('', 204);
        
    }
    
    /* Function to get Balance Payment Details */
    
    function balancePayament()
    {
        
        if (isset($_REQUEST['identifyId'])) {
            $identifyId = $_REQUEST['identifyId'];
        } else {
            $identifyId= null;
        }
        if (isset($_REQUEST['paid'])) {
            $paid_amount = $_REQUEST['paid'];
        } else {
            $paid_amount= null;
        }
        if (isset($_REQUEST['totalAmount'])) {
            $total_amount = $_REQUEST['totalAmount'];
        } else {
            $total_amount= null;
        }
        
        $loginUserid = $_COOKIE["userid"];
        
        //  print("identify "+$identifyId);
        //print("balance "+$paid_amount);
        
       // $retrieveOrder_query = "select po.identify_id,po.po_date,po.status,po.amount,sum(paid_amount) as paid from purchase_order po, payment_entity_assoc pea where po.identify_id='$orderId' and pea.entity_identify_id='$orderId'";
        //print($retrieveOrder_query);
        $updatePayment="insert into payment_entity_assoc (entity_type,identify_id,total_amount,paid_amount,created_by) values(1,$identifyId,$total_amount,$paid_amount,$loginUserid)";
        
        
        $r3 = $this->mysqli->query($updatePayment);
/*         if ($r3->num_rows > 0) {
            $result = array();
            while ($row1 = $r3->fetch_assoc()) {
                $result[] = $row1;
            }
        }
        $this->response(json_encode($result), 200);
        $this->response('', 204);  */
    }
    
    function getPaymentHistory()
    {
        if (isset($_REQUEST['invoiceId'])) {
            $identifyId = $_REQUEST['invoiceId'];
        } else {
            $identifyId= null;
        }
        if (isset($_REQUEST['entity_type'])) {
            $entity_type = $_REQUEST['entity_type'];
        } else {
            $entity_type= null;
        }
        $retrievePayment_query = "select * from payment_entity_assoc where identify_id='$identifyId' and entity_type='$entity_type'";
       // print($retrievePayment_query);
        
        $r3V = $this->mysqli->query($retrievePayment_query);
        if ($r3V->num_rows > 0) {
            $result3V = array();
            while ($row3V = $r3V->fetch_assoc()) {
                $result3V[] = $row3V;
            }
            // print($result3V);
            $this->response($this->json($result3V), 200); // send PO details
        }
        $this->response('', 204);
        

        
    }
    

    
    private function json($data)
    {
        if (is_array($data)) {
            return json_encode($data, JSON_NUMERIC_CHECK);
        }
    }
}

// Initiiate Library

$api = new PurchaseOrder();
$api->functionconnect();
