<?php
require_once ("Rest.inc.php");

class Header extends REST
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
            case 'getUserEntity':
                $this->getUserEntity();
                break; 
        }
    }

    
    private function getUserEntity(){       
        $userid = $_COOKIE['userid'];
        $userRole = $_COOKIE["userRole"];
        
        $userEntityQuery = "select DISTINCT e.id, e.name from entity_table e,
         user_entity_field_assoc uefa where uefa.user_id = $userid and uefa.entity_id = e.id order by e.id asc";
        
        
        // print("userFieldQuery".$userEntityQuery);
        $userFieldQueryRawOut = $this->mysqli->query($userEntityQuery) or die($this->mysqli->error . __LINE__);
        $result = array();
        if ($userFieldQueryRawOut->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($userFieldQueryRawOut)) {
                $result[] = $row;
            }
        } else {
            $roleFieldQuery = "select DISTINCT e.id, e.name from entity_table e,
         role_entity_field_assoc refa where refa.role_id = $userRole and refa.entity_id = e.id order by e.id asc";
            //print("roleFieldQuery". $roleFieldQuery);
            $roleFieldQueryRawOut = $this->mysqli->query($roleFieldQuery) or die($this->mysqli->error . __LINE__);
            if ($roleFieldQueryRawOut->num_rows > 0) {
                while ($row = mysqli_fetch_assoc($roleFieldQueryRawOut)) {
                    $result[] = $row;
                }
            }else {
                //If user doesn't have user specific fields and role specific fields the fields will be retrieved from entity assoc table
                
                $entityFieldsQuery = "select id, name from entity_table";
                //print("entityFieldsQuery".$entityFieldsQuery);
                $entityFieldsRawOut = $this->mysqli->query($entityFieldsQuery) or die($this->mysqli->error . __LINE__);
                if ($entityFieldsRawOut->num_rows > 0) {
                    while ($row = mysqli_fetch_assoc($entityFieldsRawOut)) {
                        $result[] = $row;
                    }
                }
            }
        }
        $this->response(json_encode($result), 200);
        $this->response('', 204);
    }
}

// Initiiate Library

$api = new Header();
$api->functionconnect();
