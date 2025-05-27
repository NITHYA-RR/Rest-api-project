<?php
class Database{
    public static $conn = null;
    public static function getConnection(){
        if (Database::$conn == null){
            
            $envPath = __DIR__ . '/../../env.json';
            if (!file_exists($envPath)) {
                die("env.json not found at $envPath");
            }
            $env = json_decode(file_get_contents($envPath), true);
            $servername = $env['db_server'];
            $username = $env['db_username'];
            $password = $env['db_password'];
            $dbname = $env['db_name'];
    
            
        
            // Create connection
            self::$conn = mysqli_connect($servername, $username, $password, $dbname);

            // Check connection
            if (!self::$conn) {
                die("Connection failed: " . mysqli_connect_error());
            } 
            else{
                // printf("connection will be created........");
               return self::$conn; // replaacing null with actual value
               

            }
        } else{
            // printf("established the connection..");
            return Database::$conn;
            
        }
 
    }
}