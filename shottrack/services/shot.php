<?php
require_once ("Rest.inc.php");

class Shot extends REST
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
            case 'addshot':
                $this->addshot();
                break;
            case 'addShotDept':
                $this->addShotDept();
                break;
            case 'addShotAlloc':
                $this->addShotAlloc();
                break;
            
            case 'addShotAllocWorkDetail':
                $this->addShotAllocWorkDetail();
                break;
            
            case 'startShotWork':
                $this->startShotWork();
                break;
            
            case 'pauseorCompleteShotWork':
                $this->pauseorCompleteShotWork();
                break;
            case 'removeShot':
                $this->removeShot();
                break;
           
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
            $projectname = $_POST['projectdetailsid'];
            // print("deptarray". $_POST['department']);
            $departmentArray = $_POST['department'];
            $department = implode(',', $_POST['department']);
            $receiveddate = $_POST['receiveddate'];
            $input_path = $_POST['input_path'];
            $version = $_POST['version'];
            $entityid = $_POST['entityid'];
            $status = $_POST['status'];
            $shot_det_id = $_POST['shot_det_id'];
            $input_path = $_POST['input_path'];
            $username = $_COOKIE["userid"];
            $date = date("Y-m-d H:i:s");
            // print("project".$project);
            // print("username".$username);
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
                        $data['message'] = 'Record Saved Successfully';
                    }
                }
                echo json_encode($data);
            } else if ($shot_det_id > 0) {
                // echo $shot_status_id;
                $shotdetails_update = "UPDATE shot_details SET shotallocationprojectname='$projectname',shotallocationprojectdetailsid='$project',shotcode='$shotcode',receiveddate='$receiveddate',shotallocationdepartmentid='$department',input_path = '$input_path',status='$status', modified_by='$username',modified_date='$date' WHERE id='$shot_det_id'";
                $r = $this->mysqli->query($shotdetails_update);
                // print($shotdetails_update);
                $shot_dept_details_id = array();
                $shot_dept_details_identify_id = $this->getShotDeptDetailsIdFromShotDetailsId($shot_det_id);
                $ids = $shot_dept_details_identify_id;
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
                        // print("departmenttemp" . $departmenttemp);
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
                    $data['shot_det_id'] = $shot_det_id;
                    $data['message'] = 'Record Saved Successfully';
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
        $shot_det_id = $_POST['shot_det_id'];
        
        if (isset($_POST['clientmandays'])) {
            $clientmandays = $_POST['clientmandays'];
        } else {
            $clientmandays = NULL;
        }
        
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
        if (isset($_POST['clientfeedback'])) {
            $clientfeedback = $_POST['clientfeedback'];
        } else {
            $clientfeedback = NULL;
        }
        if (isset($_POST['clientFeedbackPath'])) {
            $clientFeedbackPath = $_POST['clientFeedbackPath'];
        } else {
            $clientFeedbackPath = NULL;
        }
        $department = $_POST['department'];
        $client_target_date = $_POST['clientdate'];
        
        $client_manday = $_POST['clientmandayshotdetailssupport'];
        
        $version = $_POST['version'];
        // $shotstatusid=$_POST['shotstatusid'];
        $entityid = $_POST['entityid'];
        $editshotallocationid = $_POST['editshotallocid'];
        $username = $_COOKIE["userid"];
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
            while ($rowValue = $r->fetch_assoc()) {
                $inhouse_id = $rowValue["inhouse_id"];
                $vendor_id = $rowValue["vendor_id"];
                $freelancer_id = $rowValue["freelancer_id"];
            }
        }
        
        if ($shot_dept_details_max_id > 0 && (($shotstatusid == '19' && $previousStatus == 19) || ($shotstatusid != '19'))) {
            $shotdetails_update = "UPDATE shot_dept_details SET client_target_date='$client_target_date',framesgiven='$framesgiven',workrange='$workrange',outsourcemandays='$outsourcemandays',
              clientmandays = '$client_manday', outsourceprice='$outsourceprice',modified_by='$username',modified_date='$date', status = '$shotstatusid' WHERE id='$shot_dept_details_max_id'";
            // print($shotdetails_update);
            $r = $this->mysqli->query($shotdetails_update);
            $this->addOrUpdateFeedback($clientfeedback, $clientFeedbackPath, $username, $date, $shot_dept_details_identify_id, 8);
            
            if ($r) {
                $data['success'] = true;
                $data['version'] = $version;
                $data['entity'] = $editshotallocationid;
                $data['message'] = 'Record Saved Successfully';
                $this->response(json_encode($data), 200);
            }
            echo json_encode($data);
        } else {
            $version_id = $version + 1;
            $shotDetaisInsertQuery = "INSERT INTO shot_dept_details (shot_det_id,identify_id, dept_id,framesgiven,workrange, outsourcemandays,client_target_date,version,status,inhouse_id, vendor_id, freelancer_id, clientmandays, created_by)
            VALUES ('$shot_det_id','$shot_dept_details_identify_id','$department','$framesgiven','$workrange','$outsourcemandays','$client_target_date','$version_id','$shotstatusid','$inhouse_id', '$vendor_id', '$freelancer_id','$username')";
            $shotdetails_insert = $this->mysqli->query($shotDetaisInsertQuery);
            $shot_dept_details_id = mysqli_insert_id($this->mysqli);
            $this->addOrUpdateFeedback($clientfeedback, $clientFeedbackPath, $username, $date, $shot_dept_details_identify_id, 8);
            /*
             * $shotworkdetailquery = "insert into shot_work_details (identify_id, shot_dept_details_identify_id, shot_dept_det_id, worker_team_type, worker_name,worker_id, planned_start_date,actual_start_date, planned_completed_date,
             * actual_completed_date, qcperson, percentage, mid_path, worked_hours,
             * allocated_hours, feedback_path, final_path, comments, status, version) values ('$shot_work_detail_identify_id', '$shot_dept_details_identify_id','$shot_dept_det_id','$worker_team_type', '$worker_name' ,'$worker_id','$planned_start_date', '$actual_start_date', '$planned_completed_date',
             * '$actual_completed_date', '$qcperson', '$percentage', '$midpath', '$worked_hours', '$allocated_hours', '$feedback_path', '$final_path', '$comments', '$status','$latestVersion')";
             * $r = $this->mysqli->query($shotworkdetailquery);
             * $shot_work_detail_id = $shot_det_id = mysqli_insert_id($this->mysqli);
             */
            
            $getWorkdetailsQuery = "select identify_id, worker_id, worker_name, version, worker_team_type from shot_work_details where id in (select max(id) from shot_work_details where shot_dept_details_identify_id = '$shot_dept_details_identify_id' group by worker_id)";
            $getWorkdetails_rows = $this->mysqli->query($getWorkdetailsQuery);
            if ($getWorkdetails_rows->num_rows > 0) {
                while ($workDetailsRow = $getWorkdetails_rows->fetch_assoc()) {
                    $identify_id = $workDetailsRow["identify_id"];
                    $worker_id = $workDetailsRow["worker_id"];
                    $worker_name = $workDetailsRow["worker_name"];
                    $version = $workDetailsRow["version"];
                    $worker_team_type = $workDetailsRow["worker_team_type"];
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
                $data['message'] = 'Record Saved Successfully';
                echo json_encode($data);
            }
        }
    }

    /**
     *
     * @param
     *            clientfeedback
     * @param
     *            clientFeedbackPath
     * @param
     *            username
     * @param
     *            date
     * @param
     *            shot_dept_details_identify_id
     */
    private function addOrUpdateFeedback($clientfeedback, $clientFeedbackPath, $username, $date, $shot_dept_details_identify_id, $entity_type)
    {
        $feedbackQuery = "insert into feedback (entity_type_id, entity_id, feedback, feedback_path, created_by, created_date) values 
                ($entity_type, $shot_dept_details_identify_id, '$clientfeedback', '$clientFeedbackPath', $username, '$date')";
    }

    function addShotAlloc()
    {
        $framesgiven = $_POST['framesgiven'];
        $workrange = $_POST['workrange'];
        $complexityid = $_POST['complexityshotdetailssupport'];
        $department = $_POST['department'];
        $internaldate = $_POST['internaldate'];
        $entityid = $_POST['entityid'];
        $internalmandays = $_POST['internalmandayshotdetailssupport'];
        $editshotallocationid = $_POST['editshotallocid'];
        $username = $_COOKIE["userid"];
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
             
        if(sizeof($shotallocationvendorid) > 0 && sizeof($shotallocationfreelancerid) > 0 ) {
            $shotAllocOutsource = array_merge($shotallocationvendorid, $shotallocationfreelancerid);
        } else if(sizeof($shotallocationvendorid)) {
            $shotAllocOutsource = $shotallocationvendorid;
        }else if(sizeof($shotallocationfreelancerid)) {
            $shotAllocOutsource = $shotallocationfreelancerid;
        }else {
            $shotAllocOutsource = array();
        }
        //print("shotAllocOutsource".$shotAllocOutsource);
        $workerMap = array(
            "3" => $shotallocationartistid,
            "4" => $shotAllocOutsource
        );
        $artiststridempV = json_encode($workerMap);
        // $shot_dept_details_id = $this->getShotDeptDetailsIdFromIdentifyId($shot_dept_details_identify_id);
        // $previousStatus = $this->getShotWorkDetailsStatusFromShotWorkdetailsId($shot_work_details_id);
        $previousStatus = $this->getShotDeptStatusFromId($shot_dept_details_id);
        // $shot_work_details_max_version = $this->getShotWorkDetailsMaxVersion($shot_work_details_id);
        if ($shot_dept_details_id > 0) {
            $shotdetails_update = "UPDATE shot_dept_details SET 
                    internal_target_date='$internaldate', complexityid='$complexityid',modified_by='$username',modified_date='$date', status = '$status', internalmandays = '$internalmandays', inhouse_id='$shotallocationartistidupdate', vendor_id='$shotallocationvendoridupdate', freelancer_id='$shotallocationfreelanceridupdate' WHERE id='$shot_dept_details_id'";
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
                                // print("artistName" . $artistName);
                                if ($workerIdentiyId == 0) {
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
                $data['message'] = 'Record Saved Successfully!';
                $this->response(json_encode($data), 200);
            }
        }
    }

    function addShotAllocWorkDetail()
    {
        $shot_work_detail_id = isset($_POST['shot_work_detail_id']) ? $_POST['shot_work_detail_id'] : null;
        $shot_work_detail_identify_id = isset($_POST['shot_work_detail_identify_id']) ? $_POST['shot_work_detail_identify_id'] : null;
        $planned_start_date = isset($_POST['plannedstartdate']) ? $_POST['plannedstartdate'] : null;
        $actual_start_date = isset($_POST['actualstartdate']) ? $_POST['actualstartdate'] : null;
        $planned_completed_date = isset($_POST['plannedenddate']) ? $_POST['plannedenddate'] : null;
        $actual_completed_date = isset($_POST['actualenddate']) ? $_POST['actualenddate'] : null;
        $qcperson = isset($_POST['qcperson']) ? $_POST['qcperson'] : null;
        $percentage = isset($_POST['percentage']) ? $_POST['percentage'] : null;
        $midpath = isset($_POST['midpath']) ? $_POST['midpath'] : null;
        $internalmandays = isset($_POST['internalmandays']) ? $_POST['internalmandays'] : null;
        $final_path = isset($_POST['finalpath']) ? $_POST['finalpath'] : null;
        $feedback_path = isset($_POST['feedback_path']) ? $_POST['feedback_path'] : null;
        $final_path = isset($_POST['final_path']) ? $_POST['final_path'] : null;
        $comments = isset($_POST['comments']) ? $_POST['comments'] : null;
        $worker_id = $_POST['worker_id'];
        $shot_dept_details_identify_id = isset($_POST['shot_dept_details_identify_id']) ? $_POST['shot_dept_details_identify_id'] : null;
        $shot_dept_det_id = isset($_POST['shot_dept_det_id']) ? $_POST['shot_dept_det_id'] : null;
        $worker_team_type = isset($_POST['worker_team_type']) ? $_POST['worker_team_type'] : null;
        $worker_name = isset($_POST['worker_name']) ? $_POST['worker_name'] : null;
        
        $workerOutput = $this->retrieveWorkerOutputCapacity($worker_id);
        $username = $_COOKIE["userid"];
        
        $status = isset($_POST['status']) ? $_POST['status'] : null;
        $version = isset($_POST['artistversion']) ? $_POST['artistversion'] : null;
        $allocated_hours = 0;
        $previousStatus = $this->getShotWorkDetailsStatusFromShotWorkdetailsId($shot_work_detail_identify_id);
        
        // print("internalmandays".$internalmandays);
         //print("percentage".$percentage);
       // print("workerOutput".$workerOutput);
        if (isset($_POST['internalmandays']) && isset($_POST['percentage'])) {
            if(($internalmandays != '' && $internalmandays > 0) && 
                ($percentage != '' && $percentage > 0) && 
                ($workerOutput != '' && $workerOutput > 0)) {
                $allocated_hours = $internalmandays * 8 * ($percentage / 100) * (1 / $workerOutput);
            }
        }
        // print("allocated_hours".$allocated_hours);
        // print("previousStatus".$previousStatus);
        if (($status == '13' && $previousStatus != 13)) {
            $latestVersion = (int) $version + 1;
            $shotworkdetailquery = "insert into shot_work_details (identify_id, shot_dept_details_identify_id, shot_dept_det_id, worker_team_type, worker_name,worker_id, planned_start_date,actual_start_date, planned_completed_date,
            actual_completed_date, qcperson, percentage, mid_path, 
             allocated_hours, feedback_path, final_path, comments, status, version) values ('$shot_work_detail_identify_id', '$shot_dept_details_identify_id','$shot_dept_det_id','$worker_team_type', '$worker_name' ,'$worker_id','$planned_start_date', '$actual_start_date', '$planned_completed_date',
            '$actual_completed_date', '$qcperson', '$percentage', '$midpath', '$allocated_hours', '$feedback_path', '$final_path', '$comments', '$status','$latestVersion')";
            //print("shotworkdetailquery" . $shotworkdetailquery);
            $r = $this->mysqli->query($shotworkdetailquery);
            $shot_work_detail_id = $shot_det_id = mysqli_insert_id($this->mysqli);
        } else {
            $shotworkdetailquery = "update shot_work_details set planned_start_date='$planned_start_date', actual_start_date = '$actual_start_date',planned_completed_date = '$planned_completed_date',
                actual_completed_date='$actual_completed_date', qcperson='$qcperson', percentage = '$percentage', mid_path='$midpath', allocated_hours = '$allocated_hours', feedback_path = '$feedback_path', final_path = '$final_path',comments = '$comments',status = '$status' where id='$shot_work_detail_id'";
            // print("shotworkdetailquery" . $shotworkdetailquery);
            $r = $this->mysqli->query($shotworkdetailquery);
        }
        
        // print("shotworkdetailquery".$shotworkdetailquery);
        
        if ($r) {
            $data['success'] = true;
            $data['message'] = 'Record Saved Successfully!';
            $data['shot_work_detail_id'] = $shot_work_detail_id;
            $this->response(json_encode($data), 200);
        }
    }

    private function startShotWork()
    {
        $shot_dept_artist_details_id = isset($_GET['shot_dept_artist_details_id']) ? $_GET['shot_dept_artist_details_id'] : null;
        
        $date = date("Y-m-d H:i:s");
        
        $actual_start_date = "0000-00-00 00:00:00.000000";
        $startDateQuery = "select actual_start_date from shot_work_details where id='$shot_dept_artist_details_id'";
        $startDateOutput = $this->mysqli->query($startDateQuery);
        if ($startDateOutput->num_rows > 0) {
            while ($row1 = $startDateOutput->fetch_assoc()) {
                $actual_start_date = $row1['actual_start_date'];
            }
        }
        // print("actual_start_date".$actual_start_date);
        $start_Time_Update_Query = "update shot_work_details set start_time='$date', pause_time=''";
        if ($actual_start_date == "0000-00-00 00:00:00.000000") {
            $start_Time_Update_Query .= ",actual_start_date = '$date'";
            $actual_start_date = $date;
        }
        
        $start_Time_Update_Query .= " where id = '$shot_dept_artist_details_id'";
         print("date".$start_Time_Update_Query);
        $r = $this->mysqli->query($start_Time_Update_Query);
        $success = array(
            'success' => "true",
            "message" => "Shot Worked Time Started Successfully",
            "actual_start_date" => "$actual_start_date"
        );
        $this->response($this->json($success), 200);
    }

    private function pauseorCompleteShotWork()
    {
        $shot_dept_artist_details_id = isset($_GET['shot_dept_artist_details_id']) ? $_GET['shot_dept_artist_details_id'] : null;
        $operationFlag = isset($_GET['operationFlag']) ? $_GET['operationFlag'] : null;
        $dateValue = date("Y-m-d H:i:s.u");
        $get_start_time_query = "select start_time,worked_hours  from shot_work_details where id='$shot_dept_artist_details_id'";
        
        $startOutput = $this->mysqli->query($get_start_time_query);
        $start_time = date_create("0000-00-00 00:00:00.000000");
        $worked_hours = "0";
        if ($startOutput->num_rows > 0) {
            while ($row1 = $startOutput->fetch_assoc()) {
                $start_time = $row1["start_time"];
                $worked_hours = $row1["worked_hours"];
            }
        } else {
            $success = array(
                'success' => "true",
                "message" => "Technical Error, Start Worked Time is Empty"
            );
            $this->response($this->json($success), 200);
        }
        
        $nInterval = (strtotime($dateValue) - strtotime($start_time)) / 60;
       // print("nInterval" . $nInterval);
        //print("worked_hours" . $worked_hours);
        $totalWorkedhours = $nInterval + $worked_hours;
        $actual_completed_date = "0000-00-00 00:00:00.000000";
        $worked_Time_Update_Query = "update shot_work_details set worked_hours = '$totalWorkedhours' ,start_time=''";
        if ($operationFlag == "pause") {
            $worked_Time_Update_Query .= ", pause_time='$dateValue'";
        } else if ($operationFlag == "complete") {
            $actual_completed_date = $dateValue;
            $worked_Time_Update_Query .= ", actual_completed_date='$dateValue', status='11'";
        }
        $worked_Time_Update_Query .= " where id='$shot_dept_artist_details_id'";
         print($worked_Time_Update_Query);
        $r = $this->mysqli->query($worked_Time_Update_Query);
        if ($operationFlag == "pause") {
            $success = array(
                'success' => "true",
                "message" => "Shot Paused Successfully",
                "actual_completed_date" => "$actual_completed_date"
            );
        } else {
            $success = array(
                'success' => "true",
                "message" => "Shot Moved to QC Successfully"
            );
        }
        $this->response($this->json($success), 200);
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
    private function getShotDeptDetailsIdFromShotDetailsId($shot_details_id)
    {
        $shot_dept_details_id_query = "select identify_id from shot_dept_details where shot_det_id = '$shot_details_id' group by dept_id";
        $shot_dept_details_id_row = $this->mysqli->query($shot_dept_details_id_query);
        $shot_dept_details_identify_id = '';
        while ($row3V = $shot_dept_details_id_row->fetch_assoc()) {
            $shot_dept_details_identify_id.= $row3V["identify_id"];
            $shot_dept_details_identify_id.=",";
        }
        $shot_dept_details_identify_id =  rtrim($shot_dept_details_identify_id,",");
        return $shot_dept_details_identify_id;
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

    private function getShotWorkDetailsIdentifyId()
    {
        $shot_work_identify_id_query = "select max(identify_id) from shot_work_details";
        $shot_work_identify_id_row = $this->mysqli->query($shot_work_identify_id_query);
        $shot_work_identify_id = '0';
        while ($row3V = $shot_work_identify_id_row->fetch_assoc()) {
            $shot_work_identify_id = $row3V["max(identify_id)"];
        }
        // print("shot_work_identify_id" . $shot_work_identify_id);
        return $shot_work_identify_id + 1;
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

    function retrieveWorkerOutputCapacity($workerId)
    {
        $retrieveWorkerOutputQuery = "SELECT output_manday FROM artist WHERE id=$workerId";
        // print('retrieveWorkerOutputQuery'.$retrieveWorkerOutputQuery);
        $r = $this->mysqli->query($retrieveWorkerOutputQuery);
        $workerOutputCapacity = 1;
        if ($r->num_rows > 0) {
            while ($row = $r->fetch_assoc()) {
                $workerOutputCapacity = $row['output_manday'];
            }
        }
        return $workerOutputCapacity;
    }

    private function getShotWorkDetailsStatusFromShotWorkdetailsId($shot_work_details_identify_id)
    {
        $getShotWorkDetailsStatusQuery = "select status from shot_work_details where id in (select max(id) from shot_work_details where identify_id='$shot_work_details_identify_id')";
        // print("getShotWorkDetailsStatusQuery" . $getShotWorkDetailsStatusQuery);
        $getShotWorkDetailsStatus_row = $this->mysqli->query($getShotWorkDetailsStatusQuery);
        $shot_work_details_status = '0';
        while ($row3V = $getShotWorkDetailsStatus_row->fetch_assoc()) {
            $shot_work_details_status = $row3V["status"];
        }
        return $shot_work_details_status;
    }
    
    
    function removeShot()
    {
        if(isset($_GET['shotId'])) {
            $shotId = $_GET['shotId'];
        }else {
            $shotId = null;
        }
        $shot_det_id_retrieveQuery = "select shot_det_id, identify_id from shot_dept_details where identify_id in ($shotId)";
        //print($removeShot_Dept_query);
        $r2 = $this->mysqli->query($shot_det_id_retrieveQuery);
        if ($r2->num_rows > 0) {
            while ($row =$r2->fetch_assoc()) {
                $shot_det_id = $row['shot_det_id'];
                $shot_det_id_count_query = "select count(shot_det_id) as number from shot_dept_details where shot_det_id in ($shot_det_id)";
                $r = $this->mysqli->query($shot_det_id_count_query);
                $res = mysqli_fetch_array($r);                
                if($res['number'] == 1) {
                    $removeShot_query = "delete FROM shot_details where id= '$shot_det_id'";
                    $this->mysqli->query($removeShot_query);
                }
            }
        }
        
        $removeShot_Dept_query = "delete FROM shot_dept_details where identify_id in ($shotId)";
        //print($removeShot_Dept_query);
        $r1 = $this->mysqli->query($removeShot_Dept_query);
        
       
        
        $remove_shot_Work_query = "delete FROM shot_work_details where shot_dept_details_identify_id in ($shotId)";
        //print($remove_shot_Work_query);
        $r = $this->mysqli->query($remove_shot_Work_query);
        if ($r) {
            $this->response(json_encode("success"), 200);
        }else {
            $this->response(json_encode("failure"), 200);
        }
    }
    

    private function json($data)
    {
        if (is_array($data)) {
            return json_encode($data, JSON_NUMERIC_CHECK);
        }
    }
}

// Initiiate Library

$api = new Shot();
$api->functionconnect();
