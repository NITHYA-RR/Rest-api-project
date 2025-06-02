<?php

class ApiHandler {
    private $current_call;

    public function processApi(){
        $func = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
        if((int)method_exists($this,$func) > 0){ 
            $this->$func();
        }
        else {
            if(isset($_GET['namespace'])){
                $dir =  __DIR__ .'/apis'.$_GET['namespace'];
                $file = $dir.'/'.$func.'.php';
                if(file_exists($file)){
                    include $file;
                    $this->current_call = Closure::bind(${$func}, $this, get_class());
                    $this->$func();
                } else {
                    $this->response($this->json([
                    'error' => 'method_not_found',
                    'message' => 'The method was not found in the specified namespace.',
                     'searched_path' => $file,
                    'namespace_dir' => $dir
    ]), 404);
                }

                /** 
                 * Use the following snippet if you want to include multiple files
                 */
                // $methods = scandir($dir);
                // //var_dump($methods);
                // foreach($methods as $m){
                //     if($m == "." or $m == ".."){
                //         continue;
                //     }
                //     $basem = basename($m, '.php');
                //     //echo "Trying to call $basem() for $func()\n";
                //     if($basem == $func){
                //         include $dir."/".$m;
                //         $this->current_call = Closure::bind(${$basem}, $this, get_class());
                //         $this->$basem();
                //     }
                // }
            } else {
                //we can even process functions without namespace here.
                $this->response($this->json(['error'=>'method_not_found$$$$$s']),404);
            }
        }
    }

    public function __call($method, $args)
    {
        if(is_callable($this->current_call)){
            return call_user_func_array($this->current_call, $args);
        } else {
            $error = array('status' => 'NOT_FOUND', "msg" => "The requested method does not exist.");
            $error = $this->json($error);
            $this->response($error, 404);
        }
    }
}















































// require 'vendor/autoload.php';

// $envPath = __DIR__ . '/../env.json';
// $env = file_get_contents($envPath);
// $envjson = json_decode($env, true);

// $email = new \SendGrid\Mail\Mail();
// $email->setFrom("nithyaramasamy@protonmail.com", "Nithyaa");
// $email->setSubject("Testing SendGrid Email");
// $email->addTo("nithyaramasamy@protonmail.com", "Nithya Test");
// $email->addContent("text/plain", "This is a test email from SendGrid + PHP.");
// $email->addContent("text/html", "<strong>This is a <i>test</i> email from SendGrid + PHP.</strong>");

// $sendgrid = new \SendGrid($envjson['email_api_key']);

// try {
//     $response = $sendgrid->send($email);
//     echo "Status Code: " . $response->statusCode() . "\n";
//     echo "Response Body:\n" . $response->body() . "\n";
// } catch (Exception $e) {
//     echo 'Caught exception: ' . $e->getMessage() . "\n";
// }


// phpinfo();
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





                
        //     if($this->get_request_method() == "POST"){
        //         $data = [
        //             "error"=>"method_not_allowed"
        //         ];
        //         $this->response($this->json($data), 405);
        //     }
        //     if(isset($this->_request['username']) and isset($this->_request['password']) and isset($this->_request['email'])){
        //         $username = $this->_request['username'];
        //         $password = $this->_request['password'];
        //         $email = $this->_request['email'];

        //         $hashed_password = signup::hashPassword($password);

        //         // Validate username, password, and email


        //         $query = "INSERT INTO auth (username, password, email) VALUES ('$username', '$password', '$email');";

        //         $db = $this->dbConnect();
        //         $result = mysqli_query($db, $query);

        //         if($result){
        //             $user_id = mysqli_insert_id($db);
        //             $data = [
        //                 "message"=>"success $username",
        //                 "user_id"=>$user_id,

        //             ];
        //             $this->response($this->json($data), 201);
        //         } else {
        //             $data = [
        //                 "error"=>"internal_server_error"
        //             ];
        //             $this->response($this->json($data), 500);
        //         }
        //     } else {
        //         $data = [
        //             "error"=>"expectation_failed"
        //         ];
        //         $this->response($this->json($data), 417);
        //     }
        // }



    //     public static function verifyAccount($token){
    //     $query = "SELECT * FROM `auth` WHERE `token` = '?';";
    //     $db = Database::getConnection();
    //     $result = mysqli_query($db, $query);
    //     if ( $result and mysqli_num_rows($result) == 1) {
    //         $row = mysqli_fetch_assoc($result);
    //         mysqli_query($db, "UPDATE `auth` SET `action` = '1' WHERE `token` = '?';");
            
    //         return true;
    //     }
    // else{
    //             return false;
    //         }

