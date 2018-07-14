<?php
require_once ("Rest.inc.php");

class Process extends REST
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
            case 'artist':
                $this->artist();
                break;
            case 'vendor':
                $this->vendor();
                break;
            case 'client':
                $this->client();
                break;
            case 'projectdet':
                $this->projectdet();
                break;
            case 'shotdet':
                $this->shotdet();
                break;
            case 'projectalloc':
                $this->projectalloc();
                break;
            case 'shotalloc':
                $this->shotalloc();
                break;
            case 'users':
                $this->users();
                break;
            case 'addshot':
                $this->addshot();
                break;
            case 'addshotdeptroto':
                $this->addshotdeptroto();
                break;
            case 'addShotDept':
                $this->addShotDept();
                break;
            case 'addshotdeptpaint':
                $this->addshotdeptpaint();
                break;
            case 'addshotdeptmatchmove':
                $this->addshotdeptmatchmove();
                break;
            case 'addshotdeptcomp':
                $this->addshotdeptcomp();
                break;
            case 'addshotdept3d':
                $this->addshotdept3d();
                break;
            case 'shotallocroto':
                $this->shotallocroto();
                break;
            case 'shotallocrototime':
                $this->shotallocrototime();
                break;
            case 'shotallocrotovendortime':
                $this->shotallocrotovendortime();
                break;
            case 'shotallocrotofreelancertime':
                $this->shotallocrotofreelancertime();
                break;
            case 'shotallocpaintvendortime':
                $this->shotallocpaintvendortime();
                break;
            case 'shotallocpaintfreelancertime':
                $this->shotallocpaintfreelancertime();
                break;
            case 'shotallocmatchmovevendortime':
                $this->shotallocmatchmovevendortime();
                break;
            case 'shotallocmatchmovefreelancertime':
                $this->shotallocmatchmovefreelancertime();
                break;
            case 'shotalloccompvendortime':
                $this->shotalloccompvendortime();
                break;
            case 'shotalloccompfreelancertime':
                $this->shotalloccompfreelancertime();
                break;
            case 'shotalloc3dvendortime':
                $this->shotalloc3dvendortime();
                break;
            case 'shotalloc3dfreelancertime':
                $this->shotalloc3dfreelancertime();
                break;
            case 'shotallocpaint':
                $this->shotallocpaint();
                break;
            case 'shotallocpainttime':
                $this->shotallocpainttime();
                break;
            case 'shotallocmatch':
                $this->shotallocmatch();
                break;
            case 'shotallocmatchmovetime':
                $this->shotallocmatchmovetime();
                break;
            case 'shotalloccomp':
                $this->shotalloccomp();
                break;
            case 'shotalloccomptime':
                $this->shotalloccomptime();
                break;
            case 'shotalloc3d':
                $this->shotalloc3d();
                break;
            case 'shotalloc3dtime':
                $this->shotalloc3dtime();
                break;
            case 'interimroto':
                $this->interimroto();
                break;
            case 'interimpaint':
                $this->interimpaint();
                break;
            case 'interimmatchmove':
                $this->interimmatchmove();
                break;
            case 'interimcomp':
                $this->interimcomp();
                break;
            case 'interim3d':
                $this->interim3d();
                break;
            case 'rotofinal':
                $this->rotofinal();
                break;
            case 'paintfinal':
                $this->paintfinal();
                break;
            case 'matchmovefinal':
                $this->matchmovefinal();
                break;
            case 'compfinal':
                $this->compfinal();
                break;
            case 'dfinal':
                $this->dfinal();
                break;
            case 'formbank':
                $this->formbank();
                break;
            case 'formftp':
                $this->formftp();
                break;
            case 'formbankclient':
                $this->formbankclient();
                break;
            case 'formftpclient':
                $this->formftpclient();
                break;
            case 'freelancer':
                $this->freelancer();
                break;
            case 'formbankfreelancer':
                $this->formbankfreelancer();
                break;
            case 'formftpfreelancer':
                $this->formftpfreelancer();
                break;
            case 'projectdetpayment':
                $this->projectdetpayment();
                break;
            case 'projectdetpaymentedit':
                $this->projectdetpaymentedit();
                break;
            case 'addShotAlloc':
                $this->addShotAlloc();
                break;
            case 'addShotAllocWorkDetail':
                $this->addShotAllocWorkDetail();
                break;
            case 'saveOrSearchDashBoard':
                $this->saveOrSearchDashBoard();
                break;
        }
    }

    

  

    /*
     * insertion and updation for project details
     */
    function projectdet()
    {
        $name = $_POST['name'];
        $clientid = $_POST['clientid'];
        $receiveddate = $_POST['receiveddate'];
        if (isset($_POST['selectedclientid'])) {
            $selectedclientname = $_POST['selectedclientid'];
            if ($selectedclientname == '') {
                $selectedclientname = $_POST['selectedclientidd'];
            }
        }
        
        if (isset($_POST['cost'])) {
            $cost = $_POST['cost'];
        } else {
            $cost = NULL;
        }
        $entityid = $_POST['entityid'];
        $editprojectdetid = $_POST['editprojectdetid'];
        $username = $_COOKIE["username"];
        $date = date("Y-m-d H:i:s");
        echo $editprojectdetid;
        if ($editprojectdetid <= 0) {
            try {
                $entitydet = "INSERT INTO entity_det_table(name,entity_id,is_deleted,created_by)VALUES('$name','$entityid','0','$username')";
                $r = $this->mysqli->query($entitydet);
                $entity_det_id = mysqli_insert_id($this->mysqli);
                /* print("Entity Id is" . $name);
                print("name is" . $entity_det_id);
                print("clientid Id is" . $clientid);
                print("selectedclientname Id is" . $selectedclientname);
                print("receiveddate Id is" . $receiveddate);
                print("cost is" . $cost);
                print("username is" . $username); */
                if ($r) {
                    $querydet = $this->mysqli->query("INSERT INTO project_details (name,entity_id,projectdetailsclientid,receiveddate,cost,created_by)
VALUES ('$name','$entity_det_id','$clientid','$receiveddate','$cost','$username'); ");
                    //print("querydet Value is" . $querydet);
                    if ($querydet) {
                        $data['success'] = true;
                        $data['message'] = 'Successfully Created!';
                    }
                }
                if (empty($data)) {
                    // // echo json_encode($data);
                }
            } catch (Exception $e) {
                print("Exception in insertion" . $e);
            }
        } else {
            
            $projdetails_update = "UPDATE project_details SET name='$name',projectdetailsclientid='$clientid',clientname='$selectedclientname',receiveddate='$receiveddate',cost='$cost',modified_by='$username',modified_date='$date' WHERE entity_id='$editprojectdetid' ";
            $r = $this->mysqli->query($projdetails_update);
            
            if ($r) {
                $data['success'] = true;
                $data['message'] = 'Successfully Updated!';
                echo json_encode($data);
            }
        }
    }

    /*
     * insertion and updation for payment form
     */
    function projectdetpayment()
    {
        $name = $_POST['name'];
        $clientid = $_POST['clientid'];
        $receiveddate = $_POST['receiveddate'];
        $targetdate = $_POST['targetdate'];
        $internaldate = $_POST['internaltargetdate'];
        $cost = $_POST['cost'];
        $amountreceived = $_POST['amountreceived'];
        $paymentdate = $_POST['paymentdate'];
        $transfermode = $_POST['transfermode'];
        $amtpending = $_POST['amtpending'];
        $followdate = $_POST['followdate'];
        $entityid = $_POST['entityid'];
        $editprojectdetid = $_POST['editprojectdetid'];
        $dueid = $_POST['dueid'];
        $username = $_COOKIE["username"];
        $date = date("Y-m-d H:i:s");
        if ($dueid) {
            $checkdue = $this->mysqli->query("SELECT MAX(due_id) as maxid FROM project_details  WHERE entity_id='$editprojectdetid' LIMIT 1");
            $rescheckdue = mysqli_fetch_assoc($checkdue);
            $maxid = $rescheckdue['maxid'];
            $due_id = $maxid + 1;
            $projdetails_insert = $this->mysqli->query("INSERT INTO project_details (name,entity_id,due_id,projectdetailsclientid,receiveddate,targetdate,internaltargetdate,cost,amountreceived,paymentdate,transfermodeid,amtpending,followdate,created_by)
VALUES ('$name','$editprojectdetid','$due_id','$clientid','$receiveddate','$targetdate','$internaldate','$cost','$amountreceived','$paymentdate','$transfermode','$amtpending','$followdate','$username'); ");
            if ($projdetails_insert) {
                $data['success'] = true;
                $data['clientid'] = $clientid;
                $data['message'] = 'Successfully Updated!';
                echo json_encode($data);
            }
        } else {
            $due_id = '1';
            $projdetails_update = "UPDATE project_details SET due_id='$due_id',name='$name',projectdetailsclientid='$clientid',receiveddate='$receiveddate',targetdate='$targetdate',internaltargetdate='$internaldate',cost='$cost' ,amountreceived='$amountreceived',paymentdate='$paymentdate',transfermodeid='$transfermode',amtpending='$amtpending',followdate='$followdate',modified_by='$username',modified_date='$date' WHERE entity_id='$editprojectdetid'";
            $r = $this->mysqli->query($projdetails_update);
            if ($r) {
                $data['success'] = true;
                $data['message'] = 'Successfully Updated!';
                echo json_encode($data);
            }
        }
    }

    /*
     * insertion and updation for edit payment form
     */
    function projectdetpaymentedit()
    {
        $name = $_POST['name'];
        $clientid = $_POST['clientid'];
        $receiveddate = $_POST['receiveddate'];
        $targetdate = $_POST['targetdate'];
        $internaldate = $_POST['internaltargetdate'];
        $cost = $_POST['cost'];
        $amountreceived = $_POST['amountreceived'];
        $paymentdate = $_POST['paymentdate'];
        $transfermode = $_POST['transfermode'];
        $amtpending = $_POST['amtpending'];
        $followdate = $_POST['followdate'];
        $entityid = $_POST['entityid'];
        $editprojectdetid = $_POST['editprojectdetid'];
        $dueid = $_POST['dueid'];
        $username = $_COOKIE["username"];
        $date = date("Y-m-d H:i:s");
        
        $projdetails_update1 = "UPDATE project_details SET name='$name',projectdetailsclientid='$clientid',receiveddate='$receiveddate',targetdate='$targetdate',internaltargetdate='$internaldate',cost='$cost' ,amountreceived='$amountreceived',paymentdate='$paymentdate',transfermodeid='$transfermode',amtpending='$amtpending',followdate='$followdate',modified_by='$username',modified_date='$date' WHERE entity_id='$editprojectdetid' AND due_id='$dueid'";
        $r1 = $this->mysqli->query($projdetails_update1);
        
        if ($r1) {
            $data['success'] = true;
            $data['clientid'] = $clientid;
            $data['message'] = 'Successfully Updated!';
            echo json_encode($data);
        }
    }

    /*
     * insertion and updation for adding shot
     */
    function addshot()
    {
        try {
            $shotcode = $_POST['shotcode'];
            $project = $_POST['projectdetailsid'];
            $projectname = $_POST['projectname'];
            $departmentArray = $_POST['id'];
            $department = implode(',', $_POST['id']);
            $receiveddate = $_POST['receiveddate'];
            $input_path = $_POST['input_path'];
            $version = $_POST['version'];
            $entityid = $_POST['entityid'];
            $status = $_POST['status'];
            $shot_det_id = $_POST['shot_det_id'];
            $input_path = $_POST['input_path'];
            $username = $_COOKIE["username"];
            $date = date("Y-m-d H:i:s");
            // print("department".$shot_det_id);
            if ($shot_det_id <= 0) {
                $entitydet = "INSERT INTO entity_det_table(name,entity_id,is_deleted,created_by)VALUES('$shotcode','$entityid','0','$username')";
                $r = $this->mysqli->query($entitydet);
                $entity_det_id = mysqli_insert_id($this->mysqli);
                // print("entity_det_id".$entity_det_id);
                if ($r) {
                    $insertShotDetailsQuery = "INSERT INTO shot_details(entity_id,row_id,shotallocationprojectname,shotallocationprojectdetailsid,shotcode,receiveddate,shotallocationdepartmentid,input_path,version,status,created_by)
                VALUES ('$entity_det_id','0','$projectname','$project','$shotcode','$receiveddate','$department','$input_path','1','$status','$username')";
                    // print("insertShotDetailsQuery".$insertShotDetailsQuery);
                    $querydet = $this->mysqli->query($insertShotDetailsQuery);
                    
                    $shot_det_id = mysqli_insert_id($this->mysqli);
                    // print("shot_det_id".$shot_det_id);
                    foreach ($departmentArray as $dept) {
                        $shot_dept_identify_id = $this->getShotDeptIdentifyId();
                        $querydeptdet = "INSERT INTO shot_dept_details (identify_id, shot_det_id, dept_id,status, created_by, version) values ('$shot_dept_identify_id', '$shot_det_id', '$dept', '0' ,'$username', '1')";
                        $this->mysqli->query($querydeptdet);
                    }
                    if ($querydet) {
                        $data['success'] = true;
                        $data['version'] = '1';
                        $data['entity'] = $entity_det_id;
                        $data['shot_det_id'] = $shot_det_id;
                        $data['message'] = 'Successfully Created!';
                    }
                }
                echo json_encode($data);
            } else if ($shot_det_id > 0) {
               // echo $shot_status_id;
                $shotdetails_update = "UPDATE shot_details SET shotallocationprojectname='$projectname',shotallocationprojectdetailsid='$project',shotcode='$shotcode',receiveddate='$receiveddate',shotallocationdepartmentid='$department',input_path = '$input_path',status='$status', modified_by='$username',modified_date='$date' WHERE id='$shot_det_id'";
                $r = $this->mysqli->query($shotdetails_update);
                //print($shotdetails_update);
                $shot_dept_details_id = array();
                $shot_dept_details_id = $this->getShotDeptDetailsIdFromShotDetailsId($shot_det_id);
                $ids = join("','", $shot_dept_details_id);
                // print("shot_dept_details_id".$shot_dept_details_id);
                $shot_dept_details_check = "select dept_id, id from shot_dept_details where id in ('$ids')";
               // print("shot_dept_details_check" . $shot_dept_details_check);
                $departmentList = $this->mysqli->query($shot_dept_details_check);
                $deptSelectedValueArray = array();
                // print("sizeof($departmentList)".sizeof($departmentList));
                
                // If department removed from UI, the existing saved department will be removed from table
                // if($departmentList->num_rows > sizeof($departmentArray)){
                while ($row3V = $departmentList->fetch_assoc()) {                    
                    $deptSelectedValue = $row3V["dept_id"];
                    $deptIdValue = $row3V["id"];
                   // print("row3V" . $deptSelectedValue);
                    array_push($deptSelectedValueArray, $deptSelectedValue);
                    $departmentCheckFlag = false;
                    foreach ($departmentArray as $departmenttemp) {
                        //print("departmenttemp" . $departmenttemp);
                        if ($departmenttemp == $deptSelectedValue) {
                            $departmentCheckFlag = true;
                        }
                    }
                    if (! $departmentCheckFlag) {
                        $deleteQuery = "delete from shot_dept_details where id = '$deptIdValue'";
                        // print("deleteQuery".$deleteQuery);
                        $this->mysqli->query($deleteQuery);
                    }
                }
                // }
                
                // If department added from UI
                // if($departmentList->num_rows < sizeof($departmentArray)){
                
                foreach ($departmentArray as $departmenttemp) {
                    // print("departmenttemp".$departmenttemp);
                    $departmentCheckFlag = false;
                    foreach ($deptSelectedValueArray as $deptValue) {
                        if ($departmenttemp == $deptValue) {
                            $departmentCheckFlag = true;
                        }
                    }
                    if (! $departmentCheckFlag) {
                        $shot_dept_identify_id = $this->getShotDeptIdentifyId();
                        $insertQuery = "INSERT INTO shot_dept_details (identify_id, shot_det_id, dept_id, created_by, status, version) values ('$shot_dept_identify_id','$shot_det_id', '$departmenttemp', '$username', '0', '1')";
                        $this->mysqli->query($insertQuery);
                    }
                }
                // }
                if ($r) {
                    $data['success'] = true;
                    $data['version'] = '1';
                    $data['entity'] = $entityid;
                    $data['editshotallocationid'] = $shot_det_id;
                    $data['message'] = 'Successfully Updated!';
                    $this->response(json_encode($data), 200);
                }
            }
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }

    function addShotDept()
    {
        $project = $_POST['project'];
        $projectname = $_POST['projectname'];
        $shotcode = $_POST['shotcode'];
        $framesgiven = $_POST['framesgiven'];
        $workrange = $_POST['workrange'];
        $complexityid = $_POST['complexityid'];
        $shot_det_id = $_POST['shot_det_id'];
        
        if (isset($_POST['clientmandays'])) {
            $clientmandays = $_POST['clientmandays'];
        } else {
            $clientmandays = NULL;
        }
        
        $internalmandays = $_POST['internalmandays'];
        $outsourcemandays = NULL;
        if (isset($_POST['outsourcemandays'])) {
            $outsourcemandays = $_POST['outsourcemandays'];
        }
        $clientprice = NULL;
        if (isset($_POST['clientprice'])) {
            $clientprice = $_POST['clientprice'];
        }
        
        if (isset($_POST['outsourceprice'])) {
            $outsourceprice = $_POST['outsourceprice'];
        } else {
            $outsourceprice = NULL;
        }
        $department = $_POST['department'];
        $client_target_date = $_POST['client_target_date'];
        $internal_target_date = $_POST['internal_target_date'];
        $version = $_POST['version'];
        // $shotstatusid=$_POST['shotstatusid'];
        $entityid = $_POST['entityid'];
        $editshotallocationid = $_POST['editshotallocid'];
        $username = $_COOKIE["username"];
        $date = date("Y-m-d H:i:s");
        $shot_dept_details_id = $_POST['shot_dept_details_id'];
        $shot_dept_details_identify_id = $_POST['shot_dept_details_identify_id'];
        $shotstatusid = $_POST['status'];        
        $shot_dept_details_max_id = null;
        
        $shot_dept_details_max_id = $this->getShotDeptDetailsIdFromIdentifyId($shot_dept_details_identify_id);
        
        $previousStatus = $this->getPreviousStatus($shot_dept_details_max_id);
        
        $existingShotDeptInfo = "select inhouse_id, vendor_id, freelancer_id from shot_dept_details where id = '$shot_dept_details_max_id'";
        $r = $this->mysqli->query($existingShotDeptInfo);
        $inhouse_id = null;
        $vendor_id = null;
        $freelancer_id = null;
        if ($r->num_rows > 0) {
            while($rowValue = $r->fetch_assoc()){
                $inhouse_id = $r["inhouse_id"];
                $vendor_id = $r["vendor_id"];
                $freelancer_id = $r["freelancer_id"];
            }
        }
        
        
        if ($shot_dept_details_max_id > 0 && (($shotstatusid == '19' && $previousStatus == 19) || ($shotstatusid != '19'))) {
            $shotdetails_update = "UPDATE shot_dept_details SET client_target_date='$client_target_date',internal_target_date='$internal_target_date',framesgiven='$framesgiven',complexityid='$complexityid',workrange='$workrange',clientmandays='$clientmandays',internalmandays='$internalmandays',outsourcemandays='$outsourcemandays',clientprice='$clientprice',outsourceprice='$outsourceprice',modified_by='$username',modified_date='$date', status = '$shotstatusid' WHERE id='$shot_dept_details_max_id'";
            // print($shotdetails_update);
            $r = $this->mysqli->query($shotdetails_update);
            
            if ($r) {
                $data['success'] = true;
                $data['version'] = $version;
                $data['entity'] = $editshotallocationid;
                $data['message'] = 'Successfully Updated!';
                $this->response(json_encode($data), 200);
            }
            echo json_encode($data);
        } else {            
            $version_id = $version + 1;
            $shotDetaisInsertQuery = "INSERT INTO shot_dept_details (shot_det_id,identify_id, dept_id,internal_target_date,framesgiven,complexityid,workrange,clientmandays,internalmandays,outsourcemandays,client_target_date,clientprice,version,status,inhouse_id, vendor_id, freelancer_id, created_by)
            VALUES ('$shot_det_id','$shot_dept_details_identify_id','$department','$internal_target_date','$framesgiven','$complexityid','$workrange','$clientmandays','$internalmandays','$outsourcemandays','$client_target_date','$clientprice','$version_id','$shotstatusid','$inhouse_id', '$vendor_id', '$freelancer_id','$username')";
            $shotdetails_insert = $this->mysqli->query($shotDetaisInsertQuery);
            $shot_dept_details_id = mysqli_insert_id($this->mysqli);          
            
            /* $shotworkdetailquery = "insert into shot_work_details (identify_id, shot_dept_details_identify_id, shot_dept_det_id, worker_team_type, worker_name,worker_id, planned_start_date,actual_start_date, planned_completed_date,
            actual_completed_date, qcperson, percentage, mid_path, worked_hours,
             allocated_hours, feedback_path, final_path, comments, status, version) values ('$shot_work_detail_identify_id', '$shot_dept_details_identify_id','$shot_dept_det_id','$worker_team_type', '$worker_name' ,'$worker_id','$planned_start_date', '$actual_start_date', '$planned_completed_date',
            '$actual_completed_date', '$qcperson', '$percentage', '$midpath', '$worked_hours', '$allocated_hours', '$feedback_path', '$final_path', '$comments', '$status','$latestVersion')";
            $r = $this->mysqli->query($shotworkdetailquery);
            $shot_work_detail_id = $shot_det_id = mysqli_insert_id($this->mysqli); */
            
            $getWorkdetailsQuery = "select identify_id, worker_id, worker_name, version, worker_team_type from shot_work_details where id in (select max(id) from shot_work_details where shot_dept_details_identify_id = '$shot_dept_details_identify_id' group by worker_id)";
            $getWorkdetails_rows = $this->mysqli->query($getWorkdetailsQuery);
            if($getWorkdetails_rows->num_rows > 0){
                while($workDetailsRow = $getWorkdetails_rows->fetch_assoc()){
                    $identify_id =$workDetailsRow["identify_id"] ;
                    $worker_id =$workDetailsRow["worker_id"] ;
                    $worker_name =$workDetailsRow["worker_name"] ;
                    $version =$workDetailsRow["version"] ;
                    $worker_team_type =$workDetailsRow["worker_team_type"] ;
                    $insertWorkDetails = "insert into shot_work_details (identify_id, shot_dept_details_identify_id, shot_dept_det_id, worker_name,worker_id, worker_team_type, version, status ) values ('$identify_id', '$shot_dept_details_identify_id','$shot_dept_details_id','$worker_name','$worker_id','$worker_team_type','$version', '$shotstatusid')";
                    $r = $this->mysqli->query($insertWorkDetails);
                    $shot_work_detail_id = mysqli_insert_id($this->mysqli);
                }
            }
            if ($shotdetails_insert) {
                $data['success'] = true;
                $data['shot_dept_details_id'] = $shot_dept_details_id;
                $data['shot_dept_details_identify_id'] = $shot_dept_details_identify_id;
                $data['version'] = $version_id;
                $data['message'] = 'Successfully Updated!';
                echo json_encode($data);
            }
        }
    }

    function addShotAlloc()
    {
        $framesgiven = $_POST['framesgiven'];
        $workrange = $_POST['workrange'];
        $complexityid = $_POST['complexityid'];
        $department = $_POST['department'];
        $entityid = $_POST['entityid'];
        $internalmandays = $_POST['internalmandays'];
        $editshotallocationid = $_POST['editshotallocid'];
        $username = $_COOKIE["username"];
        $version = $_POST['version'];
        $date = date("Y-m-d H:i:s");
        $shot_dept_details_id = $_POST['shot_dept_details_id'];
        $shot_dept_details_identify_id = $_POST['shot_dept_details_identify_id'];
        $status = $_POST['status'];
        $shotstatusid = $status;
        $shotallocationartistid = NULL;
        $shotallocationartistidupdate = NULL;
        if (isset($_POST['shotallocationartistid'])) {
            $shotallocationartistid = $_POST['shotallocationartistid'];
            $shotallocationartistidupdate = implode(',', $_POST['shotallocationartistid']);
        }
        $shotallocationvendorid = NULL;
        $shotallocationvendoridupdate = NULL;
        if (isset($_POST['shotallocationvendorid'])) {
            $shotallocationvendorid = $_POST['shotallocationvendorid'];
            $shotallocationvendoridupdate = implode(',', $_POST['shotallocationvendorid']);
        }
        $shotallocationfreelancerid = NULL;
        $shotallocationfreelanceridupdate = NULL;
        if (isset($_POST['shotallocationfreelancerid'])) {
            $shotallocationfreelancerid = $_POST['shotallocationfreelancerid'];
            $shotallocationfreelanceridupdate = implode(',', $_POST['shotallocationfreelancerid']);
        }
        
        $workerMap = array(
            "3" => $shotallocationartistid,
            "4" => $shotallocationvendorid,
            "17" => $shotallocationfreelancerid
        );
        $artiststridempV = json_encode($workerMap);
        // $shot_dept_details_id = $this->getShotDeptDetailsIdFromIdentifyId($shot_dept_details_identify_id);
        // $previousStatus = $this->getShotWorkDetailsStatusFromShotWorkdetailsId($shot_work_details_id);
        $previousStatus = $this->getShotDeptStatusFromId($shot_dept_details_id);
        // $shot_work_details_max_version = $this->getShotWorkDetailsMaxVersion($shot_work_details_id);
        if ($shot_dept_details_id > 0) {
            $shotdetails_update = "UPDATE shot_dept_details SET framesgiven='$framesgiven',complexityid='$complexityid',workrange='$workrange',modified_by='$username',modified_date='$date', status = '$status', internalmandays = '$internalmandays', inhouse_id='$shotallocationartistidupdate', vendor_id='$shotallocationvendoridupdate', freelancer_id='$shotallocationfreelanceridupdate' WHERE id='$shot_dept_details_id'";
            // print($shotdetails_update);
            $r = $this->mysqli->query($shotdetails_update);
            
            $verifyShotWorkerDetailsQuery = "SELECT id, worker_id, identify_id, worker_team_type, version FROM shot_work_details WHERE id in (SELECT max(id) from shot_work_details where  shot_dept_details_identify_id = '$shot_dept_details_identify_id' group by worker_id)";
            $verifyShotWorkerDetailsList = $this->mysqli->query($verifyShotWorkerDetailsQuery);
            
            $workerSelectedValueArray = array();
            $workerSelectedValueArray = $this->deleteWorkerDetails($workerMap, $verifyShotWorkerDetailsList, $workerSelectedValueArray);
           // print("workerSelectedValueArray" . sizeOf($workerSelectedValueArray));
            try {
                foreach ($workerMap as $key => $val) {
                    if (! empty($val)) {
                        foreach ($val as $shotallocationtempartistid) {
                            $workerCheckFlag = FALSE;
                            $workerID = null;
                            $workerTeamType = null;
                            $workerVersion = 1;
                            $workerIdentiyId = 0;
                            // print("workerSelectedValueArray".$workerSelectedValueArray);
                           // print("old workerCheckFlag" . $workerCheckFlag);
                            foreach ($workerSelectedValueArray as $key1 => $valueempV) {
                                $workerID = $valueempV["worker_id"];
                                $workerTeamType = $valueempV["worker_team_type"];                                
                                
                                if ($shotallocationtempartistid == $workerID && $key == $workerTeamType) {
                                    $workerCheckFlag = TRUE;
                                    $workerVersion = $valueempV["version"] + 1;
                                    $workerIdentiyId = $valueempV["identify_id"];
                                    break;
                                }
                            }
                            // print("workerCheckFlag".$workerCheckFlag);
                            // print("shotstatusid".$shotstatusid);
                            // print("previousStatus".$previousStatus);
                            if (! $workerCheckFlag || ($shotstatusid == '13' && $previousStatus != 13)) {
                                $artistNameRetrieveQuery = "SELECT fd.value FROM fields_details as fd , fieldsdet_entitydet_assoc as fea WHERE fea.entity_det_id='$shotallocationtempartistid' and fea.entity_id='$key' and (fd.field_name like 'artistname' OR fd.field_name like 'vendorname' OR fd.field_name like 'freelancername') and fd.id=fea.fields_det_id";
                                // print("artistNameRetrieveQuery size".$artistNameRetrieveQuery);
                                $artistNameList = $this->mysqli->query($artistNameRetrieveQuery);
                                $artistName = null;
                                // print("artistNameList size".$artistNameList->num_rows);
                                while ($artistNameRow = $artistNameList->fetch_assoc()) {
                                    $artistName = $artistNameRow["value"];
                                }
                                //print("artistName" . $artistName);
                                if($workerIdentiyId == 0){
                                    $workerIdentiyId = $this->getShotWorkDetailsIdentifyId();
                                }
                                $insertQuery = "insert into shot_work_details (identify_id,worker_id, worker_name, shot_dept_det_id,shot_dept_details_identify_id,worker_team_type, status, version) values ('$workerIdentiyId', '$shotallocationtempartistid', '$artistName', '$shot_dept_details_id','$shot_dept_details_identify_id','$key', '$status', '$workerVersion')";
                                // print("insertQuery".$insertQuery);
                                $this->mysqli->query($insertQuery);
                                // $shot_work_details_id = mysqli_insert_id($this->mysqli);
                            }
                        }
                    }
                }
            } catch (Exception $e) {
                print($e->getTrace());
            }
            
            if ($r) {
                $data['success'] = true;
                $data['version'] = $version;
                $data['entity'] = $editshotallocationid;
                $data['message'] = 'Successfully Updated!';
                $this->response(json_encode($data), 200);
            }
        }
    }

    /**
     *
     * @param
     *            workerMap
     * @param
     *            verifyShotWorkerDetailsList
     * @param
     *            workerSelectedValueArray
     * @param
     *            workerID
     * @param
     *            workerTeamType
     * @param
     *            shot_dept_details_temp_id
     * @param
     *            workerCheckFlag
     * @param
     *            workerCheckFlag
     * @param
     *            workerCheckFlag
     * @param
     *            workerCheckFlag
     * @param
     *            deleteQuery
     */
    private function deleteWorkerDetails($workerMap, $verifyShotWorkerDetailsList, $workerSelectedValueArray)
    {
        $workerCheckFlag = false;
        while ($row3V = $verifyShotWorkerDetailsList->fetch_assoc()) {
            $workerSelectedValueArray[] = $row3V;
            $workerID = $row3V["worker_id"];
            $workerTeamType = $row3V["worker_team_type"];
            $shot_dept_details_temp_id = $row3V["id"];
            $workerCheckFlag = false;
            foreach ($workerMap as $key => $val) {
                if (! empty($val)) {
                    foreach ($val as $shotallocationtempartistid) {
                        if ($shotallocationtempartistid == $workerID && $key == $workerTeamType) {
                            $workerCheckFlag = true;
                        }
                    }
                }
            }
            
            if (! $workerCheckFlag) {
                $deleteQuery = "delete from shot_work_details where id = '$shot_dept_details_temp_id'";
                // print("deleteQuery".$deleteQuery);
                $this->mysqli->query($deleteQuery);
            }
        }
        return $workerSelectedValueArray;
    }

    function retrieveWorkerOutputCapacity($workerId)
    {
        $retrieveWorkerOutputQuery = "SELECT fd.value FROM `fields_details` fd, fieldsdet_entitydet_assoc fea WHERE fea.entity_det_id = '$workerId' and field_name = 'artistartistoutput' and fea.fields_det_id = fd.id";
       // print('retrieveWorkerOutputQuery'.$retrieveWorkerOutputQuery);
        $r = $this->mysqli->query($retrieveWorkerOutputQuery);
        $workerOutputCapacity = 1;
        if ($r->num_rows > 0) {
            while ($row = $r->fetch_assoc()) {
                $workerOutputCapacity = $row['value'];
            }
        }
        return $workerOutputCapacity;
    }

    function addShotAllocWorkDetail()
    {
        $shot_work_detail_id = isset($_POST['shot_work_detail_id']) ? $_POST['shot_work_detail_id'] : null;
        $shot_work_detail_identify_id = isset($_POST['shot_work_detail_identify_id']) ? $_POST['shot_work_detail_identify_id'] : null;
        $planned_start_date = isset($_POST['planned_start_date']) ? $_POST['planned_start_date'] : null;
        $actual_start_date = isset($_POST['actual_start_date']) ? $_POST['actual_start_date'] : null;
        $planned_completed_date = isset($_POST['planned_completed_date']) ? $_POST['planned_completed_date'] : null;
        $actual_completed_date = isset($_POST['actual_completed_date']) ? $_POST['actual_completed_date'] : null;
        $qcperson = isset($_POST['qcperson']) ? $_POST['qcperson'] : null;
        $percentage = isset($_POST['percentage']) ? $_POST['percentage'] : null;
        $midpath = isset($_POST['mid_path']) ? $_POST['mid_path'] : null;
        $worked_hours = isset($_POST['worked_hours']) ? $_POST['worked_hours'] : null;
        $internalmandays = isset($_POST['internalmandays']) ? $_POST['internalmandays'] : null;
        $input_path = isset($_POST['input_path']) ? $_POST['input_path'] : null;
        $feedback_path = isset($_POST['feedback_path']) ? $_POST['feedback_path'] : null;
        $final_path = isset($_POST['final_path']) ? $_POST['final_path'] : null;
        $comments = isset($_POST['comments']) ? $_POST['comments'] : null;
        $worker_id = $_POST['worker_id'];
        $shot_dept_details_identify_id = isset($_POST['shot_dept_details_identify_id']) ? $_POST['shot_dept_details_identify_id'] : null;
        $shot_dept_det_id = isset($_POST['shot_dept_det_id']) ? $_POST['shot_dept_det_id'] : null;
        $worker_team_type = isset($_POST['worker_team_type']) ? $_POST['worker_team_type'] : null;
        $worker_name = isset($_POST['worker_name']) ? $_POST['worker_name'] : null;
        
        $workerOutput = $this->retrieveWorkerOutputCapacity($worker_id);
        $username = $_COOKIE["username"];
        
        $status = isset($_POST['status']) ? $_POST['status'] : null;
        $version = isset($_POST['version']) ? $_POST['version'] : null;
        $allocated_hours = 0;
        $previousStatus  = $this->getShotWorkDetailsStatusFromShotWorkdetailsId($shot_work_detail_identify_id);
        $latestVersion = $version + 1;
        
        if (isset($_POST['internalmandays']) && isset($_POST['percentage'])) {
            $allocated_hours = $internalmandays * 8 * ($percentage / 100) * 60 * (1 / $workerOutput);
        }
      //  print("status".$status);
       // print("previousStatus".$previousStatus);
        if(($status == '13' && $previousStatus != 13)){
            $shotworkdetailquery = "insert into shot_work_details (identify_id, shot_dept_details_identify_id, shot_dept_det_id, worker_team_type, worker_name,worker_id, planned_start_date,actual_start_date, planned_completed_date,
            actual_completed_date, qcperson, percentage, mid_path, worked_hours,
             allocated_hours, feedback_path, final_path, comments, status, version) values ('$shot_work_detail_identify_id', '$shot_dept_details_identify_id','$shot_dept_det_id','$worker_team_type', '$worker_name' ,'$worker_id','$planned_start_date', '$actual_start_date', '$planned_completed_date',
            '$actual_completed_date', '$qcperson', '$percentage', '$midpath', '$worked_hours', '$allocated_hours', '$feedback_path', '$final_path', '$comments', '$status','$latestVersion')";
            $r = $this->mysqli->query($shotworkdetailquery);
            $shot_work_detail_id = $shot_det_id = mysqli_insert_id($this->mysqli);
        }else {
            $shotworkdetailquery = "update shot_work_details set planned_start_date='$planned_start_date', actual_start_date = '$actual_start_date',planned_completed_date = '$planned_completed_date',
                actual_completed_date='$actual_completed_date', qcperson='$qcperson', percentage = '$percentage', mid_path='$midpath', worked_hours='$worked_hours', allocated_hours = '$allocated_hours', feedback_path = '$feedback_path', final_path = '$final_path',comments = '$comments',status = '$status' where id='$shot_work_detail_id'";
            $r = $this->mysqli->query($shotworkdetailquery);
        }
        
        // print("shotworkdetailquery".$shotworkdetailquery);
        
        if ($r) {
            $data['success'] = true;
            $data['message'] = 'Successfully Updated!';
            $data['shot_work_detail_id'] = $shot_work_detail_id;
            $this->response(json_encode($data), 200);
        }
    }

    private function getShotWorkDetailsIdFromShotDeptDetailsIdentifyIdQuery($shot_dept_details_identify_id)
    {
        $shot_dept_details_id = array();
        $getShotWorkDetailsIdFromShotDeptDetailsIdentifyIdQuery = "select max(id) from shot_work_details where shot_dept_details_identify_id in ('$shot_dept_details_identify_id') group by worker_id";
        $shot_work_details_id_row = $this->mysqli->query($getShotWorkDetailsIdFromShotDeptDetailsIdentifyIdQuery);
        while ($row3V = $shot_work_details_id_row->fetch_assoc()) {
            $shot_dept_details_id[] = $row3V;
        }
        return $shot_dept_details_id;
    }

    private function getShotWorkDetailsIdFromIdentifyId($shot_work_details_identify_id)
    {
        $shot_dept_details_id_query = "select max(id) from shot_dept_details where identify_id = '$shot_dept_details_identify_id' group by dept_id";
        $shot_dept_details_id_row = $this->mysqli->query($shot_dept_details_id_query);
        $shot_dept_details_id = '0';
        while ($row3V = $shot_dept_details_id_row->fetch_assoc()) {
            $shot_dept_details_id = $row3V["max(id)"];
        }
        return $shot_dept_details_id;
    }

    private function getShotWorkDetailsIdentifyId()
    {
        $shot_work_identify_id_query = "select max(identify_id) from shot_work_details";
        $shot_work_identify_id_row = $this->mysqli->query($shot_work_identify_id_query);
        $shot_work_identify_id = '0';
        while ($row3V = $shot_work_identify_id_row->fetch_assoc()) {
            $shot_work_identify_id = $row3V["max(identify_id)"];
        }
        //print("shot_work_identify_id" . $shot_work_identify_id);
        return $shot_work_identify_id + 1;
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

    private function getStatusId($status)
    {
        $shot_status_query = "(select id from status where status = '$status')";
        $shotstatusr = $this->mysqli->query($shot_status_query);
        if ($shotstatusr->num_rows > 0) {
            while ($shot_status_details = $shotstatusr->fetch_assoc()) {
                $shot_status_id = $shot_status_details["id"];
                return $shot_status_id;
            }
        }
    }

    private function saveOrSearchDashBoard()
    {
        //print('dashboardName'.saveOrSearchDashBoard);
       // print('dashboardName');
        $dashboardName = $_GET['dashboardName']; 
       // print('dashboardName'.$dashboardName);
        $dashBoardOperation = $_GET['dashBoardOperation']; 
        $dashboardRetrievalQuery = $_GET['dashboardRetrievalQuery']; 
       /// print('dashboardRetrievalQuery'.$dashboardRetrievalQuery);
        $retrieveLevel = $_GET['retrieveLevel'];
       // print('retrieveLevel'.$retrieveLevel);
        $username = $_COOKIE["username"];
       // print('username'.$username);
        $date = date("Y-m-d H:i:s");
        $queryAdditionArray = array("version", "status");      
        $shotLevelStatus = "";
        $shotLevelAddtion = "";
        $artistLevelAddtion = "";
        $tempArtistLevelAddition = "";
        $tempShotLevelAddition = "";
        $finalConditionQuery = "and sd.id = sdd.shot_det_id and sdd.dept_id = d.id and swd.shot_dept_det_id = sdd.id";
        $selectDataQuery = "select swd.id, swd.identify_id,sdd.shot_det_id, sdd.dept_id, d.dept, sdd.path, sdd.framesgiven, sdd.complexityid, sdd.workrange, sdd.clientmandays, sdd.internalmandays, sdd.outsourcemandays,sdd.clientprice, sdd.client_target_date, sdd.internal_target_date,sdd.inhouse_id, sdd.vendor_id, sdd.freelancer_id, sdd.version, swd.planned_start_date, swd.actual_start_date, swd.planned_completed_date, swd.actual_completed_date, swd.qcperson, swd.percentage, swd.worker_id, swd.worker_name,swd.worker_id, swd.shot_dept_details_identify_id, swd.shot_dept_det_id, swd.worker_team_type,sd.input_path, swd.mid_path,swd.feedback_path,swd.final_path,swd.comments, swd.allocated_hours,swd.worked_hours,swd.status from shot_details sd, shot_dept_details sdd, department d, shot_work_details swd where ";
        if($retrieveLevel === 'sdd') {
            $tempShotLevelAddition = " and tsd.id = tsdd.shot_det_id and tswd.shot_dept_det_id = tsdd.id group by tswd.shot_dept_details_identify_id)";
            $shotLevelAddtion = "sdd.id in (select max(tsdd.id) from shot_work_details tswd, shot_details tsd, shot_dept_details tsdd ";
        }else if($retrieveLevel === 'swd'){
            $tempArtistLevelAddition = " and tsd.id = tsdd.shot_det_id and tswd.shot_dept_det_id = tsdd.id group by tswd.worker_id)";
            $artistLevelAddtion = "swd.id in (select max(tswd.id) from shot_work_details tswd, shot_details tsd, shot_dept_details tsdd ";
        }
        foreach ($queryAdditionArray as $queryAdditionValue){
            if (strpos($dashboardRetrievalQuery, $queryAdditionValue) !== false) {
                $replaceQueryValue = $retrieveLevel.'.'.$queryAdditionValue;                
                str_replace($queryAdditionValue,$replaceQueryValue, $dashboardRetrievalQuery);     
              //  print("dashboardRetrievalQuery".$dashboardRetrievalQuery);
            } 
            
            if($queryAdditionValue === 'status'){
                $shotLevelStatus = ",status ts";
            }
        }
        $queryAdditionTemp = $shotLevelAddtion.$artistLevelAddtion.$shotLevelStatus." where ".$retrieveLevel.".".$dashboardRetrievalQuery.$tempShotLevelAddition.$tempArtistLevelAddition;
      //  print("queryAdditionTemp".$queryAdditionTemp);
        $queryFinalQuery = $selectDataQuery.$queryAdditionTemp.$finalConditionQuery;
       // print("queryFinalQuery".$queryFinalQuery);
        $r3V = $this->mysqli->query($queryFinalQuery) or die($this->mysqli->error.__LINE__);
        if($r3V->num_rows > 0)
        {
            if(strcasecmp($dashBoardOperation, "save") == 0){
                $dashBoardId = $this->save($dashboardName, $username, $date, $queryFinalQuery);
                if ($dashBoardId) {
                    $data['success'] = true;
                    $data['message'] = 'Dashboard Created Successfully!';               
                }else{
                    $data['failure'] = true;
                    $data['message'] = 'Dashboard is not Created Successfully!';    
                }
                return json_encode($data);
            }else {
               
                    $result3V = array();
                    while($row3V = $r3V->fetch_assoc())
                    {
                        $result3V[] = $row3V;
                    }
                    
                    $fielddetempV= json_encode($result3V);
                    
                    $dashboardDetails = json_decode($fielddetempV,true);
                    
                    $this->response($this->json($dashboardDetails), 200); // send user details
            }
        }else {
            $data['status'] = false;
            $data['message'] = 'Query Value is Empty!';    
            $this->response($this->json($data), 200);
        }       
    }
    /**
     * @param dashboardName
     * @param username
     * @param date
     * @param queryAdditionTemp
     */private function save($dashboardName, $username, $date, $queryFinalQuery)
    {
        $insertDashboardQuery = "insert into dashboard (name, query,created_by, created_date ) values ('$dashboardName', '$queryFinalQuery', '$username','$date')";
        $dashBoardId = $this->mysqli->query($insertDashboardQuery);
        return $dashBoardId;
    }


    /**
     */
    private function getPreviousStatus($shot_dept_details_max_id)
    {
        $getPreviousStatusQuery = "select status from shot_dept_details where id = '$shot_dept_details_max_id'";
        // print("getPreviousStatusQuery".$getPreviousStatusQuery);
        $getPreviousStatus = $this->mysqli->query($getPreviousStatusQuery);
        $previousStatus = null;
        while ($row3V = $getPreviousStatus->fetch_assoc()) {
            $previousStatus = $row3V["status"];
        }
        return $previousStatus;
    }

    private function getShotDeptDetailsIdFromShotDetailsId($shot_details_id)
    {
        $shot_dept_details_id_query = "select max(id) from shot_dept_details where shot_det_id in ('$shot_details_id') group by dept_id";
        $shot_dept_details_id_row = $this->mysqli->query($shot_dept_details_id_query);
        $shot_dept_details_id = array();
        while ($row3V = $shot_dept_details_id_row->fetch_assoc()) {
            $shot_dept_details_id[] = $row3V["max(id)"];
        }
        return $shot_dept_details_id;
    }

    private function getShotDeptDetailsIdFromIdentifyId($shot_dept_details_identify_id)
    {
        $shot_dept_details_id_query = "select max(id) from shot_dept_details where identify_id = '$shot_dept_details_identify_id' group by dept_id";
        $shot_dept_details_id_row = $this->mysqli->query($shot_dept_details_id_query);
        $shot_dept_details_id = '0';
        while ($row3V = $shot_dept_details_id_row->fetch_assoc()) {
            $shot_dept_details_id = $row3V["max(id)"];
        }
        return $shot_dept_details_id;
    }

    private function getShotDeptIdentifyId()
    {
        $shot_dept_identify_id_query = "select max(identify_id) from shot_dept_details";
        $shot_dept_identify_id_row = $this->mysqli->query($shot_dept_identify_id_query);
        $shot_dept_identify_id = '0';
        while ($row3V = $shot_dept_identify_id_row->fetch_assoc()) {
            $shot_dept_identify_id = $row3V["max(identify_id)"];
        }
        return $shot_dept_identify_id + 1;
    }

    private function getShotWorkDetailsMaxVersion($shot_work_details_id)
    {
       // print("shot_work_details_id in max version" . $shot_work_details_id);
        $getShotWorkDetailsMaxVersionQuery = "select version from shot_work_details where id = '$shot_work_details_id'";
      //  print("getShotWorkDetailsMaxVersionQuery" . $getShotWorkDetailsMaxVersionQuery);
        $getShotWorkDetailsMaxVersion_row = $this->mysqli->query($getShotWorkDetailsMaxVersionQuery);
        $shot_work_details_version = '0';
        while ($row3V = $getShotWorkDetailsMaxVersion_row->fetch_assoc()) {
            $shot_work_details_version = $row3V["version"];
        }
        return $shot_work_details_version;
    }

    private function getShotWorkDetailsStatusFromShotWorkdetailsId($shot_work_details_identify_id)
    {
       
        $getShotWorkDetailsStatusQuery = "select status from shot_work_details where id in (select max(id) from shot_work_details where identify_id='$shot_work_details_identify_id')";
      //  print("getShotWorkDetailsStatusQuery" . $getShotWorkDetailsStatusQuery);
        $getShotWorkDetailsStatus_row = $this->mysqli->query($getShotWorkDetailsStatusQuery);
        $shot_work_details_status = '0';
        while ($row3V = $getShotWorkDetailsStatus_row->fetch_assoc()) {
            $shot_work_details_status = $row3V["status"];
        }
        return $shot_work_details_status;
    }

    private function getShotDeptStatusFromId($shot_dept_details_id)
    {
        $shot_dept_details_status_query = "select status from shot_dept_details where id = '$shot_dept_details_id'";
        $shot_dept_details_status_row = $this->mysqli->query($shot_dept_details_status_query);
        $shot_dept_details_id = '0';
        while ($row3V = $shot_dept_details_status_row->fetch_assoc()) {
            $shot_dept_details_status = $row3V["status"];
        }
        return $shot_dept_details_status;
    }
}

// Initiiate Library

$api = new Process();
$api->functionconnect();
