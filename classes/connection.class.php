<?php
class Connection{



    private $servername;
    private $username;
    private $password;
    public $databasename;

    public $database;

    public function __construct(){
        $this->databaseConnection();

    }// end construct function


    function databaseConnection(){
        $this->servername="localhost";
        $this->username="root";
        $this->password="";
        $this->databasename="hospital_data";

        $this->database = new mysqli($this->servername, $this->username, $this->password, $this->databasename);
        return $this->database;
    }// end  Databaseconnection function



}// end  connection class
?>