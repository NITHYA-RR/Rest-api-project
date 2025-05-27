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


        $query = "INSERT INTO `auth` (`username`, `password`, `email`, `token`, `action`) 
        VALUES ('$username', '$password', '$email', '$token',0)";
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
        $email->setFrom("", "API curse by selfmade");
        $email->setSubject("Verify your account");
        $email->addTo($this->email,$this->username);
        $email->addContent("text/plain", "please verify your account by clicking the link below:\n\n" .
            "https://nithya@ramesh.com/verify.php?token=" . $this->token);
        $email->addContent(
            "text/html",
            '<strong>Please verify your account by <a href="https://nithya@ramesh.com/verify.php?token=' . $this->token . '">clicking here</a></strong>'
        );
        $sendgrid = new \SendGrid($envjson['email_api_key']);
        try {
            $response = $sendgrid->send($email);
            // print $response->statusCode() . "\n";
            // print_r($response->headers());
            // print $response->body() . "\n";
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
        $query = "SELECT * FROM `auth` WHERE `token` = '$token';";
        $db = Database::getConnection();
        $result = mysqli_query($db, $query);
        if ( $result and mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $stmt = $db->prepare("UPDATE `auth` SET `action` = 1 WHERE `token` = ?");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            return true;
        }
    else{
                return false;
            }

}
}