<?php
require_once ("Rest.inc.php");

class API extends REST
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
    public function processApi()
    {
        $func = strtolower(trim(str_replace("/", "", $_REQUEST['x'])));
        if ((int) method_exists($this, $func) > 0)
            $this->$func();
        else
            $this->response('', 404); // If the method not exist with in this class "Page not found".
    }

    /*
     * geting access specifiers
     */
    private function access()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $query = "SELECT id as userroleid,access as name FROM role";
        $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
        if ($r->num_rows > 0) {
            $result = array();
            while ($row = $r->fetch_assoc()) {
                $result[] = $row;
            }
            $this->response($this->json($result), 200); // send user details
        }
        $this->response('', 204); // If no records "No Content" status
    }

    /*
     * geting shotid for projects
     */
    private function getProjectShotid()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $id = (int) $this->_request['projID'];
        
        $query = "SELECT child_entity_det_id FROM entity_entity_assoc WHERE child_entity_id='$id' AND entity_det_id='7'";
        $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
        if ($r->num_rows > 0) {
            $strid = array();
            while ($row = mysqli_fetch_assoc($r)) {
                $strid[] = $row['child_entity_det_id'];
            }
            $jsonoutput = json_encode($strid);
            $empstrid = json_decode($jsonoutput, true);
            foreach ($empstrid as $value) {
                $string[] = $value;
            }
            $stringempid = implode(',', $string);
            $empids = str_replace(array(
                '(',
                ')'
            ), '', $stringempid);
            $empidsstring = '(' . $empids . ')';
            
            $queryfd = "SELECT fields_det_id FROM fieldsdet_entitydet_assoc  WHERE  entity_det_id IN $empidsstring ";
            $rfd = $this->mysqli->query($queryfd) or die($this->mysqli->error . __LINE__);
            if ($rfd->num_rows > 0) {
                $result = array();
                while ($rowfd = $rfd->fetch_assoc()) {
                    $resultfd[] = $rowfd['fields_det_id'];
                }
            }
            $jsonoutputfd = json_encode($resultfd);
            $empstrfd = json_decode($jsonoutputfd, true);
            foreach ($empstrfd as $value) {
                $stringfd[] = $value;
            }
            $stringempfd = implode(',', $stringfd);
            $empfd = str_replace(array(
                '(',
                ')'
            ), '', $stringempfd);
            $empidsstringfd = '(' . $empfd . ')';
            
            $query3 = "SELECT field_name,value FROM fields_details  WHERE  id IN $empidsstringfd AND (field_name LIKE '%shotallocationid%' OR field_name LIKE '%shotallocationshotcode%')";
            $r3 = $this->mysqli->query($query3) or die($this->mysqli->error . __LINE__);
            if ($r3->num_rows > 0) {
                $result = array();
                while ($row1 = $r3->fetch_assoc()) {
                    $result[] = $row1;
                }
            }
            $fielddetemp = json_encode($result);
            $empstridemp = json_decode($fielddetemp, true);
            $stringemp = array();
            $iCount = 1;
            $rCount = 0;
            foreach ($empstridemp as $key => $valueemp) {
                $stringemp[$rCount][$valueemp['field_name']] = $valueemp['value'];
                if ($iCount == 2) {
                    $iCount = 1;
                    $rCount ++;
                } else
                    $iCount ++;
            }
            $this->response($this->json($stringemp), 200); // send employee details
        }
        $this->response('', 204);
    }

    /*
     * function to get particular artist details
     */
    private function getDeptArtistid()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $departmentid = $this->_request['departmentid'];
        if ($departmentid == '1') {
            $deptname = "roto";
        } else if ($departmentid == '2') {
            $deptname = "paint";
        } else if ($departmentid == '3') {
            $deptname = "matchmove";
        } else if ($departmentid == '4') {
            $deptname = "comp";
        } else {
            $deptname = "3d";
        }
        
        /* query to get artist id who are all in this particular department */
        
        $query3 = "SELECT fea.entity_det_id as entity_det_id,fd.field_name,fd.value FROM fields_details as fd INNER JOIN fieldsdet_entitydet_assoc as fea ON fd.id=fea.fields_det_id WHERE 	fea.entity_id='3' AND fea.is_deleted=0  AND field_name LIKE '%artistdepartmentid%' AND  fd.value LIKE '%$departmentid%'";
        $r3 = $this->mysqli->query($query3) or die($this->mysqli->error . __LINE__);
        if ($r3->num_rows > 0) {
            $result = array();
            while ($row1 = $r3->fetch_assoc()) {
                $result[] = $row1['entity_det_id'];
            }
            $fielddetemp = json_encode($result);
            $empstridemp = json_decode($fielddetemp, true);
            foreach ($empstridemp as $value) {
                $stringfd[] = $value;
            }
            $stringempfd = implode(',', $stringfd);
            $empfd = str_replace(array(
                '(',
                ')'
            ), '', $stringempfd);
            $empidsstringfd = '(' . $empfd . ')';
            /* query to get artist details like name and id who are all in that particular department */
            $query4 = "SELECT fea.entity_det_id as entity_det_id,fd.field_name,fd.value FROM fields_details as fd INNER JOIN fieldsdet_entitydet_assoc as fea ON fd.id=fea.fields_det_id WHERE 	 fea.entity_id='3' AND fea.is_deleted=0 AND (field_name LIKE '%artistdepartmentid%' OR field_name LIKE '%artistname%'  OR field_name LIKE '%artistid%' )AND (field_name NOT LIKE '%editartistid%' ) AND fea.entity_det_id IN $empidsstringfd";
            $r4 = $this->mysqli->query($query4) or die($this->mysqli->error . __LINE__);
            if ($r4->num_rows > 0) {
                $result4 = array();
                while ($row4 = $r4->fetch_assoc()) {
                    $result4[] = $row4;
                }
            }
            $fielddetemp4 = json_encode($result4);
            $empstridemp4 = json_decode($fielddetemp4, true);
            $stringemp4 = array();
            $iCount = 1;
            $rCount = 0;
            foreach ($empstridemp4 as $key => $valueemp) {
                $stringemp4[$rCount]['shotallocation' . $deptname . $valueemp['field_name']] = $valueemp['value'];
                if ($iCount == 3) {
                    $iCount = 1;
                    $rCount ++;
                } else
                    $iCount ++;
            }
            $this->response($this->json($stringemp4), 200); // send employee details
        }
        $this->response('', 204);
    }

    private function getDeptArtistidTemp($departmentid, $deptname)
    {
        
        /* query to get artist details like name and id who are all in that particular department */
        $query4 = "SELECT id,name FROM artist WHERE departmentid like '%$departmentid%'";
        // print($query4);
        $r4 = $this->mysqli->query($query4) or die($this->mysqli->error . __LINE__);
        $result4 = array();
        if ($r4->num_rows > 0) {
            while ($row4 = $r4->fetch_assoc()) {
                $result4[] = $row4;
            }
        }
        
        return $result4;
    }

    /*
     * function to get particular vendor details
     */
    private function getDeptVendorid()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $departmentid = $this->_request['departmentid'];
        if ($departmentid == '1') {
            $deptname = "roto";
        } else if ($departmentid == '2') {
            $deptname = "paint";
        } else if ($departmentid == '3') {
            $deptname = "matchmove";
        } else if ($departmentid == '4') {
            $deptname = "comp";
        } else {
            $deptname = "3d";
        }
        
        $query3 = "SELECT fea.entity_det_id as entity_det_id,fd.field_name,fd.value FROM fields_details as fd INNER JOIN fieldsdet_entitydet_assoc as fea ON fd.id=fea.fields_det_id WHERE 	 fea.entity_id='4' AND fea.is_deleted=0 AND field_name LIKE '%vendordepartmentid%' AND fd.value LIKE  '%$departmentid%'";
        $r3 = $this->mysqli->query($query3) or die($this->mysqli->error . __LINE__);
        
        if ($r3->num_rows > 0) {
            $result = array();
            while ($row1 = $r3->fetch_assoc()) {
                $result[] = $row1['entity_det_id'];
            }
            
            $fielddetemp = json_encode($result);
            $empstridemp = json_decode($fielddetemp, true);
            foreach ($empstridemp as $value) {
                $stringfd[] = $value;
            }
            $stringempfd = implode(',', $stringfd);
            $empfd = str_replace(array(
                '(',
                ')'
            ), '', $stringempfd);
            $empidsstringfd = '(' . $empfd . ')';
            $query4 = "SELECT fea.entity_det_id as entity_det_id,fd.field_name,fd.value FROM fields_details as fd INNER JOIN fieldsdet_entitydet_assoc as fea ON fd.id=fea.fields_det_id WHERE 	 fea.entity_id='4' AND fea.is_deleted=0 AND (field_name LIKE '%vendordepartmentid%' OR field_name LIKE '%vendorname%'  OR field_name LIKE '%vendorid%' ) AND (field_name NOT LIKE '%editvendorid%' ) AND fea.entity_det_id IN $empidsstringfd";
            $r4 = $this->mysqli->query($query4) or die($this->mysqli->error . __LINE__);
            if ($r4->num_rows > 0) {
                $result4 = array();
                while ($row4 = $r4->fetch_assoc()) {
                    $result4[] = $row4;
                }
            }
            $fielddetemp4 = json_encode($result4);
            $empstridemp4 = json_decode($fielddetemp4, true);
            $stringemp4 = array();
            $iCount = 1;
            $rCount = 0;
            foreach ($empstridemp4 as $key => $valueemp) {
                $stringemp4[$rCount]['shotallocation' . $deptname . $valueemp['field_name']] = $valueemp['value'];
                if ($iCount == 3) {
                    $iCount = 1;
                    $rCount ++;
                } else
                    $iCount ++;
            }
            $this->response($this->json($stringemp4), 200); // send employee details
        }
        $this->response('', 204);
    }

    private function getDeptVendorFreelanceridTemp($departmentid, $type)
    {
        /* query to get artist details like name and id who are all in that particular department */
        $query4 = "SELECT id,name FROM vendor WHERE type= '$type' and department like '%$departmentid%'";
        // print($query4);
        $r4 = $this->mysqli->query($query4) or die($this->mysqli->error . __LINE__);
        $result4 = array();
        if ($r4->num_rows > 0) {
            while ($row4 = $r4->fetch_assoc()) {
                $result4[] = $row4;
            }
        }
        
        return $result4;
    }

    /*
     * function to get particular freelancer details
     */
    private function getDeptfreelancerid()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $departmentid = $this->_request['departmentid'];
        if ($departmentid == '1') {
            $deptname = "roto";
        } else if ($departmentid == '2') {
            $deptname = "paint";
        } else if ($departmentid == '3') {
            $deptname = "matchmove";
        } else if ($departmentid == '4') {
            $deptname = "comp";
        } else {
            $deptname = "3d";
        }
        $query3 = "SELECT fea.entity_det_id as entity_det_id,fd.field_name,fd.value FROM fields_details as fd INNER JOIN fieldsdet_entitydet_assoc as fea ON fd.id=fea.fields_det_id WHERE 	 fea.entity_id='17' AND fea.is_deleted=0 AND field_name LIKE '%freelancerdepartmentid%'  AND fd.value LIKE '%$departmentid%'";
        $r3 = $this->mysqli->query($query3) or die($this->mysqli->error . __LINE__);
        
        if ($r3->num_rows > 0) {
            $result = array();
            while ($row1 = $r3->fetch_assoc()) {
                $result[] = $row1['entity_det_id'];
            }
            
            $fielddetemp = json_encode($result);
            $empstridemp = json_decode($fielddetemp, true);
            foreach ($empstridemp as $value) {
                $stringfd[] = $value;
            }
            $stringempfd = implode(',', $stringfd);
            $empfd = str_replace(array(
                '(',
                ')'
            ), '', $stringempfd);
            $empidsstringfd = '(' . $empfd . ')';
            $query4 = "SELECT fea.entity_det_id as entity_det_id,fd.field_name,fd.value FROM fields_details as fd INNER JOIN fieldsdet_entitydet_assoc as fea ON fd.id=fea.fields_det_id WHERE 	 fea.entity_id='17' AND fea.is_deleted=0 AND (field_name LIKE '%freelancerdepartmentid%' OR field_name LIKE '%freelancername%'  OR field_name LIKE '%freelancerid%' ) AND (field_name NOT LIKE '%editfreelancerid%' ) AND fea.entity_det_id IN $empidsstringfd";
            $r4 = $this->mysqli->query($query4) or die($this->mysqli->error . __LINE__);
            if ($r4->num_rows > 0) {
                $result4 = array();
                while ($row4 = $r4->fetch_assoc()) {
                    $result4[] = $row4;
                }
            }
            $fielddetemp4 = json_encode($result4);
            $empstridemp4 = json_decode($fielddetemp4, true);
            $stringemp4 = array();
            $iCount = 1;
            $rCount = 0;
            foreach ($empstridemp4 as $key => $valueemp) {
                $stringemp4[$rCount]['shotallocation' . $deptname . $valueemp['field_name']] = $valueemp['value'];
                if ($iCount == 3) {
                    $iCount = 1;
                    $rCount ++;
                } else
                    $iCount ++;
            }
            $this->response($this->json($stringemp4), 200); // send employee details
        }
        $this->response('', 204);
    }

    /*
     * function to get particular freelancer details
     */
    private function getDeptfreelanceridTemp($departmentid, $deptname)
    {
        $query3 = "SELECT fea.entity_det_id as entity_det_id,fd.field_name,fd.value FROM fields_details as fd INNER JOIN fieldsdet_entitydet_assoc as fea ON fd.id=fea.fields_det_id WHERE 	 fea.entity_id='17' AND fea.is_deleted=0 AND field_name LIKE '%freelancerdepartmentid%'  AND fd.value LIKE '%$departmentid%'";
        $r3 = $this->mysqli->query($query3) or die($this->mysqli->error . __LINE__);
        
        if ($r3->num_rows > 0) {
            $result = array();
            while ($row1 = $r3->fetch_assoc()) {
                $result[] = $row1['entity_det_id'];
            }
            
            $fielddetemp = json_encode($result);
            $empstridemp = json_decode($fielddetemp, true);
            foreach ($empstridemp as $value) {
                $stringfd[] = $value;
            }
            $stringempfd = implode(',', $stringfd);
            $empfd = str_replace(array(
                '(',
                ')'
            ), '', $stringempfd);
            $empidsstringfd = '(' . $empfd . ')';
            $query4 = "SELECT fea.entity_det_id as entity_det_id,fd.field_name,fd.value FROM fields_details as fd INNER JOIN fieldsdet_entitydet_assoc as fea ON fd.id=fea.fields_det_id WHERE 	 fea.entity_id='17' AND fea.is_deleted=0 AND (field_name LIKE '%freelancerdepartmentid%' OR field_name LIKE '%freelancername%'  OR field_name LIKE '%freelancerid%' ) AND (field_name NOT LIKE '%editfreelancerid%' ) AND fea.entity_det_id IN $empidsstringfd";
            $r4 = $this->mysqli->query($query4) or die($this->mysqli->error . __LINE__);
            if ($r4->num_rows > 0) {
                $result4 = array();
                while ($row4 = $r4->fetch_assoc()) {
                    $result4[] = $row4;
                }
            }
            $fielddetemp4 = json_encode($result4);
            $empstridemp4 = json_decode($fielddetemp4, true);
            $stringemp4 = array();
            $iCount = 1;
            $rCount = 0;
            foreach ($empstridemp4 as $key => $valueemp) {
                $stringemp4[$rCount]['shotallocation' . $valueemp['field_name']] = $valueemp['value'];
                if ($iCount == 3) {
                    $iCount = 1;
                    $rCount ++;
                } else
                    $iCount ++;
            }
            return $stringemp4; // send employee details
        }
        return "";
    }

    /*
     * geting access specifiers
     */
    private function entity()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        if (isset($_GET['userId'])) {
            $userid = $_GET['userId'];
        } else {
            $userid = null;
        }
        if (isset($_GET['roleId'])) {
            $roleId = $_GET['roleId'];
        } else {
            $roleId = null;
        }
        
        $userEntityFlag = false;
        
        /* Entity Detail retrival in Entity Table Starts */
        $finalResults = array();
        $query = "SELECT id,name, title FROM entity_table WHERE is_deleted='0' AND access='1'";
        
        // print("EntityQuery---".$query);
        $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
        if ($r->num_rows > 0) {
            $entityResults = array();
            while ($row = $r->fetch_assoc()) {
                $entityResults[] = $row;
                $finalResults[] = $row;
            }
        }
        // print("finalResults count".count($finalResults));
        
        /* Entity Detail retrival in Entity Table Ends */
        
        /* User associated Entity Detail retrival Starts */
        $userEntityQuery = "select e.name, e.id, e.title from entity_table e,
            user_entity_field_assoc uefa where uefa.user_id = $userid and uefa.entity_id = e.id";
        // print("userEntityQuery---".$userEntityQuery);
        $userEntityQueryOutput = $this->mysqli->query($userEntityQuery) or die($this->mysqli->error . __LINE__);
        $userEntityQueryResults = array();
        if ($userEntityQueryOutput->num_rows > 0) {
            while ($row = $userEntityQueryOutput->fetch_assoc()) {
                $userEntityQueryResults[] = $row;
            }
        }
        /* User associated Entity Detail retrival Ends */
        
        /* User Entity Checked status Update Starts */
        if (! empty($entityResults) && ! empty($userEntityQueryResults)) {
            $userEntityFlag = true;
            unset($finalResults);
            $finalResults = array();
            foreach ($entityResults as $result) {
                $matchFlag = false;
                foreach ($userEntityQueryResults as $userEntityQueryResult) {
                    if ($result['name'] == $userEntityQueryResult['name']) {
                        $matchFlag = true;
                    }
                }
                if ($matchFlag) {
                    $result['checked'] = "checked";
                }
                $finalResults[] = $result;
            }
        }
        /* User Entity Checked status Update Ends */
        
        /* Role associated Entity Detail retrival Starts */
        $roleEntityQuery = "select e.name, e.id, e.title from entity_table e,
            role_entity_field_assoc refa where refa.role_id = $roleId and refa.entity_id = e.id";
        // print("roleEntityQuery---".$roleEntityQuery);
        $roleEntityQueryOutput = $this->mysqli->query($roleEntityQuery) or die($this->mysqli->error . __LINE__);
        $roleEntityQueryResults = array();
        if ($roleEntityQueryOutput->num_rows > 0) {
            while ($row = $roleEntityQueryOutput->fetch_assoc()) {
                $roleEntityQueryResults[] = $row;
            }
        }
        /* Role associated Entity Detail retrival Ends */
        
        /* Role Entity Checked status Update Starts */
        if (! $userEntityFlag && ! empty($entityResults) && ! empty($roleEntityQueryResults)) {
            // print(count($entityResults));
            unset($finalResults);
            $finalResults = array();
            foreach ($entityResults as $result) {
                $matchFlag = false;
                foreach ($roleEntityQueryResults as $roleEntityQueryResult) {
                    if ($result['name'] == $roleEntityQueryResult['name']) {
                        $matchFlag = true;
                    }
                }
                if ($matchFlag) {
                    $result['checked'] = "checked";
                }
                $finalResults[] = $result;
            }
        }
        /* User Entity Checked status Update Ends */
        
        $this->response($this->json($finalResults), 200);
        $this->response('', 204); // If no records "No Content" status
    }

    /*
     * geting entity id
     */
    private function getentityid()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $name = $this->_request['entityname'];
        $query = "SELECT id FROM entity_table WHERE is_deleted='0' AND name='$name'";
        $r = $this->mysqli->query($query);
        $res = mysqli_fetch_array($r);
        $id = $res['id'];
        if ($id) {
            echo $id;
        } else {
            echo "unauthorized";
        }
    }

    /*
     * geting get nuber of shots
     */
    private function getcountshots()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $projectid = $this->_request['projectiddd'];
        $query = "SELECT COUNT(*) as number FROM `shot_details` WHERE shotallocationprojectdetailsid='$projectid'";
        $r = $this->mysqli->query($query);
        $res = mysqli_fetch_array($r);
        $num = $res['number'];
        if ($num) {
            echo $num;
        } else {
            echo "0";
        }
    }

    /*
     * geting user acess
     */
    private function userentity()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $id = (int) $this->_request['userid'];
        
        $query = "SELECT entity_id FROM entity_user_assoc WHERE user_id='$id' AND is_deleted='0'";
        $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
        if ($r->num_rows > 0) {
            $result = array();
            while ($row = $r->fetch_assoc()) {
                $result[] = $row;
            }
            $this->response($this->json($result), 200);
        }
        $this->response('', 204); // If no records "No Content" status
    }

    /*
     * geting access details
     */
    private function getAccessDetails()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $id = (int) $this->_request['empID'];
        
        /* selctin accessid from loggedin userid */
        if ($id > 0) {
            $selectuser = "SELECT  entity_id FROM entity_user_assoc where user_id=$id AND is_deleted='0'";
            $selectuserquery = $this->mysqli->query($selectuser);
            if ($selectuserquery->num_rows > 0) {
                $struserid = array();
                while ($userrow = mysqli_fetch_assoc($selectuserquery)) {
                    $struserid[] = $userrow['entity_id'];
                }
                $jsonoutputuser = json_encode($struserid);
                $userstrid = json_decode($jsonoutputuser, true);
                foreach ($userstrid as $valueuser) {
                    $stringuser[] = $valueuser;
                }
                $stringuserid1 = implode(',', $stringuser);
                $userids1 = str_replace(array(
                    '(',
                    ')'
                ), '', $stringuserid1);
                $entityuserid = '(' . $userids1 . ')';
                $queryuser2 = "SELECT id,name,title FROM entity_table WHERE id IN  $entityuserid ORDER BY orderby ASC ";
                $userr2 = $this->mysqli->query($queryuser2);
                if ($userr2) {
                    $result2 = array();
                    while ($rowuser2 = $userr2->fetch_assoc()) {
                        $result2[] = $rowuser2;
                    }
                    $this->response($this->json($result2), 200);
                }
            }
        } else {
            $this->response('', 204);
        }
    }

    /*
     * assignstatus
     */
    private function assignstatus()
    {
        $query = "SELECT shotallocationrotoartistid,actualworkstartdateroto,actual_comp_time FROM shot_details WHERE multiid >0";
        $r = $this->mysqli->query($query);
        if ($r2->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($r)) {
                $rows[] = $row;
            }
            foreach ($rows as $row) {
                $artistroto = $row['shotallocationrotoartistid'];
                $startroto = $row['actualworkstartdateroto'];
                $completeroto = $row['actualcompletedtimeroto'];
                $updateassignstatus = $this->mysqli->query("UPDATE fieldsdet_entitydet_assoc SET  assign_status = '1'  WHERE entity_det_id = '$artistroto' AND
							  NOW() > '$startroto' AND NOW() < '$completeroto'");
                $updateassignstatus1 = $this->mysqli->query("UPDATE fieldsdet_entitydet_assoc SET  assign_status = '0'  WHERE entity_det_id = '$artistroto' AND
							  NOW() > '$startroto' AND NOW() > '$completeroto'");
            }
        }
    }

    private function assignstatusroto()
    {
        $query = "SELECT shotallocationrotoartistid,actualworkstartdateroto,actual_comp_time FROM shot_details WHERE multiid >0";
        $r = $this->mysqli->query($query);
        while ($row = mysqli_fetch_assoc($r)) {
            $rows[] = $row;
        }
        foreach ($rows as $row) {
            $artistroto = $row['shotallocationrotoartistid'];
            $startroto = $row['actualworkstartdateroto'];
            $completeroto = $row['actualcompletedtimeroto'];
            $updateassignstatus = $this->mysqli->query("UPDATE fieldsdet_entitydet_assoc SET  assign_status = '1'  WHERE entity_det_id = '$artistroto' AND
							  NOW() > '$startroto' AND NOW() < '$completeroto'");
            $updateassignstatus1 = $this->mysqli->query("UPDATE fieldsdet_entitydet_assoc SET  assign_status = '0'  WHERE entity_det_id = '$artistroto' AND
							  NOW() > '$startroto' AND NOW() > '$completeroto'");
        }
    }

    private function assignstatuspaint()
    {
        $query = "SELECT shotallocationpaintartistid,actualworkstartdatepaint,actualcompletedtimepaint FROM shot_details WHERE multiid >0";
        $r = $this->mysqli->query($query);
        while ($row = mysqli_fetch_assoc($r)) {
            $rows[] = $row;
        }
        foreach ($rows as $row) {
            $artistpaint = $row['shotallocationpaintartistid'];
            $workstartdatepaint = $row['actualworkstartdatepaint'];
            $completedtimepaint = $row['actualcompletedtimepaint'];
            $updateassignstatus = $this->mysqli->query("UPDATE fieldsdet_entitydet_assoc SET  assign_status = '1'  WHERE entity_det_id = '$artistpaint' AND
							  NOW() > '$workstartdatepaint' AND NOW() < '$completedtimepaint'");
            $updateassignstatus1 = $this->mysqli->query("UPDATE fieldsdet_entitydet_assoc SET  assign_status = '0'  WHERE entity_det_id = '$artistpaint' AND
							  NOW() > '$workstartdatepaint' AND NOW() > '$completedtimepaint'");
        }
    }

    private function assignstatusmatchmove()
    {
        $query = "SELECT  	shotallocationmatchmoveartistid,actualworkstartdatematch,actualcompletedtimematch FROM shot_details WHERE multiid >0";
        $r = $this->mysqli->query($query);
        while ($row = mysqli_fetch_assoc($r)) {
            $rows[] = $row;
        }
        foreach ($rows as $row) {
            $artistm = $row['shotallocationmatchmoveartistid'];
            $workstartdatematch = $row['actualworkstartdatematch'];
            $targettimematch = $row['actualtargettimematch'];
            $updateassignstatus = $this->mysqli->query("UPDATE fieldsdet_entitydet_assoc SET  assign_status = '1'  WHERE entity_det_id = '$artistm' AND
							  NOW() > '$workstartdatematch' AND NOW() < '$targettimematch'");
            $updateassignstatus1 = $this->mysqli->query("UPDATE fieldsdet_entitydet_assoc SET  assign_status = '0'  WHERE entity_det_id = '$artistm' AND
							  NOW() > '$workstartdatematch' AND NOW() > '$targettimematch'");
        }
    }

    private function assignstatuscomp()
    {
        $query = "SELECT shotallocationcompartistid, actualworkstartdatecomp,actualcompletedtimecomp FROM shot_details WHERE multiid >0";
        $r = $this->mysqli->query($query);
        while ($row = mysqli_fetch_assoc($r)) {
            $rows[] = $row;
        }
        foreach ($rows as $row) {
            $shotallocationcompartistid = $row['shotallocationcompartistid'];
            $workstartdatecomp = $row['actualworkstartdatecomp'];
            $completedtimecomp = $row['actualcompletedtimecomp'];
            $updateassignstatus = $this->mysqli->query("UPDATE fieldsdet_entitydet_assoc SET  assign_status = '1'  WHERE entity_det_id = '$shotallocationcompartistid' AND
							  NOW() > '$workstartdatecomp' AND NOW() < '$completedtimecomp'");
            $updateassignstatus1 = $this->mysqli->query("UPDATE fieldsdet_entitydet_assoc SET  assign_status = '0'  WHERE entity_det_id = '$shotallocationcompartistid' AND
							  NOW() > '$workstartdatecomp' AND NOW() > '$completedtimecomp'");
        }
    }

    private function assignstatus3d()
    {
        $query = "SELECT shotallocation3dartistid,actualworkstartdate3d,actualcompletedtime3d FROM shot_details WHERE multiid >0";
        $r = $this->mysqli->query($query);
        while ($row = mysqli_fetch_assoc($r)) {
            $rows[] = $row;
        }
        foreach ($rows as $row) {
            $shotallocation3dartistid = $row['shotallocation3dartistid'];
            $workstartdate3d = $row['actualworkstartdate3d'];
            $completedtime3d = $row['actualcompletedtime3d'];
            $updateassignstatus = $this->mysqli->query("UPDATE fieldsdet_entitydet_assoc SET  assign_status = '1'  WHERE entity_det_id = '$shotallocation3dartistid' AND
							  NOW() > '$workstartdate3d' AND NOW() < '$completedtime3d'");
            $updateassignstatus1 = $this->mysqli->query("UPDATE fieldsdet_entitydet_assoc SET  assign_status = '0'  WHERE entity_det_id = '$shotallocation3dartistid' AND
							  NOW() > '$workstartdate3d' AND NOW() > '$completedtime3d'");
        }
    }

    /*
     * check credentials to perform login
     */
    private function login()
    {
        $customer = json_decode(file_get_contents("php://input"), true);
        $username = $customer['username'];
        $password = $customer['password'];
        if (! empty($username) and ! empty($password)) {
            $query = "SELECT id,name,password,userroleid FROM users WHERE name = '$username' AND password = '$password'  AND is_deleted='0' LIMIT 1";
            $r = $this->mysqli->query($query);
            $res = mysqli_fetch_array($r);
            $id = $res['id'];
            $userroleid = $res['userroleid'];
            if ($id) {
                $data['success'] = true;
                $data['userroleid'] = $userroleid;
                $data['id'] = (int) $id;
                $this->response(json_encode($data), 200);
            } else {
                $data['success'] = false;
                $this->response(json_encode($data), 204);
            }
        }
    }

    /*
     * geting field details to construct form
     */
    private function getfields()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $entity = $this->_request['entity'];
        $userid = $_COOKIE['userid'];
        
        $query = "SELECT id FROM entity_table  WHERE name ='$entity' LIMIT 1";
        $r = $this->mysqli->query($query);
        $res = mysqli_fetch_array($r);
        $entityid = $res['id'];
        
        $selaccessquery = "SELECT userroleid FROM users  WHERE id =$userid";
        $racc2 = $this->mysqli->query($selaccessquery);
        $roleid2 = mysqli_fetch_array($racc2);
        $userroleid = $roleid2['userroleid'];
        
        $query2 = "SELECT fields_id  FROM fields_entity_assoc  WHERE entity_id = $entityid AND is_deleted='0' AND access LIKE '%$userroleid%'";
        $r2 = $this->mysqli->query($query2) or die($this->mysqli->error . __LINE__);
        
        if ($r2->num_rows > 0) {
            $strid2 = array();
            while ($row2 = mysqli_fetch_assoc($r2)) {
                $strid2[] = $row2['fields_id'];
            }
            $jsonoutput2 = json_encode($strid2);
            $empstrid2 = json_decode($jsonoutput2, true);
            foreach ($empstrid2 as $value2) {
                $string2[] = $value2;
            }
            $stringempid2 = implode(',', $string2);
            $empids2 = str_replace(array(
                '(',
                ')'
            ), '', $stringempid2);
            $fields = '(' . $empids2 . ')';
            
            $query3 = "SELECT * FROM fields  WHERE id IN $fields AND is_deleted = '0' ORDER BY orderby asc ";
            $r3 = $this->mysqli->query($query3) or die($this->mysqli->error . __LINE__);
            if ($r3->num_rows > 0) {
                $result = array();
                while ($row = $r3->fetch_assoc()) {
                    $result[] = $row;
                }
                
                $this->response($this->json($result), 200); // send user details
            }
        }
        
        $this->response('', 204);
    }

    /*
     * geting all user details
     */
    private function getuserrole()
    {
        $userid = $_COOKIE['userid'];
        $selaccessquery = "SELECT userroleid as userrole FROM users  WHERE id =$userid";
        $r3V = $this->mysqli->query($selaccessquery);
        if ($r3V->num_rows > 0) {
            $result3V = array();
            while ($row3V = $r3V->fetch_assoc()) {
                $result3V[] = $row3V;
            }
            // print($result3V);
            $this->response($this->json($result3V), 200); // send user details
        }
        $this->response('', 204); // If no records "No Content" status
    }

    // get the field associated with user and then role
    private function getUserRolefields()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $entity = $this->_request['entity'];
        $userid = $_COOKIE['userid'];
        $userRole = $_COOKIE["userRole"];
        
        $userFieldQuery = "select f.name, f.type, f.title, f.required, f.drop_down_ref, f.disabled from fields f,
         user_entity_field_assoc uefa where uefa.user_id = $userid and uefa.field_id = f.id";
        if ($entity != 0) {
            $userFieldQuery .= " and entity_id=$entity";
        }
        
        $userFieldQuery .= " order by f.orderby asc";
        // print("userFieldQuery".$userFieldQuery);
        $userFieldQueryRawOut = $this->mysqli->query($userFieldQuery) or die($this->mysqli->error . __LINE__);
        $result = array();
        if ($userFieldQueryRawOut->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($userFieldQueryRawOut)) {
                $result[] = $row;
            }
        } else {
            $roleFieldQuery = "select f.name, f.type, f.title, f.required, f.drop_down_ref, f.disabled from fields f,
             role_entity_field_assoc refa where refa.role_id = $userRole and refa.field_id = f.id";
            
            if ($entity != 0) {
                $roleFieldQuery .= " and entity_id=$entity";
            }
            $roleFieldQuery .= " order by f.orderby asc";
            // print("roleFieldQuery". $roleFieldQuery);
            $roleFieldQueryRawOut = $this->mysqli->query($roleFieldQuery) or die($this->mysqli->error . __LINE__);
            if ($roleFieldQueryRawOut->num_rows > 0) {
                while ($row = mysqli_fetch_assoc($roleFieldQueryRawOut)) {
                    $result[] = $row;
                }
            } else {
                // If user doesn't have user specific fields and role specific fields the fields will be retrieved from entity assoc table
                
                $entityFieldsQuery = "select f.name, f.id, f.title, f.required, f.drop_down_ref, f.disabled from fields_entity_assoc fea, fields f where fea.entity_id = $entity and fea.fields_id = f.id
            order by f.orderby";
                // print("entityFieldsQuery".$entityFieldsQuery);
                $entityFieldsRawOut = $this->mysqli->query($entityFieldsQuery) or die($this->mysqli->error . __LINE__);
                if ($entityFieldsRawOut->num_rows > 0) {
                    while ($row = mysqli_fetch_assoc($entityFieldsRawOut)) {
                        $result[] = $row;
                    }
                }
            }
        }
        $this->response($this->json($result), 200);
        $this->response('', 204);
    }

    private function getAllFields()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $entity = $this->_request['entity'];
        if (isset($_GET['userId'])) {
            $userid = $_GET['userId'];
        } else {
            $userid = null;
        }
        if (isset($_GET['roleId'])) {
            $roleId = $_GET['roleId'];
        } else {
            $roleId = null;
        }
        $userDataField = false;
        $finalResults = array();
        
        $entityFieldsQuery = "select f.name, f.id, f.title, f.required  from fields_entity_assoc fea, fields f where fea.entity_id = $entity and fea.fields_id = f.id
            order by f.orderby";
        // print($entityFieldsQuery);
        $entityFieldsRawOut = $this->mysqli->query($entityFieldsQuery) or die($this->mysqli->error . __LINE__);
        $entityFieldsresult = array();
        if ($entityFieldsRawOut->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($entityFieldsRawOut)) {
                
                // print($row['name']);
                $entityFieldsresult[] = $row;
                $finalResults[] = $row;
            }
        }
        $userFieldQuery = "select f.name, f.id, f.type, f.title, f.required from fields f,
         user_entity_field_assoc uefa where uefa.user_id = $userid and uefa.field_id = f.id and uefa.entity_id = $entity";
        if ($entity != 0) {
            $userFieldQuery .= " and entity_id=$entity";
        }
        
        // print("userFieldQuery".$userFieldQueryRawOut->num_rows);
        $userFieldQueryRawOut = $this->mysqli->query($userFieldQuery) or die($this->mysqli->error . __LINE__);
        $userFieldQueryResults = array();
        if ($userFieldQueryRawOut->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($userFieldQueryRawOut)) {
                $userFieldQueryResults[] = $row;
            }
        }
        
        // print(count($userFieldQueryResults));
        // print(count($entityFieldsresult));
        
        /* User Entity Checked status Update Starts */
        if (! empty($entityFieldsresult) && ! empty($userFieldQueryResults)) {
            //
            $userEntityFlag = true;
            unset($finalResults);
            $finalResults = array();
            foreach ($entityFieldsresult as $result) {
                $matchFlag = false;
                // print("Entiy Name--".$result['id']);
                foreach ($userFieldQueryResults as $userFieldQueryResult) {
                    
                    // print("UserField Name--".$userFieldQueryResult['id']);
                    if ($result['name'] == $userFieldQueryResult['name']) {
                        // print("UserField Name--".$userFieldQueryResult['id']);
                        $result['checked'] = "checked";
                        break;
                    }
                }
                $finalResults[] = $result;
            }
        } else {
            /* User Entity Checked status Update Ends */
            
            // print("User Out ".$userFieldOutput);
            $roleFieldQuery = "select f.name, f.type, f.title, f.required from fields f,
             role_entity_field_assoc refa where refa.role_id = $roleId and refa.field_id = f.id";
            if ($entity != 0) {
                $roleFieldQuery .= " and entity_id=$entity";
            }
            // print("roleFieldQuery". $roleFieldQuery);
            $roleFieldResults = array();
            $roleFieldQueryRawOut = $this->mysqli->query($roleFieldQuery) or die($this->mysqli->error . __LINE__);
            while ($row = mysqli_fetch_assoc($roleFieldQueryRawOut)) {
                $roleFieldResults[] = $row;
            }
            
            /* User Entity Checked status Update Starts */
            if (! empty($roleFieldResults)) {
                unset($finalResults);
                $finalResults = array();
                foreach ($entityFieldsresult as $result) {
                    $matchFlag = false;
                    foreach ($roleFieldResults as $roleFieldResult) {
                        if ($result['name'] == $roleFieldResult['name']) {
                            $matchFlag = true;
                        }
                    }
                    if ($matchFlag) {
                        $result['checked'] = "checked";
                    }
                    $finalResults[] = $result;
                }
            }
        }
        
        // print("ROle Out ".$roleFieldOutput);
        $this->response($this->json($finalResults), 200);
        $this->response('', 204);
    }

    function getdepartments()
    {
        $query3V = "select * from department";
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

    function getUserEntity()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $userid = $_COOKIE['userid'];
        $userRole = $_COOKIE["userRole"];
        
        $userEntityQuery = "select DISTINCT e.id, e.name from entity_table e,
         user_entity_field_assoc uefa where uefa.user_id = $userid and uefa.entity_id = e.id order by e.id asc";
        
        // print("userFieldQuery".$userFieldQuery);
        $userFieldQueryRawOut = $this->mysqli->query($userEntityQuery) or die($this->mysqli->error . __LINE__);
        $result = array();
        if ($userFieldQueryRawOut->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($userFieldQueryRawOut)) {
                $result[] = $row;
            }
        } else {
            $roleFieldQuery = "select DISTINCT e.id, e.name from entity_table e,
         role_entity_field_assoc refa where refa.role_id = $userRole and refa.entity_id = e.id order by e.id asc";
            // print("roleFieldQuery". $roleFieldQuery);
            $roleFieldQueryRawOut = $this->mysqli->query($roleFieldQuery) or die($this->mysqli->error . __LINE__);
            if ($roleFieldQueryRawOut->num_rows > 0) {
                while ($row = mysqli_fetch_assoc($roleFieldQueryRawOut)) {
                    $result[] = $row;
                }
            } else {
                // If user doesn't have user specific fields and role specific fields the fields will be retrieved from entity assoc table
                
                $entityFieldsQuery = "select id, name from entity_table";
                // print("entityFieldsQuery".$entityFieldsQuery);
                $entityFieldsRawOut = $this->mysqli->query($entityFieldsQuery) or die($this->mysqli->error . __LINE__);
                if ($entityFieldsRawOut->num_rows > 0) {
                    while ($row = mysqli_fetch_assoc($entityFieldsRawOut)) {
                        $result[] = $row;
                    }
                }
            }
        }
        $this->response($this->json($result), 200);
        $this->response('', 204);
    }

    /*
     * geting all user details
     */
    private function userdetails()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $id = (int) $this->_request['userid'];
        if ($id > 0) {
            $query = "SELECT * FROM users WHERE id='$id'";
            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            if ($r->num_rows > 0) {
                $result = array();
                while ($row = $r->fetch_assoc()) {
                    $result = $row;
                }
                $this->response($this->json($result), 200); // send user details
            }
        }
        $this->response('', 204); // If no records "No Content" status
    }

    /*
     * geting all country details
     */
    private function getCountryDetails()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $id = (int) $this->_request['regionid'];
        if ($id > 0) {
            $query = "SELECT id as clientcountryid,name FROM countries WHERE region_id='$id'";
            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            if ($r->num_rows > 0) {
                $result = array();
                while ($row = $r->fetch_assoc()) {
                    $result[] = $row;
                }
                $this->response($this->json($result), 200); // send user details
            }
        }
        $this->response('', 204); // If no records "No Content" status
    }

    /*
     * geting all currency details
     */
    private function getCurrencyList()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $result = array();
        $query = "SELECT id ,value FROM currencies";
        $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
        if ($r->num_rows > 0) {
            
            while ($row = $r->fetch_assoc()) {
                $result[] = $row;
            }
        }
        
        // print($result);
        $this->response($this->json($result), 200); // send user details
        $this->response('', 204); // If no records "No Content" status
    }

    /*
     * geting all artist details
     */
    private function empdetails()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $id = (int) $this->_request['empid'];
        if ($id > 0) {
            $query = "SELECT fields_det_id FROM fieldsdet_entitydet_assoc WHERE entity_det_id='$id'";
            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            $res = mysqli_fetch_array($r);
            $idfielddetid = $res['fields_det_id'];
            if ($idfielddetid) {
                $query3 = "SELECT fea.entity_det_id,fd.field_name,fd.value FROM fields_details as fd INNER JOIN fieldsdet_entitydet_assoc as fea ON fd.id=fea.fields_det_id WHERE fea.entity_det_id=$id ";
                $r3 = $this->mysqli->query($query3) or die($this->mysqli->error . __LINE__);
                if ($r3->num_rows > 0) {
                    $result3 = array();
                    while ($row3 = $r3->fetch_assoc()) {
                        $result3[] = $row3;
                    }
                }
                
                $fielddetemp = json_encode($result3);
                $artiststridemp = json_decode($fielddetemp, true);
                $stringemp = array();
                foreach ($artiststridemp as $key => $valueemp) {
                    $stringemp[$valueemp['field_name']] = $valueemp['value'];
                }
                
                $this->response($this->json($stringemp), 200); // send user details
            }
        }
        
        $this->response('', 204); // If no records "No Content" status
    }

    /*
     * geting particular shot details
     */
    private function shotdetails()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $id = (int) $this->_request['userid'];
        $version = (int) $this->_request['version'];
        if ($id > 0) {
            $query3 = "SELECT sd.id, sd.entity_id,  sd.shotallocationprojectdetailsid, sd.shotcode, sd.receiveddate,pd.name as projectname, sd.shotallocationdepartmentid, sd.version,sd.input_path, sd.client_feedback, sd.status from shot_details sd,project_details pd WHERE sd.id='$id' AND sd.version=1 AND sd.shotallocationprojectdetailsid!='0' AND sd.shotallocationprojectdetailsid=pd.id";
            $r3 = $this->mysqli->query($query3) or die($this->mysqli->error . __LINE__);
            if ($r3->num_rows > 0) {
                $result3 = array();
                while ($row3 = $r3->fetch_assoc()) {
                    $result3[] = $row3;
                }
            }
            $fielddetemp = json_encode($result3);
            $artiststridemp = json_decode($fielddetemp, true);
            $stringemp = array();
            foreach ($artiststridemp as $key => $valueemp) {
                $stringemp = $valueemp;
            }
            
            $this->response($this->json($stringemp), 200); // send user details
        }
        $this->response('', 204); // If no records "No Content" status
    }

    /*
     * geting particular shot details
     */
    private function getshotallocdetails()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $dept = (int) $this->_request['dept'];
        $shotallocationid = (int) $this->_request['shotallocationid'];
        $versionn = (int) $this->_request['versionn'];
        
        $query3V = "SELECT * from shot_details WHERE entity_id=$shotallocationid AND version=$versionn AND row_id='$dept' AND shotallocationdepartmentid='$dept'";
        $r3V = $this->mysqli->query($query3V) or die($this->mysqli->error . __LINE__);
        
        if ($r3V->num_rows > 0) {
            $result3V = array();
            while ($row3V = $r3V->fetch_assoc()) {
                $result3V[] = $row3V;
            }
            
            $fielddetempV = json_encode($result3V);
            $artiststridempV = json_decode($fielddetempV, true);
            $stringempV = array();
            foreach ($artiststridempV as $key => $valueempV) {
                $stringempV = $valueempV;
            }
            
            $this->response($this->json($stringempV), 200); // send user details
        }
        $this->response('', 204); // If no records "No Content" status
    }

    private function getShotDet()
    {
        // print("getShotDet called");
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        
        $shotallocationid = (int) $this->_request['shotallocationid'];
        // print('shotallocationid'.$shotallocationid);
        // $maxDepartmentId = "SELECT max(id) FROM `shot_dept_details` WHERE shot_det_id = '195' group by dept_id";
        $query3V = "select sdd.id, sdd.identify_id, sdd.shot_det_id,sd.input_path, sdd.dept_id, d.dept, sdd.path,sdd.framesgiven, sdd.complexityid as complexityshotdetailssupport, sdd.workrange, sdd.clientmandays as clientmandayshotdetailssupport, sdd.internalmandays as internalmandayshotdetailssupport, sdd.outsourcemandays,sdd.clientprice, sdd.client_target_date as clientdate, sdd.internal_target_date as internaldate,sdd.inhouse_id, sdd.vendor_id, sdd.freelancer_id, sdd.version,sdd.status from shot_details sd, shot_dept_details sdd, department d where sdd.id in (SELECT max(tsdd.id) FROM `shot_dept_details` tsdd, shot_details tsd WHERE tsd.id =  '$shotallocationid' and tsd.id = tsdd.shot_det_id group by tsdd.dept_id) and sdd.dept_id = d.id  and sd.id = sdd.shot_det_id";
        
        // print($query3V);
        $r3V = $this->mysqli->query($query3V) or die($this->mysqli->error . __LINE__);
        // print($r3V->num_rows);
        if ($r3V->num_rows > 0) {
            $result3V = array();
            while ($row3V = $r3V->fetch_assoc()) {
                $result3V[] = $row3V;
            }
            $finalResult = array();
            foreach ($result3V as $result) {
                $result["shotartistlist"] = $this->getDeptArtistidTemp($result["dept_id"], $result["dept"]);
                $result["shotvendorlist"] = $this->getDeptVendorFreelanceridTemp($result["dept_id"], 2);
                $result["shotfreelancerlist"] = $this->getDeptVendorFreelanceridTemp($result["dept_id"], 1);
                array_push($finalResult, $result);
            }
            $fielddetempV = json_encode($finalResult);
            /* $fielddetempV= json_encode($result3V); */
            
            $artiststridempV = json_decode($fielddetempV, true);
            $stringempV = array();
            foreach ($artiststridempV as $key => $valueempV) {
                $stringempV = $valueempV;
            }
            
            $this->response($this->json($artiststridempV), 200); // send user details
        } else if ($shotallocationid > 0) {
            print("shotallocationid" . $shotallocationid);
            $entityId = (int) $this->_request['shotallocationid'];
            $query3V = "SELECT distinct d.dept,sd.id,sd.version, sd.shotallocationdepartmentid,sd.input_path, sd.entity_id, sd.targetdate,sd.internaltargetdate from shot_details sd, department d WHERE d.id=sd.shotallocationdepartmentid and sd.id='$shotallocationid'";
            // print($query3V);
            $r3V = $this->mysqli->query($query3V) or die($this->mysqli->error . __LINE__);
            
            if ($r3V->num_rows > 0) {
                $result3V = array();
                $i = 0;
                while ($row3V = $r3V->fetch_assoc()) {
                    $result3V[$i] = $row3V['dept'];
                    $i ++;
                }
                
                $this->response($this->json($result3V), 200); // send user details
            }
        }
        
        $this->response('', 204); // If no records "No Content" status
    }

    private function getDashBoardDetails()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $dashboardname = (int) $this->_request['dashboardname'];
        // $dashboardRetrievalQuery = (int)$this->_request['dashboardRetrievalQuery'];
        $userid = (int) $this->_request['userid'];
        // $existDashboardName = "select query from dashboard where name = '$dashboardname'";
        // $existingQuery = $this->mysqli->query($existDashboardName) or die($this->mysqli->error.__LINE__);
        
        // print("userid".$userid);
        // if($query3V == null || $query3V == '') {
        $query3V = "select swd.id, sd.shotcode, d.dept, swd.identify_id, swd.shot_dept_details_identify_id, swd.shot_dept_det_id, swd.worker_team_type, swd.worker_name, sdd.framesgiven, sdd.complexityid, sdd.workrange, sdd.internalmandays, sdd.version shot_version, swd.version artist_version,s.status, swd.planned_start_date, swd.actual_start_date, swd.planned_completed_date, swd.actual_completed_date, swd.percentage, swd.allocated_hours, swd.worked_hours,swd.worker_id, swd.qcperson, sd.input_path, swd.mid_path,swd.feedback_path,swd.final_path,swd.comments, swd.start_time, swd.pause_time from shot_details sd, shot_dept_details sdd, department d, shot_work_details swd, status s where swd.id in (select max(tswd.id) from shot_work_details tswd, users u where swd.worker_id = u.artistid and u.id = '$userid' group by tswd.identify_id) and sd.id = sdd.shot_det_id and sdd.dept_id = d.id and s.id = swd.status and swd.shot_dept_det_id = sdd.id";
        /*
         * }else {
         *
         * }
         */
        // print("query3V".$query3V);
        $r3V = $this->mysqli->query($query3V) or die($this->mysqli->error . __LINE__);
        // print($r3V->num_rows);
        if ($r3V->num_rows > 0) {
            $result3V = array();
            while ($row3V = $r3V->fetch_assoc()) {
                $result3V[] = $row3V;
            }
            
            $fielddetempV = json_encode($result3V);
            
            $artiststridempV = json_decode($fielddetempV, true);
            
            $this->response($this->json($artiststridempV), 200); // send user details
        }
        
        $this->response('', 204); // If no records "No Content" status
    }

    private function getShotAllocWorkDetail()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('Not Get method', 406);
        }
        $shotallocationid = (int) $this->_request['shotallocationid'];
        
        // print("userid".$shotallocationid);
        $query3V = "select swd.id, swd.identify_id,sdd.shot_det_id, sdd.dept_id, d.dept, sdd.path, 
sdd.framesgiven, sdd.complexityid, sdd.workrange, sdd.clientmandays, 
sdd.internalmandays, sdd.outsourcemandays,sdd.clientprice, sdd.client_target_date,
 sdd.internal_target_date,sdd.inhouse_id, sdd.vendor_id, sdd.freelancer_id, swd.start_time, swd.pause_time,
 sdd.version shot_version, swd.version artistversion, swd.planned_start_date as plannedstartdate,
 swd.actual_start_date as actualstartdate, swd.planned_completed_date as plannedenddate, swd.actual_completed_date as actualenddate,
 swd.qcperson, swd.percentage, swd.worker_id, swd.worker_name,swd.worker_id,
 swd.shot_dept_details_identify_id, swd.shot_dept_det_id, swd.worker_team_type,sd.input_path as inputpath,
 swd.mid_path as midpath,swd.feedback_path,swd.final_path as finalpath,swd.comments, swd.allocated_hours as allocatedhours,swd.worked_hours as workedhours,
swd.status from shot_details sd, shot_dept_details sdd, department d, shot_work_details swd where swd.id in (select max(tswd.id) from shot_details tsd, shot_dept_details tsdd, shot_work_details tswd where tsd.id = '$shotallocationid' and tsd.id = tsdd.shot_det_id and tswd.shot_dept_det_id = tsdd.id group by tswd.shot_dept_details_identify_id, tswd.identify_id) and sd.id = sdd.shot_det_id and sdd.dept_id = d.id and swd.shot_dept_det_id = sdd.id";
        // print("query3V".$query3V);
        $r3V = $this->mysqli->query($query3V) or die($this->mysqli->error . __LINE__);
        // print($r3V->num_rows);
        if ($r3V->num_rows > 0) {
            $result3V = array();
            while ($row3V = $r3V->fetch_assoc()) {
                $workTeamType = $row3V['worker_team_type'];
                $workerId = $row3V['worker_id'];
                
                if (($workTeamType != null && $workTeamType != '') && ($workerId != null && $workerId != '')) {
                    $workerName = $this->getWorkerName($workTeamType, $workerId);
                    $row3V['worker_name'] = $workerName;
                }
                $result3V[] = $row3V;
            }
            
            /*
             * $fielddetempV = json_encode($result3V);
             *
             * $artiststridempV = json_decode($fielddetempV, true);
             */
            
            $this->response($this->json($result3V), 200); // send user details
        }
        
        $this->response('', 204); // If no records "No Content" status
    }

    /*
     * geting particular shot details
     */
    /*
     * private function gettimedetails()
     * {
     * if($this->get_request_method() != "GET"){
     * $this->response('',406);
     * }
     * $dept=(int)$this->_request['dept'];
     * $shotallocationid = (int)$this->_request['shotallocationid'];
     * $shotstatusid = (int)$this->_request['shstatusi'];
     * $versionn = (int)$this->_request['versionn'];
     * $selmaxid=$this->mysqli->query("select max(multiid) as maxmul from shot_details where entity_id='$shotallocationid' AND shotallocationdepartmentid='1' and version='$versionn' ");
     * $resmax = mysqli_fetch_array($selmaxid);
     * $maxid=$resmax['maxmul'];
     * for($i=1;$i<=$maxid;$i++)
     * {
     * $query3t="select max(case when multiid= '$i' AND entity_id='$shotallocationid' AND shotallocationdepartmentid='1' AND version='$versionn' then shotallocationrotoartistid end) shotallocationrotoartistid$i,
     * max(case when multiid= '$i' AND entity_id='$shotallocationid' AND shotallocationdepartmentid='1' AND version='$versionn' then workstartdateroto end) workstartdateroto$i,
     * max(case when multiid= '$i' AND entity_id='$shotallocationid' AND shotallocationdepartmentid='1' AND version='$versionn' then targettimeroto end) targettimeroto$i,
     * max(case when multiid= '$i' AND entity_id='$shotallocationid' AND shotallocationdepartmentid='1' AND version='$versionn' then completedtimeroto end) completedtimeroto$i
     * from shot_details ";
     * }
     * $r3t = $this->mysqli->query($query3t) or die($this->mysqli->error.__LINE__);
     *
     * if($r3t->num_rows > 0)
     * {
     * $result3t= array();
     * while($row3t = $r3t->fetch_assoc())
     * {
     * $result3t[] = $row3t;
     * }
     * }
     * $fielddetempt= json_encode($result3t);
     * $artiststridempt = json_decode($fielddetempt,true);
     * $stringempt=array();
     * foreach ($artiststridempt as $key=>$valueempt)
     * {
     * $stringempt = $valueempt;
     *
     *
     * }
     *
     * $this->response($this->json($stringempt), 200); // send user details
     *
     * $this->response('',204); // If no records "No Content" status
     * }
     */
    
    /*
     * geting particular time details
     */
    private function getWorkerName($workTeamType, $workerId)
    {
        $workerName = '';
        if ($workTeamType == 4) {
            $workerNameRetrieveQuery = "select name from vendor where id = '$workerId' ";
        } else {
            $workerNameRetrieveQuery = "select name from artist where id = '$workerId' ";
        }
        $r3t = $this->mysqli->query($workerNameRetrieveQuery) or die($this->mysqli->error . __LINE__);
        if ($r3t->num_rows > 0) {
            while ($row1t = $r3t->fetch_assoc()) {
                $workerName = $row1t['name'];
            }
        }
        // print("workerName ---- ".$workerName);
        return $workerName;
    }

    private function gettimedetails()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $dept = (int) $this->_request['dept'];
        $shotallocationid = (int) $this->_request['shotallocationid'];
        $shotstatusid = (int) $this->_request['shstatusi'];
        $versionn = (int) $this->_request['versionn'];
        $selm = "select * from shot_details where entity_id='$shotallocationid' AND shotallocationdepartmentid='$dept' and version='$versionn' and multiid > '0'  and shotallocationrotoartistid !='0'";
        $r3t = $this->mysqli->query($selm) or die($this->mysqli->error . __LINE__);
        if ($r3t->num_rows > 0) {
            $resultt = array();
            while ($row1t = $r3t->fetch_assoc()) {
                $result[] = $row1t;
            }
            
            $fielddetempt = json_encode($result);
            $empstridempt = json_decode($fielddetempt, true);
            $stringempt = array();
            foreach ($empstridempt as $key => $valueempt) {
                $stringemp[$valueempt['multiid']] = $valueempt;
            }
            $this->response($this->json($stringemp), 200); // send employee details
        }
        echo "notfound";
    }

    /*
     * geting particular time details vendor
     */
    private function gettimedetailsvendor()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $dept = (int) $this->_request['dept'];
        $shotallocationid = (int) $this->_request['shotallocationid'];
        $shotstatusid = (int) $this->_request['shstatusi'];
        $versionn = (int) $this->_request['versionn'];
        $selm = "select * from shot_details where entity_id='$shotallocationid' AND shotallocationdepartmentid='$dept' and version='$versionn' and multiid > '0' and shotallocationrotovendorid !='0'";
        $r3t = $this->mysqli->query($selm) or die($this->mysqli->error . __LINE__);
        if ($r3t->num_rows > 0) {
            $resultt = array();
            while ($row1t = $r3t->fetch_assoc()) {
                $result[] = $row1t;
            }
            
            $fielddetempt = json_encode($result);
            $empstridempt = json_decode($fielddetempt, true);
            $stringempt = array();
            foreach ($empstridempt as $key => $valueempt) {
                $stringemp[$valueempt['multiid']] = $valueempt;
            }
            $this->response($this->json($stringemp), 200); // send employee details
        }
        echo "notfound";
    }

    /*
     * geting particular time details vendor
     */
    private function gettimedetailsfreelancer()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $dept = (int) $this->_request['dept'];
        $shotallocationid = (int) $this->_request['shotallocationid'];
        $shotstatusid = (int) $this->_request['shstatusi'];
        $versionn = (int) $this->_request['versionn'];
        $selm = "select * from shot_details where entity_id='$shotallocationid' AND shotallocationdepartmentid='$dept' and version='$versionn' and multiid > '0' and shotallocationrotofreelancerid !='0'";
        $r3t = $this->mysqli->query($selm) or die($this->mysqli->error . __LINE__);
        if ($r3t->num_rows > 0) {
            $resultt = array();
            while ($row1t = $r3t->fetch_assoc()) {
                $result[] = $row1t;
            }
            
            $fielddetempt = json_encode($result);
            $empstridempt = json_decode($fielddetempt, true);
            $stringempt = array();
            foreach ($empstridempt as $key => $valueempt) {
                $stringemp[$valueempt['multiid']] = $valueempt;
            }
            $this->response($this->json($stringemp), 200); // send employee details
        }
        echo "notfound";
    }

    /*
     * geting particular shot details with statusid
     */
    private function shotdetailsshotstatusid()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $id = (int) $this->_request['shotallocid'];
        $version = (int) $this->_request['version'];
        if ($id > 0) {
            $query3s = "SELECT entity_id,shotallocationprojectdetailsid,shotcode,shotallocationdepartmentid,framesgiven,complexityid,workrange,priceclient,priceoutsource from shot_details WHERE entity_id=$id AND version=$version AND  	shotallocationprojectdetailsid != '0'";
            $r3s = $this->mysqli->query($query3s) or die($this->mysqli->error . __LINE__);
            if ($r3s->num_rows > 0) {
                $result3s = array();
                while ($row3s = $r3s->fetch_assoc()) {
                    $result3s[] = $row3s;
                }
            }
            $fielddetemps = json_encode($result3s);
            $artiststridemps = json_decode($fielddetemps, true);
            $stringemps = array();
            foreach ($artiststridemps as $key => $valueemps) {
                $stringemps = $valueemps;
            }
            
            $this->response($this->json($stringemps), 200); // send user details
        }
        $this->response('', 204); // If no records "No Content" status
    }

    private function getDept()
    {
        $entityId = (int) $this->_request['entityid'];
        $query3V = "SELECT distinct d.dept from shot_details sd, department d WHERE d.id=sd.shotallocationdepartmentid and entity_id=$entityId and row_id!='0'";
        $r3V = $this->mysqli->query($query3V) or die($this->mysqli->error . __LINE__);
        
        if ($r3V->num_rows > 0) {
            $result3V = array();
            $i = 0;
            while ($row3V = $r3V->fetch_assoc()) {
                $result3V[$i] = $row3V['dept'];
                $i ++;
            }
            // print("result3V[]".$result3V[0]."value 2".$result3V[1] );
            
            /*
             * $fielddetempV= json_encode($result3V);
             * $artiststridempV = json_decode($fielddetempV,true);
             * $stringempV=array();
             *
             * foreach ($artiststridempV as $valueempV)
             * {
             * print("valueempV---" .$valueempV);
             * $stringempV = $valueempV;
             *
             *
             * }
             */
            
            $this->response($this->json($result3V), 200); // send user details
        }
        $this->response('', 204); // If no records "No Content" status
    }

    private function getLeads()
    {
        $query3V = "select id, name from artist where role='6' and status='1'";
        $r3V = $this->mysqli->query($query3V) or die($this->mysqli->error . __LINE__);
        // print($r3V->num_rows);
        if ($r3V->num_rows > 0) {
            $result3V = array();
            while ($row3V = $r3V->fetch_assoc()) {
                $result3V[] = $row3V;
            }
            // print($result3V);
            $this->response($this->json($result3V), 200); // send user details
        }
        $this->response('', 204); // If no records "No Content" status
    }

    /* function calls to get supervisor names */
    function getSupervisors()
    {
        $query3V = "select id, name from artist where role = '5' and status='1'";
        $r3V = $this->mysqli->query($query3V) or die($this->mysqli->error . __LINE__);
        // print($r3V->num_rows);
        if ($r3V->num_rows > 0) {
            $result3V = array();
            while ($row3V = $r3V->fetch_assoc()) {
                $result3V[] = $row3V;
            }
            // print($result3V);
            $this->response($this->json($result3V), 200); // send user details
        }
        $this->response('', 204); // If no records "No Content" status
    }

    /* Function calls for get ProjectHead */
    function getProjectHead()
    {
        $query3V = "select id, name from artist where role='3' and status='1'";
        $r3V = $this->mysqli->query($query3V) or die($this->mysqli->error . __LINE__);
        // print($r3V->num_rows);
        if ($r3V->num_rows > 0) {
            $result3V = array();
            while ($row3V = $r3V->fetch_assoc()) {
                $result3V[] = $row3V;
            }
            // print($result3V);
            $this->response($this->json($result3V), 200); // send user details
        }
        $this->response('', 204); // If no records "No Content" status
    }

    /*
     * geting loggedin user name
     */
    private function getmaxshotstatusid()
    {
        $entityid = $this->_request['entityid'];
        $query = "SELECT max(shot_dept_status_id) as id FROM `shot_details` WHERE entity_id='$entityid'";
        $r = $this->mysqli->query($query);
        $res = mysqli_fetch_array($r);
        $id = $res['id'];
        echo $id;
    }

    /*
     * geting loggedin user name
     */
    private function getusername()
    {
        $id = (int) $this->_request['userid'];
        if ($id > 0) {
            $query = "SELECT name FROM users WHERE id='$id'";
            $r = $this->mysqli->query($query);
            $res = mysqli_fetch_array($r);
            $username = $res['name'];
            if ($id) {
                echo $username;
            }
        } else {
            echo "unauthorized";
        }
    }

    /*
     * function for forgot password
     */
    private function forgotpassword()
    {
        $usermail = $this->_request['usermail'];
        if ($usermail) {
            $query = "SELECT email FROM users WHERE email='$usermail'";
            $r = $this->mysqli->query($query);
            $res = mysqli_fetch_array($r);
            $email = $res['email'];
            if ($email) {
                echo $email;
            }
        } else {
            echo "unauthorized";
        }
    }

    /*
     * function to get particular user details
     */
    private function users()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $query = "SELECT distinct c.name,c.id,c.email FROM users c WHERE is_deleted='0' order by c.id desc";
        $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
        
        if ($r->num_rows > 0) {
            $result = array();
            while ($row = $r->fetch_assoc()) {
                $quiz[$row['id']] = array(
                    'username' => $row['name'],
                    'email' => array()
                );
                
                $result[] = $row;
            }
            
            $this->response($this->json($result), 200); // send user details
        }
        $this->response('', 204); // If no records "No Content" status
    }

    /*
     * function to get particular client details
     */
    private function clientdetails()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $clientid = (int) $this->_request['id'];
        $name = $this->_request['name'];
        $entityname = $this->_request['entityname'];
        $idname = $this->_request['idname'];
        $urlid = $this->_request['urlid'];
        if ($urlid <= 0) {
            $querye = "SELECT child_entity_det_id FROM entity_entity_assoc  WHERE  is_deleted='0'";
            $re = $this->mysqli->query($querye) or die($this->mysqli->error . __LINE__);
            if ($re->num_rows > 0) {
                $stride = array();
                while ($rowe = mysqli_fetch_assoc($re)) {
                    $stride[] = $rowe['child_entity_det_id'];
                }
                $jsonoutpute = json_encode($stride);
                $empstride = json_decode($jsonoutpute, true);
                foreach ($empstride as $valuee) {
                    $stringe[] = $valuee;
                }
                $stringempide = implode(',', $stringe);
                $empidse = str_replace(array(
                    '(',
                    ')'
                ), '', $stringempide);
                $empidsstringe = '(' . $empidse . ')';
            } else {
                $empidsstringe = "(' ')";
            }
            $query = "SELECT fields_det_id FROM fieldsdet_entitydet_assoc  WHERE entity_id='$clientid' AND is_deleted='0'";
            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            if ($r->num_rows > 0) {
                $strid = array();
                while ($row = mysqli_fetch_assoc($r)) {
                    $strid[] = $row['fields_det_id'];
                }
                $jsonoutput = json_encode($strid);
                $empstrid = json_decode($jsonoutput, true);
                foreach ($empstrid as $value) {
                    $string[] = $value;
                }
                $stringempid = implode(',', $string);
                $empids = str_replace(array(
                    '(',
                    ')'
                ), '', $stringempid);
                $empidsstring = '(' . $empids . ')';
                $query3 = "SELECT field_name,value FROM fields_details  WHERE  id IN $empidsstring AND (field_name LIKE '%$idname%' OR field_name LIKE '%$name%') AND (field_name NOT LIKE '%edit%')";
                $r3 = $this->mysqli->query($query3) or die($this->mysqli->error . __LINE__);
                if ($r3->num_rows > 0) {
                    $result = array();
                    while ($row1 = $r3->fetch_assoc()) {
                        $result[] = $row1;
                    }
                }
                $fielddetemp = json_encode($result);
                $empstridemp = json_decode($fielddetemp, true);
                
                $stringemp = array();
                $iCount = 1;
                $rCount = 0;
                foreach ($empstridemp as $key => $valueemp) {
                    $stringemp[$rCount][$entityname . $valueemp['field_name']] = $valueemp['value'];
                    if ($iCount == 2) {
                        $iCount = 1;
                        $rCount ++;
                    } else
                        $iCount ++;
                }
                $this->response($this->json($stringemp), 200); // send employee details
            }
            $this->response('', 204);
        } else {
            $query = "SELECT fields_det_id FROM fieldsdet_entitydet_assoc  WHERE entity_id='$clientid' AND is_deleted='0'";
            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            if ($r->num_rows > 0) {
                $strid = array();
                while ($row = mysqli_fetch_assoc($r)) {
                    $strid[] = $row['fields_det_id'];
                }
                $jsonoutput = json_encode($strid);
                $empstrid = json_decode($jsonoutput, true);
                foreach ($empstrid as $value) {
                    $string[] = $value;
                }
                $stringempid = implode(',', $string);
                $empids = str_replace(array(
                    '(',
                    ')'
                ), '', $stringempid);
                $empidsstring = '(' . $empids . ')';
                $query3 = "SELECT field_name,value FROM fields_details  WHERE  id IN $empidsstring AND (field_name LIKE '%$idname%' OR field_name LIKE '%$name%')AND (field_name NOT LIKE '%edit%')";
                $r3 = $this->mysqli->query($query3) or die($this->mysqli->error . __LINE__);
                if ($r3->num_rows > 0) {
                    $result = array();
                    while ($row1 = $r3->fetch_assoc()) {
                        $result[] = $row1;
                    }
                }
                $fielddetemp = json_encode($result);
                $empstridemp = json_decode($fielddetemp, true);
                
                $stringemp = array();
                $iCount = 1;
                $rCount = 0;
                foreach ($empstridemp as $key => $valueemp) {
                    $stringemp[$rCount][$entityname . $valueemp['field_name']] = $valueemp['value'];
                    if ($iCount == 2) {
                        $iCount = 1;
                        $rCount ++;
                    } else
                        $iCount ++;
                }
                $this->response($this->json($stringemp), 200); // send employee details
            }
            $this->response('', 204);
        }
    }

    /*
     * function to get particular client details
     */
    private function clientdetailsproj()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        
        $clientid = (int) $this->_request['id'];
        $name = $this->_request['name'];
        $entityname = $this->_request['entityname'];
        $idname = $this->_request['idname'];
        $urlid = $this->_request['urlid'];
        
        $query3 = "SELECT id,name FROM project_details WHERE status='1' order BY id ";
        $r3 = $this->mysqli->query($query3) or die($this->mysqli->error . __LINE__);
        if ($r3->num_rows > 0) {
            $result = array();
            while ($row1 = $r3->fetch_assoc()) {
                $result[] = $row1;
            }
            
            $fielddetemp = json_encode($result);
            $empstridemp = json_decode($fielddetemp, true);
            $stringemp = array();
            
            foreach ($empstridemp as $valueemp) {
                $stringemp[] = $valueemp;
            }
            $this->response($this->json($stringemp), 200); // send employee details
        }
        $this->response('', 204);
    }

    /*
     * function to get particular client details
     */
    private function clientdetailsprojdept()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        
        $deptid = (int) $this->_request['deptid'];
        $deptidd = $deptid + 1;
        
        $query2 = "select shotallocationprojectdetailsid from shot_details WHERE shotallocationdepartmentid like '%$deptidd%' AND is_deleted='0' AND   shotallocationprojectdetailsid !='0' GROUP BY shotallocationprojectdetailsid";
        $r2 = $this->mysqli->query($query2) or die($this->mysqli->error . __LINE__);
        if ($r2->num_rows > 0) {
            $result2 = array();
            while ($row2 = $r2->fetch_assoc()) {
                $result2[] = $row2;
            }
        }
        $fielddetemp2 = json_encode($result2);
        $empstridemp2 = json_decode($fielddetemp2, true);
        
        $stringemp2 = array();
        foreach ($empstridemp2 as $valueemp2) {
            $projid[] = $valueemp2['shotallocationprojectdetailsid'];
        }
        $idstr = implode(",", $projid);
        $resfields = '(' . $idstr . ')';
        
        $query3 = "SELECT entity_id as shotallocationprojectdetailsid,name  as shotallocationprojectdetailsname FROM project_details where is_deleted='0' AND entity_id IN $resfields GROUP by shotallocationprojectdetailsid";
        $r3 = $this->mysqli->query($query3) or die($this->mysqli->error . __LINE__);
        if ($r3->num_rows > 0) {
            $result = array();
            while ($row1 = $r3->fetch_assoc()) {
                $result[] = $row1;
            }
            
            $fielddetemp = json_encode($result);
            $empstridemp = json_decode($fielddetemp, true);
            $stringemp = array();
            
            foreach ($empstridemp as $valueemp) {
                $stringemp[] = $valueemp;
            }
            $this->response($this->json($stringemp), 200); // send employee details
        }
        $this->response('', 204);
    }

    /*
     * function to get all shot details
     */
    private function shotdetailsproj()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        
        $query3 = "SELECT entity_id as shotcode,shotcode as shotcodename FROM shot_details where is_deleted='0' GROUP by entity_id";
        $r3 = $this->mysqli->query($query3) or die($this->mysqli->error . __LINE__);
        if ($r3->num_rows > 0) {
            $result = array();
            while ($row1 = $r3->fetch_assoc()) {
                $result[] = $row1;
            }
            
            $fielddetemp = json_encode($result);
            $empstridemp = json_decode($fielddetemp, true);
            $stringemp = array();
            
            foreach ($empstridemp as $valueemp) {
                $stringemp[] = $valueemp;
            }
            $this->response($this->json($stringemp), 200); // send employee details
        }
        $this->response('', 204);
    }

    /*
     * function to get particular shot details
     */
    private function shotdetailsprojid()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        
        $projname = $this->_request['projname'];
        $query1 = "SELECT entity_id FROM project_details WHERE is_deleted='0' AND name='$projname'";
        $r1 = $this->mysqli->query($query1);
        $res1 = mysqli_fetch_array($r1);
        $id1 = $res1['entity_id'];
        
        $query3 = "SELECT entity_id as shotcode,shotcode as shotcodename FROM shot_details where is_deleted='0' AND shotallocationprojectdetailsid = '$id1' GROUP by entity_id";
        $r3 = $this->mysqli->query($query3) or die($this->mysqli->error . __LINE__);
        if ($r3->num_rows > 0) {
            $result = array();
            while ($row1 = $r3->fetch_assoc()) {
                $result[] = $row1;
            }
            
            $fielddetemp = json_encode($result);
            $empstridemp = json_decode($fielddetemp, true);
            $stringemp = array();
            
            foreach ($empstridemp as $valueemp) {
                $stringemp[] = $valueemp;
            }
            $this->response($this->json($stringemp), 200); // send employee details
        }
        $this->response('', 204);
    }

    /*
     * function to get particular employee details
     */
    private function employee()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $empid = (int) $this->_request['empID'];
        $query3 = "SELECT fea.entity_det_id,fea.assign_status ,fd.field_name,fd.value FROM fields_details as fd INNER JOIN fieldsdet_entitydet_assoc as fea ON fd.id=fea.fields_det_id WHERE fea.entity_id='$empid' AND fea.is_deleted='0' ";
        $r3 = $this->mysqli->query($query3) or die($this->mysqli->error . __LINE__);
        if ($r3->num_rows > 0) {
            $result = array();
            while ($row1 = $r3->fetch_assoc()) {
                $result[] = $row1;
            }
        }
        $fielddetemp = json_encode($result);
        $empstridemp = json_decode($fielddetemp, true);
        $stringemp = array();
        foreach ($empstridemp as $key => $valueemp) {
            $field_name = $valueemp['field_name'];
            $stringemp[$valueemp['entity_det_id']][$field_name] = $valueemp['value'];
        }
        $this->response($this->json($stringemp), 200); // send employee details
        $this->response('', 204);
    }

    /*
     * function to get particular employee details
     */
    private function employeestatus()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $empid = (int) $this->_request['empID'];
        $query3 = "SELECT fea.entity_det_id as artistid,fea.assign_status ,fd.field_name,fd.value FROM fields_details as fd INNER JOIN fieldsdet_entitydet_assoc as fea ON fd.id=fea.fields_det_id WHERE 	fea.entity_id='$empid' AND fea.is_deleted=0 ";
        $r3 = $this->mysqli->query($query3) or die($this->mysqli->error . __LINE__);
        if ($r3->num_rows > 0) {
            $result = array();
            while ($row1 = $r3->fetch_assoc()) {
                $result[] = $row1;
            }
        }
        $fielddetemp = json_encode($result);
        $empstridemp = json_decode($fielddetemp, true);
        $stringemp = array();
        foreach ($empstridemp as $key => $valueemp) {
            $field_name = $valueemp['field_name'];
            $stringemp[$valueemp['artistid']] = $valueemp;
        }
        $this->response($this->json($stringemp), 200); // send employee details
        $this->response('', 204);
    }

    /*
     * function to get all project detials
     */
    private function allprojdetails()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $empid = (int) $this->_request['empID'];
        $query3 = "select pd.id, pd.name,c.name as clientName  from project_details pd, client c WHERE pd.projectdetailsclientid = c.id";
        $r3 = $this->mysqli->query($query3) or die($this->mysqli->error . __LINE__);
        if ($r3->num_rows > 0) {
            $result = array();
            while ($row1 = $r3->fetch_assoc()) {
                $result[] = $row1;
            }
        }
        /*
         * $fielddetemp = json_encode($result);
         * $empstridemp = json_decode($fielddetemp, true);
         * $stringemp = array();
         * foreach ($empstridemp as $key => $valueemp) {
         * $stringemp[$valueemp['entity_id']] = $valueemp;
         * }
         */
        $this->response($this->json($result), 200); // send employee details
        $this->response('', 204);
    }

    /*
     * function to get all shot detials
     */
    private function allshotdetails()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $empid = (int) $this->_request['empID'];
        $deptId = isset($_GET['deptId']) ? $_GET['deptId'] : null;
        $projectId = isset($_GET['projectId']) ? $_GET['projectId'] : null;
        $whereFlag = false;
        $query3 = "SELECT s1.id, sdd1.version, sdd1.identify_id, d.dept,s1.shotcode, pd.name as projectname,
        s.status FROM department d, project_details pd, ( SELECT identify_id, MAX(version) AS maxversion,
        dept_id FROM shot_dept_details ";
        if($deptId != null && $deptId != '') {
            $whereFlag = true;
            $query3 .= "where dept_id = '$deptId' ";
        }
        
        $query3 .= "GROUP BY identify_id, dept_id, version) sdd2, shot_details s1,
        status s, shot_dept_details sdd1 where ";
        if ($projectId != null && $projectId != '') {
            $query3 .= " s1.shotallocationprojectdetailsid = '$projectId' and ";
        }
        $query3 .= " sdd1.identify_id = sdd2.identify_id and
     sdd1.version = sdd2. maxversion  and sdd1.dept_id = sdd2.dept_id and d.id = sdd1.dept_id
     and sdd1.shot_det_id = s1.id and pd.id = s1.shotallocationprojectdetailsid and sdd1.status = s.id";
        //print("query3".$query3);
        $r3 = $this->mysqli->query($query3) or die($this->mysqli->error . __LINE__);
        $result = array();
        if ($r3->num_rows > 0) {
            
            while ($row1 = $r3->fetch_assoc()) {
                $result[] = $row1;
            }
        }
        $fielddetemp = json_encode($result);
        $empstridemp = json_decode($fielddetemp, true);
        $stringemp = array();
        foreach ($empstridemp as $key => $valueemp) {
            $stringemp[] = $valueemp;
        }
        $this->response($this->json($stringemp), 200); // send employee details
        $this->response('', 204);
    }

    private function allshotdetailsdet()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $empid = (int) $this->_request['empID'];
        $query3 = "SELECT * FROM shot_details s1 JOIN ( SELECT entity_id, MAX(version) AS maxversion FROM shot_details GROUP BY entity_id) AS s2 ON s1.entity_id = s2.entity_id JOIN ( SELECT entity_id as projectentityid, name as projectname FROM project_details GROUP BY entity_id) AS s3 ON s1.shotallocationprojectdetailsid = s3.projectentityid AND row_id='0' AND shotallocationprojectdetailsid !='0' ";
        
        $r3 = $this->mysqli->query($query3) or die($this->mysqli->error . __LINE__);
        if ($r3->num_rows > 0) {
            $result = array();
            while ($row1 = $r3->fetch_assoc()) {
                $result[] = $row1;
            }
        }
        $fielddetemp = json_encode($result);
        $empstridemp = json_decode($fielddetemp, true);
        $stringemp = array();
        foreach ($empstridemp as $key => $valueemp) {
            $stringemp[$valueemp['entity_id']] = $valueemp;
        }
        $this->response($this->json($stringemp), 200); // send employee details
        $this->response('', 204);
    }

    /*
     * getting clientdetials
     */
    private function getClientpaymentdetails()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $clientid = (int) $this->_request['clientid'];
        $query3 = "select * from project_details WHERE `projectdetailsclientid`='$clientid' AND `due_id`>='1'  group by due_id,entity_id ORDER by id DESC";
        $r3 = $this->mysqli->query($query3) or die($this->mysqli->error . __LINE__);
        if ($r3->num_rows > 0) {
            $result = array();
            while ($row1 = $r3->fetch_assoc()) {
                $result[] = $row1;
            }
        }
        $fielddetemp = json_encode($result);
        $empstridemp = json_decode($fielddetemp, true);
        $stringemp = array();
        foreach ($empstridemp as $valueemp) {
            $stringemp[] = $valueemp;
        }
        $this->response($this->json($stringemp), 200); // send employee details
        $this->response('', 204);
    }

    /*
     * getting paymentdetails
     */
    private function paymentdetails()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $entityid = (int) $this->_request['entityid'];
        $dueid = (int) $this->_request['dueid'];
        $query3 = "select * from project_details WHERE `entity_id`='$entityid' AND `due_id`='$dueid'";
        $r3 = $this->mysqli->query($query3) or die($this->mysqli->error . __LINE__);
        if ($r3->num_rows > 0) {
            $result = array();
            while ($row1 = $r3->fetch_assoc()) {
                $result[] = $row1;
            }
        }
        $fielddetemp = json_encode($result);
        $empstridemp = json_decode($fielddetemp, true);
        $stringemp = array();
        foreach ($empstridemp as $valueemp) {
            $stringemp = $valueemp;
        }
        $this->response($this->json($stringemp), 200); // send employee details
        $this->response('', 204);
    }

    /*
     * update particular user
     */
    private function updateUser()
    {
        $creater = $this->_request['loggedusername'];
        $addname = $this->_request['newname'];
        $addpassword = $this->_request['password'];
        $addemail = $this->_request['email'];
        $userid = $this->_request['userid'];
        $date = date("Y-m-d H:i:s");
        
        $updateuser = "UPDATE users SET name='$addname',password='$addpassword',email='$addemail',modified_by='$creater',modified_date='$date' WHERE id=$userid";
        $r = $this->mysqli->query($updateuser);
        $success = array(
            'success' => "true",
            "message" => "Successfully updated one record."
        );
        $this->response($this->json($success), 200);
    }

    /*
     * delete particular user
     */
    private function deleteuser()
    {
        echo "delete user function in api.php";
        
        $id = (int) $this->_request['userid'];
        if ($id > 0) {
            $loggedinuser = $_COOKIE['username'];
            $logguser = str_replace('"', ' ', $loggedinuser);
            $date = date("Y-m-d H:i:s");
            $query = "UPDATE users SET is_deleted='1',modified_by='$logguser',modified_date='$date' WHERE id = $id";
            $r = $this->mysqli->query($query);
            $success = array(
                'success' => "true",
                "message" => "Successfully deleted one record."
            );
            $this->response($this->json($success), 200);
        } else
            $this->response('', 204); // If no records "No Content" status
    }

    /*
     * delete particular employee
     */
    private function deleteemp()
    {
        $id = (int) $this->_request['empid'];
        if ($id > 0) {
            $loggedinuser = $_COOKIE['username'];
            $logguser = str_replace('"', ' ', $loggedinuser);
            $date = date("Y-m-d H:i:s");
            $query1 = "UPDATE entity_det_table SET is_deleted='1',modified_by='$logguser',modified_date='$date' WHERE id = $id";
            $r1 = $this->mysqli->query($query1);
            $query2 = "UPDATE fieldsdet_entitydet_assoc SET is_deleted='1',modified_by='$logguser',modified_date='$date' WHERE entity_det_id = $id";
            $r2 = $this->mysqli->query($query2);
            $success = array(
                'success' => "true",
                "message" => "Successfully deleted one record."
            );
            $this->response($this->json($success), 200);
        } else {
            $this->response('', 204); // If no records "No Content" status
        }
    }

    /*
     * delete particular project
     */
    private function deleteproject()
    {
        $id = (int) $this->_request['empid'];
        if ($id > 0) {
            $loggedinuser = $_COOKIE['username'];
            $logguser = str_replace('"', ' ', $loggedinuser);
            $date = date("Y-m-d H:i:s");
            $query1 = "UPDATE entity_det_table SET is_deleted='1',modified_by='$logguser',modified_date='$date' WHERE id = $id";
            $r1 = $this->mysqli->query($query1);
            $query2 = "UPDATE project_details SET is_deleted='1',modified_by='$logguser',modified_date='$date' WHERE entity_id = $id";
            $r2 = $this->mysqli->query($query2);
            $success = array(
                'success' => "true",
                "message" => "Successfully deleted one record."
            );
            $this->response($this->json($success), 200);
        } else {
            $this->response('', 204); // If no records "No Content" status
        }
    }

    /*
     * delete particular project
     */
    private function deleteshot()
    {
        $id = (int) $this->_request['empid'];
        if ($id > 0) {
            $loggedinuser = $_COOKIE['username'];
            $logguser = str_replace('"', ' ', $loggedinuser);
            $date = date("Y-m-d H:i:s");
            $query1 = "UPDATE entity_det_table SET is_deleted='1',modified_by='$logguser',modified_date='$date' WHERE id = $id";
            $r1 = $this->mysqli->query($query1);
            $query2 = "UPDATE shot_details SET is_deleted='1',modified_by='$logguser',modified_date='$date' WHERE entity_id = $id";
            $r2 = $this->mysqli->query($query2);
            $success = array(
                'success' => "true",
                "message" => "Successfully deleted one record."
            );
            $this->response($this->json($success), 200);
        } else {
            $this->response('', 204); // If no records "No Content" status
        }
    }

    private function Deleteprojalloc()
    {
        $id = (int) $this->_request['empid'];
        
        if ($id > 0) {
            $loggedinuser = $_COOKIE['username'];
            $logguser = str_replace('"', ' ', $loggedinuser);
            $date = date("Y-m-d H:i:s");
            $query3 = "UPDATE entity_entity_assoc SET is_deleted='1',modified_by='$logguser',modified_date='$date' WHERE child_entity_id = $id";
            $r3 = $this->mysqli->query($query3);
            $query1 = "UPDATE entity_det_table SET is_deleted='1',modified_by='$logguser',modified_date='$date' WHERE id = $id";
            $r1 = $this->mysqli->query($query1);
            $query2 = "UPDATE fieldsdet_entitydet_assoc SET is_deleted='1',modified_by='$logguser',modified_date='$date' WHERE entity_det_id = $id";
            $r2 = $this->mysqli->query($query2);
            $success = array(
                'success' => "true",
                "message" => "Successfully deleted one record."
            );
            $this->response($this->json($success), 200);
        } else {
            $this->response('', 204); // If no records "No Content" status
        }
    }

    private function deletemultiple()
    {
        if (isset($_POST['bulk_delete_submit'])) {
            $idArr = $_POST['checked_id'];
            foreach ($idArr as $id) {
                $loggedinuser = $_COOKIE['username'];
                $logguser = str_replace('"', ' ', $loggedinuser);
                $date = date("Y-m-d H:i:s");
                $query = "UPDATE users SET is_deleted='1',modified_by='$logguser',modified_date='$date' WHERE id = $id";
                $r = $this->mysqli->query($query);
                $success = array(
                    'success' => "true",
                    "message" => "Successfully deleted one record."
                );
                $this->response($this->json($success), 200);
            }
        } else {
            $this->response('', 204); // If no records "No Content" status
        }
    }

    private function startShotWork()
    {
        $shot_dept_artist_details_id = $this->_request['shot_dept_artist_details_id'];
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
            $start_Time_Update_Query .= ",actual_start_date = '$getProjectHeaddate'";
            $actual_start_date = $date;
        }
        
        $start_Time_Update_Query .= " where id = '$shot_dept_artist_details_id'";
        // print("date".$start_Time_Update_Query);
        $r = $this->mysqli->query($start_Time_Update_Query);
        $success = array(
            'success' => "true",
            "message" => "Shot Worked Time Started Successfully",
            "actual_start_date" => "$actual_start_date"
        );
        $this->response($this->json($success), 200);
    }

    private function pauseShotWork()
    {
        $shot_dept_artist_details_id = $this->_request['shot_dept_artist_details_id'];
        $operationFlag = $this->_request['operationFlag'];
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
        print("nInterval" . $nInterval);
        print("worked_hours" . $worked_hours);
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
        // print($worked_Time_Update_Query);
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

    /*
     * private function completeShotWork() {
     * print("completework start");
     * $shot_dept_artist_details_id = $this->_request['shot_dept_artist_details_id'];
     * $worked_hours = $this->_request['worked_hours'];
     * $date=date("Y-m-d H:i:s");
     * }
     */
    private function getShotStatus()
    {
        $statusRetrivalQuery = "select id, status as value from status";
        $r = $this->mysqli->query($statusRetrivalQuery);
        $result = array();
        if ($r->num_rows > 0) {
            while ($row1 = $r->fetch_assoc()) {
                $result[] = $row1;
            }
        }
        $fielddetemp = json_encode($result);
        $empstridemp = json_decode($fielddetemp, true);
        $stringemp = array();
        foreach ($empstridemp as $valueemp) {
            $stringemp[] = $valueemp;
        }
        // print($result);
        $this->response($this->json($result), 200);
    }

    private function getDashBoardList()
    {
        $dashboardNameRetrivalQuery = "select id, name from dashboard";
        $r = $this->mysqli->query($dashboardNameRetrivalQuery);
        $result = array();
        if ($r->num_rows > 0) {
            while ($row1 = $r->fetch_assoc()) {
                $result[] = $row1;
            }
        }
        
        $this->response($this->json($result), 200);
    }

    function addnewuser()
    {
        $creater = $this->_request['loggedusername'];
        $addname = $this->_request['newname'];
        $addpassword = $this->_request['password'];
        $addemail = $this->_request['email'];
        $addrole = $this->_request['role'];
        
        $insertnewuser = "insert into users (name, password, email, userroleid,created_by) values ('$addname', '$addpassword', '$addemail', '$addrole','$creater')";
        $r = $this->mysqli->query($insertnewuser);
        $success = array(
            'success' => "true",
            "message" => "Successfully added one record."
        );
        $this->response($this->json($success), 200);
    }

    /* getting all role values */
    function getroles()
    {
        try {
            $userRole = (string) $_COOKIE['userRole'];
        } catch (Exception $e) {
            print("except" . $e->getMessage());
        }
        $query3V = "select * from role where id >= $userRole ";
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

    /* getting all company values */
    function companies()
    {
        $query3V = "select * from company";
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

    /* function to get All Levels */
    function getlevels()
    {
        $query3V = "select * from level";
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

    /* function to get All status */
    function getEntityStatus()
    {
        $query3V = "select * from entity_status";
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

    private function json($data)
    {
        if (is_array($data)) {
            return json_encode($data);
        }
    }
}

// Initiiate Library

$api = new API();
$api->processApi();
?>