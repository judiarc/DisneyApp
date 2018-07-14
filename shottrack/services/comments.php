<?php
require_once ("Rest.inc.php");

class Comments extends REST
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
            case 'addOrUpdateComment':
                $this->addOrUpdateComment();
                break;
            case 'removeComment':
                $this->removeComment();
                break;
            case 'retreiveComments':
                $this->retrieveComments();
                break;
           
        }
    }

    /* Function for Add or Update User Details */
    function addOrUpdateComment()
    {
        if (isset($_GET['entityId'])) {
            $entityId = $_GET['entityId'];
        } else {
            $entityId = '';
        }
        if (isset($_GET['entityType'])) {
            $entityTypeId = $_GET['entityType'];
        } else {
            $entityTypeId = '';
        }
        if (isset($_GET['feedback'])) {
            $feedback = $_GET['feedback'];
        } else {
            $feedback = '';
        }
        if (isset($_GET['feedbackpath'])) {
            $feedbackpath = strtolower($_GET['feedbackpath']);
        } else {
            $feedbackpath = '';
        }
        
        $loginUserid = $_COOKIE["userid"];
        $date = date("Y-m-d H:i:s");
        
        $insertComments = "insert into feedback (entity_Id, entity_type_id, feedback, feedback_path, created_by, created_date) values ('$entityId', '$entityTypeId', '$feedback',
             '$feedbackpath', '$loginUserid','$date')";
       // print($insertComments);
        $r = $this->mysqli->query($insertComments);
        $commentId = mysqli_insert_id($this->mysqli);
        
        if ($commentId) {
            $data['success'] = true;
            $data['commentId'] = $commentId;
            $data['message'] = 'Record Saved Successfully';
        } else {
            $data['failure'] = true;
            $data['message'] = 'Record is not properly saved in Database';
        }
        
        $this->response(json_encode($data), 200);
    }
    /**
     * @param email
     */
     private function retrieveComments()
    {
        if (isset($_GET['entityId'])) {
            $entityId = $_GET['entityId'];
        } else {
            $entityId = '';
        }
        if (isset($_GET['entityType'])) {
            $entityTypeId = $_GET['entityType'];
        } else {
            $entityTypeId = '';
        }
        
        $feedbackQuery = "SELECT fb.*, u.name from feedback fb, shot_dept_details sdd, shot_work_details swd, users u where sdd.shot_det_id = $entityId
        and (sdd.identify_id = fb.entity_id and fb.entity_type_id = 8) or 
        (sdd.identify_id = swd.shot_dept_details_identify_id and swd.id = fb.entity_id and fb.entity_type_id = 10)
         and u.id = fb.created_by order by fb.created_date";    
        
        $r3V = $this->mysqli->query($feedbackQuery) or die($this->mysqli->error . __LINE__);
        // print($r3V->num_rows);
        if ($r3V->num_rows > 0) {
            $result3V = array();
            while ($row3V = $r3V->fetch_assoc()) {
                $result3V[] = $row3V;
            }                       
            $this->response($this->json($result3V), 200); // send user details
        }  
        $this->response("Empty Comments", 200);
    }


   
    function removeComment()
    {
        if (isset($_GET['userId'])) {
            $userId = $_GET['userId'];
        } else {
            $userId = null;
        }
        $removeUser_query = "DELETE FROM USERS WHERE ID IN ($userId)";
        $r = $this->mysqli->query($removeUser_query);
        if ($r) {
            $this->response(json_encode("success"), 200);
        } else {
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

$api = new Comments();
$api->functionconnect();
