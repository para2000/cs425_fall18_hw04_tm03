<?php 
  class Database {
    // DB Params
    private $host = 'localhost';
    private $db_name = 'asg4db';
    private $username = 'epl425as4';
    private $password = '1q2w3e4r5t';
    private $conn;

    // DB Connect
    public function connect() {
      $this->conn = null;

      try { 
        $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch(PDOException $e) {
        //echo 'Connection Error: ' . $e->getMessage();
      }

      return $this->conn;
    }
  }
  
  ?>
