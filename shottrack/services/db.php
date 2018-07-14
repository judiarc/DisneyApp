<?php
class db
{
const DB_SERVER = "127.0.0.1";

const DB_USER = "root";

const DB_PASSWORD = "";

const DB = "project_management";

public $db = NULL;

public $mysqli = NULL;

public function __construct()
{
    parent::__construct(); // Init parent contructor
    $this->dbConnect(); // Initiate Database connection
    // $this->functionconnect();
}

/*
 * Connect to Database
 */
public function dbConnect()
{
    $this->mysqli = new mysqli(self::DB_SERVER, self::DB_USER, self::DB_PASSWORD, self::DB);
}
}

