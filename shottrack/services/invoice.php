<?php
require_once ("Rest.inc.php");

class Invoice extends REST
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
            case 'retrieveInvoiceShot':
                $this->retrieveInvoiceShot();
                break;
            case 'raiseInvoice':
                $this->raiseInvoice();
                break;
            case 'retriveInvoiceRaisedList':
                $this->retriveInvoiceRaisedList();
                break;
            case 'retrieveInvoiceDetails':
                $this->retrieveInvoiceDetails();
                break;
            case 'getStatus':
                $this->getStatus();
                break;
            case 'addEntityMethodAssoc':
                $this->addEntityMethodAssoc();
                break;
            case 'balancePayament':
                $this->balancePayament();
                break;
            case 'getPaymentHistory':
                $this->getPaymentHistory();
                break;
                
                
                
                
        }
    }

    /* The invoice Pending shot retrived based on the status - Method Starts */
    function retrieveInvoiceShot()
    {
        $loginUserId = $_COOKIE["userid"];
        
        $existQueryResult = $this->getTotalCount($loginUserId);
        $count = (int)$existQueryResult;
        //     print("count".$count);
        if ($count == 0) {
            
            $retrieveInvoiceShot ="
SELECT sh.id,sh.shotallocationprojectdetailsid,sh.shotcode,sh.receiveddate,ety.entity_type_id,ety.entity_id,ety.price,s.status as shot_status,
sdd.internalmandays,c.id as clientid,output.shotstatus,c.name ,pd.name as projectname,output.shot_dept_details_identify_id,sdd.shot_det_id,sdd.identify_id as shotDept,
output.shotWork_Identify FROM shot_details sh, entity_department_assoc ety, client c ,
shot_dept_details sdd,shot_work_details swd,project_details pd,status s,
(SELECT status as shotstatus,
identify_id as shotWork_Identify, worker_team_type, worker_id, shot_dept_det_id, shot_dept_details_identify_id,
MAX(version) AS maxversion FROM shot_work_details WHERE status IN (18) GROUP BY worker_id,worker_team_type,
shot_dept_det_id,identify_id )
output WHERE output.shot_dept_details_identify_id=sdd.identify_id and sdd.shot_det_id=sh.id AND pd.id=sh.shotallocationprojectdetailsid and output.shotstatus=s.id and 
 output.worker_team_type=ety.entity_type_id and output.worker_id = ety.entity_id AND ety.entity_id =c.id group by
 sh.shotcode,output.shotWork_Identify";
            
            
            $r3 = $this->mysqli->query($retrieveInvoiceShot);
            //print($r3);
            // print("number of rows".$r3->num_rows);
            $result = array();
            if ($r3->num_rows > 0) {
                while ($row1 = $r3->fetch_assoc()) {
                    $result[] = $row1;
                }
            }
            $this->response(json_encode($result), 200);
            $this->response('', 204);
            
        }
        else
        {
        $retrieveInvoiceShot ="
SELECT sh.id,sh.shotallocationprojectdetailsid,sh.shotcode,sh.receiveddate,ety.entity_type_id,ety.entity_id,ety.price,s.status as shot_status,
sdd.internalmandays,c.id as clientid,output.shotstatus,c.name ,pd.name as projectname,output.shot_dept_details_identify_id,sdd.shot_det_id,sdd.identify_id as shotDept,
output.shotWork_Identify FROM shot_details sh, entity_department_assoc ety, client c ,status s,
shot_dept_details sdd, shot_work_details swd,project_details pd, 
(SELECT status as shotstatus,
identify_id as shotWork_Identify, worker_team_type, worker_id, shot_dept_det_id, shot_dept_details_identify_id, 
MAX(version) AS maxversion FROM shot_work_details WHERE status IN (18) GROUP BY worker_id,worker_team_type,
shot_dept_det_id,identify_id ) 
output WHERE output.shot_dept_details_identify_id not in (select isa.shot_dept_details_identifyid from invoice_shot_assoc isa) and 
output.shot_dept_details_identify_id=sdd.identify_id and sdd.shot_det_id=sh.id AND output.shotstatus=s.id AND pd.id=sh.shotallocationprojectdetailsid and
 output.worker_team_type=ety.entity_type_id and output.worker_id = ety.entity_id AND ety.entity_id =c.id group by
 sh.shotcode,output.shotWork_Identify";
        
    
        $r3 = $this->mysqli->query($retrieveInvoiceShot);
        //print($r3);
        // print("number of rows".$r3->num_rows);
        $result = array();
        if ($r3->num_rows > 0) {
            while ($row1 = $r3->fetch_assoc()) {
                $result[] = $row1;
            }
        }
        $this->response(json_encode($result), 200);
        $this->response('', 204);
        }
        
        
        
      
    }
    
    
    /**
     * @param email
     */private function getTotalCount($loginUserId)
     {
         $existQuery = "SELECT count(*) as total FROM invoice_shot_assoc";
         //print("query".$existQuery);
         $r3 = $this->mysqli->query($existQuery);
         if ($r3->num_rows > 0) {
             while ($row1 = $r3->fetch_assoc()) {
                 $existQueryResult = $row1['total'];
             }
         }
         
         return $existQueryResult;
    }
    
    
    
    

    /* The invoice Pending shot retrived based on the status - Method Ends */
    
    /* Function for Add or Update User Details */
    function raiseInvoice()
    {
        

        if (isset($_REQUEST['client'])) {
            $client_id = $_REQUEST['client'];
        } else {
            $client_id = '';
        }
        if (isset($_REQUEST['status'])) {
            $status = strtolower($_REQUEST['status']);
        } else {
            $status = '';
        }

        if (isset($_REQUEST['amount'])) {
            $totalAmount = $_REQUEST['amount'];
        } else {
            $totalAmount = '';
        }
        
        if (isset($_REQUEST['shotDeptIdentifyId'])) {
            $shotDeptIdentifyId = $_REQUEST['shotDeptIdentifyId'];
        } else {
            $shotDeptIdentifyId = '';
        }

        $invoice_date = $date = date("Y-m-d H:i:s");
        $created_date = $date = date("Y-m-d H:i:s");
      
        $loginUserid = $_COOKIE["userid"];
        $maxIdentifyId=null;
        $maxIdentifyId=$this->getMaxIdentifyId();
        
        $maxIdentifyId = $maxIdentifyId + 1;
        
       //$invoiceInsertQuery = "insert into invoice(identify_id, shot_dept_identify_id, amount,status, invoice_date, created_by, created_date)   values($maxIdentifyId, $shot_dept_identify_id, $totalAmount,$status, $invoice_date, $loginUserid, $created_date)";
         $invoiceInsertQuery ="insert into invoice(identify_id,amount,status,created_by) values($maxIdentifyId,$totalAmount,$status,$loginUserid)";
       // print($invoiceInsertQuery);
        // $purchaseorder="INSERT INTO purchase_order('vendor_id', 'identify_id', 'amount', 'status','po_date','created_by','created_date','modified_by','modufied_date','extradield_1') VALUES (5,5,5,5,1993,'40',2018,'40',2018,'2')";
        
        $r = $this->mysqli->query($invoiceInsertQuery);
        
        $invoiceIdentifyId = mysqli_insert_id($this->mysqli);
        $this->addEntityMethodAssoc($maxIdentifyId,$status,$loginUserid,$shotDeptIdentifyId);
        
        if ($identifyId) {
            $data['success'] = true;
            $data['identifyId'] = $invoiceIdentifyId;
        } else {
            $data['failure'] = true;
            $data['message'] = 'Record is not properly saved in Database';
        }
        
        $this->response(json_encode($data), 200);
    }
    
    
    private function getMaxIdentifyId()
    {
        $get_max_query = "select max(identify_id) from invoice";
        $invoice_row = $this->mysqli->query($get_max_query);
        $max_identify = '0';
        while ($row3V = $invoice_row->fetch_assoc()) {
            $max_identify = $row3V["max(identify_id)"];
        }
        return $max_identify;
    }

    private function addEntityMethodAssoc( $invoiceIdentifyId,$status,$loginUserid,$shotDeptIdentifyId)
    {
        $invoice_shot_assoc = "INSERT INTO invoice_shot_assoc (shot_dept_details_identifyid,invoice_identify_id,status, created_by) values($shotDeptIdentifyId,$invoiceIdentifyId,$status,$loginUserid) ";
        
$this->mysqli->query($invoice_shot_assoc);
        
        if ($invoiceIdentifyId) {
            $data['success'] = true;
            $data['message'] = 'Invoice Raised Successfully';
            $this->response(json_encode($data), 200);
        } else {
            $data['success'] = false;
            $this->response(json_encode($data), 204);
        }
        
 }
    
    /* pull all invice details */
    function retriveInvoiceRaisedList()
    {
        //$retirveInvoice = "SELECT i.identify_id,i.invoice_date,swd.worker_id,i.status,i.amount, c.name as clientname,c.id, swd.worker_id FROM invoice i, client c ,invoice_shot_assoc isa,shot_work_details swd where swd.shot_dept_details_identify_id=isa.shot_dept_details_identifyid and i.identify_id=isa.invoice_identify_id and c.id=swd.worker_id group by i.identify_id ";
        $retirveInvoice ="SELECT i.identify_id,i.invoice_date,swd.worker_id,i.status,i.amount, c.name as clientname,s.status as shot_status,c.id, swd.worker_id FROM invoice i, client c ,invoice_shot_assoc isa,shot_work_details swd, status s where swd.shot_dept_details_identify_id=isa.shot_dept_details_identifyid and i.identify_id=isa.invoice_identify_id and c.id=swd.worker_id and i.status=s.id group by i.identify_id";
       // print($retirveInvoice);
        
        $r3 = $this->mysqli->query($retirveInvoice);
        // print("number of rows".$r3->num_rows);
        $result = array();
        if ($r3->num_rows > 0) {
            while ($row1 = $r3->fetch_assoc()) {
                $result[] = $row1;
            }
        }
        $this->response(json_encode($result), 200);
        $this->response('', 204);
    }

    /* function to get particular invoice details */
    function retrieveInvoiceDetails()
    {
        
          $loginUserId = $_COOKIE["userid"];
          $loginUserRole = $_COOKIE["userRole"];
        
        if (isset($_REQUEST['invoiceNo'])) {
            $invoiceNo = $_REQUEST['invoiceNo'];
        } else {
            $invoiceNo = null;
        }
        $invoiceDetails_query = "select i.identify_id, i.amount, output.paid,i.invoice_date,i.status, pd.name as projectname, c.name as clientname from invoice i, invoice_shot_assoc isa, shot_dept_details sdd, shot_details sd, project_details pd, client c,(select SUM(pea.paid_amount)as paid from payment_entity_assoc pea where pea.entity_type=2 and pea.identify_id='$invoiceNo' )output where  i.identify_id = isa.invoice_identify_id and isa.shot_dept_details_identifyid = sdd.identify_id and sdd.shot_det_id = sd.id and sd.shotallocationprojectdetailsid = pd.id and pd.projectdetailsclientid = c.id and i.identify_id= '$invoiceNo' group by i.identify_id";
        //print($invoiceDetails_query);
        $r3 = $this->mysqli->query($invoiceDetails_query);
        
        if ($r3->num_rows > 0) {
            $result = array();
            while ($row1 = $r3->fetch_assoc()) {
                $result[] = $row1;
            }
        }
        //print("result "+$r3);
        $this->response(json_encode($result), 200);
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
    
    
    /* Function to update balance Payment details */
    
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
        
        if($identifyId!=NULL)
        {
       $query = "insert into payment_entity_assoc(entity_type,identify_id,total_amount,paid_amount,created_by) values('2','$identifyId','$total_amount','$paid_amount','$loginUserid')";
       //print($query); 
       
       $r = $this->mysqli->query($query);
        /* $success = array(
            'success' => "true",
            "message" => "Successfully updated."
        ); */
       $paymentId = mysqli_insert_id($this->mysqli);
       if ($paymentId) {
           $data['success'] = true;
           $data['paymentId'] = $paymentId;
           $data['message'] = 'Payment updated Successfully';
           
       } else {
           $data['failure'] = true;
           $data['message'] = 'Record is not properly saved in Database';
       }
        $this->response($this->json($data), 200);
        } else
        $this->response('', 204); // If no records "No Content" status
        
        
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

$api = new Invoice();
$api->functionconnect();
