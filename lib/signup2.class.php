<?php

require_once("database.class.php");
require ("__DIR__ . '/../../vendor/autoload.php");


class signup
{

    private $username;
    private $password;
    private $email;
    private $token;

    private $db;
    private $id;

    public function __construct($username, $password, $email)
    {
        $this->db = Database::getConnection(); // Initiate Database connection
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        if ($this->UserExists()) {
            throw new Exception("User already exists");
        }
        $bytes = random_bytes(16);
        $this->token = $token = bin2hex($bytes);
        // Generate a secure token for email verification
        $password = $this->hashPassword($password);
        // Prepare the SQL query to prevent SQL injection


        $query = "INSERT INTO `auth` (`username`, `password`, `email`, `token`) 
        VALUES ('$username', '$password', '$email', '$token')";
        if (!mysqli_query($this->db, $query)) {
            throw new Exception("Unable to signin");
        } else {
            $this->id = mysqli_insert_id($this->db);
            $this->sendEmailVerification();
        }
    }

    function sendEmailVerification()
    {
        $envPath = __DIR__ . '/../../env.json';
        $env = file_get_contents($envPath);
        $envjson = json_decode($env, true);

        $email = new \SendGrid\Mail\Mail();
        $email->setFrom("nithyaramasamy@protonmail.com", "API curse by selfmade");
        $email->setSubject("Verify your account");
        $email->addTo($this->email,$this->username);
        $email->addContent("text/plain", "please verify your account by clicking the link below:\n\n" .
            "https://NithyaR-R@proton.me/verify.php?token=" . $this->token);
        $email->addContent(
            "text/html",
            '<strong>Please verify your account by <a href="https://NithyaR-R@proton.me/verify.php?token=' . $this->token . '">clicking here</a></strong>'
        );
        $sendgrid = new \SendGrid($envjson['email_api_key']);
        try {
            $response = $sendgrid->send($email);
            
        } catch (Exception $e) {
            echo 'Caught exception: ' . $e->getMessage() . "\n";
        }
    }



    public function getInsertID()
    {
        return $this->id;
    }

    public function UserExists()
    {
        $query = "SELECT * FROM `auth` WHERE `username` = '$this->username' OR `email` = '$this->email'";
        $result = mysqli_query($this->db, $query);
        if (mysqli_num_rows($result) > 0) {
            return true; // User exists
        } else {
            return false; // User does not exist
        }
    }

    public static function hashPassword($password)
    {
        $options = [
            'cost' => 12,
        ];
        return password_hash($password, PASSWORD_BCRYPT, $options);
        
    }

        public static function verifyAccount($token){
        $db = Database::getConnection();
        $query = "SELECT * FROM `auth` WHERE `token` = '$token';";
        $result = mysqli_query($db, $query);
        if ( $result and mysqli_num_rows($result) > 0) {
            $data = mysqli_fetch_assoc($result);
            if($data['action'] == 1){
                throw new Exception("Already Verified");
            }
                mysqli_query($db, "UPDATE `auth` SET `action` = '1' WHERE `token` = '$token';");
                return true;
        }
    else{
                return false;
            }






}
}