<?php
require_once("database.class.php");
require ("__DIR__ . '/../../vendor/autoload.php");

class User{
    private $db;
    private $username;
    private $user;
    public function __construct($username){
        $this->username = $username;
        $this->db = Database::getConnection(); // Initiate Database connection
        $query = "SELECT * FROM `auth` WHERE `username` = '$username' or email = '$username' LIMIT 1";
        $result = mysqli_query($this->db, $query);
        if(mysqli_num_rows($result) == 1){
            $this->user = mysqli_fetch_assoc($result);
        } else {
            throw new Exception("User not found");
        }
    }
    public function getUsername(){
        return $this->user['username'];
    }
    public function getPasswordHash(){
        return $this->user['password'];
    }
    public function getEmail(){
        return $this->user['email'];
    }
    public function isActive(){
        return $this->user['action'] == 1;

    }
}