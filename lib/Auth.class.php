<?php
require_once("database.class.php");
require ("__DIR__ . '/../../vendor/autoload.php");

class Auth {
    private $token;
    private $username;
    private $password;
    private $db;
    private $isTokenAuth = false;
    private $loginToken = NULL;
    public function __construct($username, $password = NULL){
        $this->db = Database::getConnection(); // Initiate Database connection
        
    
    if($password == NULL){
        $this->token = $username;
        $this->isTokenAuth = true;
    }
    else {
        $this->username = $username; //it might be username or email
        $this->password = $password;
    }
    if($this->isTokenAuth){
        throw new Exception("Token authentication is not implemented yet");
    }
    else {
      $user = new User($this->username);
      $hash = $user->getPasswordHash();
      $this->username = $user->getUsername();
      if(password_verify($password, $hash)){
          if(!$user->isActive()){
                throw new Exception("User is not active");

          }
          $this->loginToken = $this->addSession();
      } else {
          throw new Exception("Invalid username or password");
      }
    }
    }
    

    public function getAuthToken(){
        if($this->loginToken){
            return $this->loginToken;
        } else {
            throw new Exception("No login token available");
        }
    }

    private function addSession(){
        $token = Auth::generateRandomHash(32);
        
        // $query = "INSERT INTO `session` (`username`, `token`) 
        // VALUES ('$this->username', $token',)";
        $query = "INSERT INTO `session` (`username`, `token`) 
          VALUES ('{$this->username}', '{$token}')";

        if(mysqli_query($this->db, $query)){
            return $token;
        } else {
            throw new Exception("Failed to create session: " . mysqli_error($this->db));
        }
        
    }

    public static function generateRandomHash($len){
        $bytes = openssl_random_pseudo_bytes($len, $cstring);
        return bin2hex($bytes);
    }
}

