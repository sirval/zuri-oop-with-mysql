<?php
session_start();
class Dbh{
    private $hostname = 'localhost';
    private $username = 'root';
    private $password = '';
    private $dbname = 'zuriphp';
   
    protected function connect()
    {
        $conn = mysqli_connect($this->hostname, $this->username, $this->password, $this->dbname);
        if(mysqli_connect_error()){
			die("Database Connection Failed" . mysqli_connect_error() . mysqli_connect_errno());
		}
        return $conn;
    }
}

