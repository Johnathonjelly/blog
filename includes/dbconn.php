<?php
class Connection {
  protected $db;
  public $error;
  var $blogTitle, 
    $blogBody, 
    $blogTags, 
    $blogImg, 
    $blogActive;

  public function __construct() {
    $conn = NULL;
      try {
        $conn = new PDO("mysql:host=localhost;dbname=blog", "root", "root");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch(PDOException $e) {
        $this->error = $e->getMessage();
      }
        $this->db = $conn;
      }
      public function getConnection() {
        return $this->db;
      }
  }