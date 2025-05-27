<?php

phpinfo();
// class DatabaseConnector {
//     private $db = NULL;

//     public function dbConnect() {
//     if ($this->db != NULL) {
//         return $this->db;
//     } else {
//         // Read database credentials from env.json
//         $envPath = __DIR__ . '/../env.json'; // Use forward slashes or double backslashes for cross-platform

//         $envJson = file_get_contents($envPath);
//         if ($envJson === false) {
//             die("Failed to read env.json");
//         }

//         $env = json_decode($envJson, true);
//         if ($env === null && json_last_error() !== JSON_ERROR_NONE) {
//             die("JSON Decode Error: " . json_last_error_msg());
//         }

//         $servername = $env['db_server'];
//         $username = $env['db_username'];
//         $password = $env['db_password'];
//         $dbname = $env['db_name'];

//         $this->db = mysqli_connect($servername, $username, $password, $dbname);
//         if (!$this->db) {
//             die("Connection failed: " . mysqli_connect_error());
//         } else {
//             return $this->db;
//         }
//     }
// }


    
// }

// // Example usage:
// $connector = new DatabaseConnector();
// $db = $connector->dbConnect();
// if ($db) {
//     echo "✅ Database connected successfully!";
// } else {
//     echo "❌ Failed to connect to database!";
// }





                
            