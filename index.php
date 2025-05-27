<?php
    error_reporting(E_ALL ^ E_DEPRECATED);
    require_once("REST.api.php");
    require_once("lib/database.class.php");

    class API extends REST {

        public $data = "";

        private $db = NULL;

        public function __construct(){
            parent::__construct();                // Init parent contructor
            $this->dbConnect();                    // Initiate Database connection
        }
        /*
           Database connection
        */

        private function dbConnect(){
    if ($this->db != NULL) {
        return $this->db;
    } else {
        // Read database credentials from env.json
        $envPath = __DIR__ . '/../env.json';
        $env = file_get_contents($envPath);
        $envjson = json_decode($env, true);
        $servername = $envjson['db_server'];
        $username = $envjson['db_username'];
        $password = $envjson['db_password'];
        $dbname = $envjson['db_name'];

        $this->db = mysqli_connect($servername, $username, $password, $dbname);
        if (!$this->db) {
            die("Connection failed: " . mysqli_connect_error());
        } else {
            return $this->db;
        }
        // $connector = new DatabaseConnec();
        // $db = $connector->dbConnect();
        // if ($db) {
        // echo "✅ Database connected successfully!";
        // } else {
        // echo "❌ Failed to connect to database!";
        // }
    }
}

        /*
         * Public method for access api.
         * This method dynmically call the method based on the query string
         *
         */
        public function processApi(){
            
            $func = strtolower(trim(str_replace("/","",$_REQUEST['rquest']?? '')));
            if((int)method_exists($this,$func) > 0)
                $this->$func();
            else
                $this->response('',400);                // If the method not exist with in this class, response would be "Page not found".
        }

        /*************API SPACE START*******************/

        private function about(){

            if($this->get_request_method() != "POST"){
                $error = array('status' => 'WRONG_CALL', "msg" => "The type of call cannot be accepted by our servers.");
                $error = $this->json($error);
                $this->response($error,406);
            }
            $data = array('version' => '0.1', 'desc' => 'This API is created by Blovia Technologies Pvt. Ltd., for the public usage for accessing data about vehicles.');
            $data = $this->json($data);
            $this->response($data,200);

        }

        private function verify(){
            $user = $this->_request['user'];
            $password =  $this->_request['pass'];

            $flag = 0;
            if($user == "admin"){
                if($password == "adminpass123"){
                    $flag = 1;
                }
            }

            if($flag == 1){
                $data = [
                    "status" => "verified"
                ];
                $data = $this->json($data);
                $this->response($data,200);
            } else {
                $data = [
                    "status" => "unauthorized"
                ];
                $data = $this->json($data);
                $this->response($data,403);
            }
        }

        private function test(){
                $data = $this->json(getallheaders());
                $this->response($data,101);
        }


     



        function generate_hash(){
            $bytes = random_bytes(16);
            return bin2hex($bytes);
        }



        public function getUsername(){
        return $_SESSION['username'];
    }
        public function getUserID(){
            return $_SESSION['user_id'];
        }
        

         private function signup(){
            if($this->get_request_method() != "POST"){
                $data = [
                    "error"=>"method_not_allowed"
                ];
                $this->response($this->json($data), 405);
            }
            if(isset($this->_request['username']) and isset($this->_request['password']) and isset($this->_request['email'])){
                $username = $this->_request['username'];
                $password = $this->_request['password'];
                $email = $this->_request['email'];

                $query = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email');";

                $db = $this->dbConnect();
                $result = mysqli_query($db, $query);
                if($result){
                    $data = [
                        "message"=>"success"
                    ];
                    $this->response($this->json($data), 201);
                } else {
                    $data = [
                        "error"=>"internal_server_error"
                    ];
                    $this->response($this->json($data), 500);
                }
            } else {
                $data = [
                    "error"=>"expectation_failed"
                ];
                $this->response($this->json($data), 417);
            }
        }



        private function json($data){
            if(is_array($data)){
                return json_encode($data, JSON_PRETTY_PRINT);
            } else {
                return "{}";
            }
        }

    }

    // Initiiate Library

    $api = new API;
    $api->processApi();
?>
