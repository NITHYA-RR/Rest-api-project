<?php
class Database{
    public static $conn = null;
    public static function getConnection(){
        if (Database::$conn == null){
            
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "apis";
            
        
            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
        
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } 
            else{
                // printf("connection will be created........");
                Database::$conn = $conn;
                return Database::$conn; // replaacing null with actual value
               

            }
        } else{
            // printf("established the connection..");
            return Database::$conn;
            
        }
 
    }
}