<?php
//Author: Johnathon Southworth
//Class: CS296 PHP Jeff Miller
//Final -- blog
class Connection {
    protected $db;
    public function Connection() {

    $conn = NULL;
        try{
            $conn = new PDO("mysql:host=localhost;dbname=blog", "xxxx", "xxxx"); //changed for later
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e){
                echo 'ERROR: ' . $e->getMessage();
                }
            $this->db = $conn;
    }

    public function getConnection() {
        return $this->db;
    }
}
?>
