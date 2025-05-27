<?php
class Database{
    public static $conn = null;
    public static function getConnection(){
        if (Database::$conn == null){
            
            $envPath = __DIR__ . '/../../env.json';
            if (!file_exists($envPath)) {
                die("env.json not found at $envPath");
            }
            $env = file_get_contents($envPath);
            $envjson = json_decode($env, true);
            $servername = $envjson['db_server'];
            $username = $envjson['db_username'];
            $password = $envjson['db_password'];
            $dbname = $envjson['db_name'];
            // Create connection
            $conn = mysqli_connect($servername, $username, $password, $dbname);

            // Check connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            } 
            else{
    
               return $conn; // replaacing null with actual value
            }
        } else{
            return Database::$conn;
            
        }
 
    }
}