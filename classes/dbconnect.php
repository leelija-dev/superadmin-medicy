<?php
require_once dirname(__DIR__).'/config/constant.php';


trait DatabaseConnection {


    private $servername;
    private $username;
    private $password;
    private $dbname;
    public $conn;


    public function __construct()
    {

        $this->db_connect();
    }


    function db_connect()
    {

        $this->servername   = DBHOST;
        $this->username     = DBUSER;
        $this->password     = DBPASS;
        $this->dbname       = DBNAME;

        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        return $this->conn;
        
    }
}// DatabaseConnection end
