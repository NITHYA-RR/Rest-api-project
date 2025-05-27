<?php

require_once("database.class.php");

class signup{

    private $username;
    private $password;
    private $email;
    private $token;

    private $db;
    private $id;

    public function __construct($username, $password, $email){
        $this->db = Database::getConnection(); // Initiate Database connection
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        if($this->UserExists()){
            throw new Exception("User already exists");
        }
        $bytes = random_bytes(16);
        $this->token = $token = bin2hex($bytes);
        $password = $this->hashPassword($password);

        $query = "INSERT INTO `auth` (`username`, `password`, `email`, `token`, `action`) 
        VALUES ('$username', '$password', '$email', '$token',0)";
        if(!mysqli_query($this->db, $query)){
            throw new Exception("Unable to signin");

        }else{
            $this->id = mysqli_insert_id($this->db);
            
        }
    }

    function sendEmailVerification(){

        $config_json = file_get_contents("config.json");
        $config = json_decode($config_json, true);
        

    }



    public function getInsertID(){
        return $this->id;

    }

    public function UserExists(){
        $query = "SELECT * FROM `auth` WHERE `username` = '$this->username' OR `email` = '$this->email'";
        $result = mysqli_query($this->db, $query);
        if(mysqli_num_rows($result) > 0){
            return true; // User exists
        } else {
            return false; // User does not exist
        }
    }

    public function hashPassword($password){
        $options = [
            'cost' => 12,
        ];
        return password_hash($password, PASSWORD_BCRYPT, $options);
    }
    
}
?>