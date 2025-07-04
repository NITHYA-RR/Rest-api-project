<?php
error_reporting(E_ALL ^ E_DEPRECATED);
require_once("REST.api.php");
require_once("lib/database.class.php");
require_once("lib/signup2.class.php");
require_once("lib/User.class.php");
require_once("lib/Auth.class.php");
require_once $_SERVER['DOCUMENT_ROOT'].'/api/vendor/autoload.php';


class API extends REST
{

    public $data = "";

    private $db = NULL;
    private $current_call;
    public $auth = null;

    public function __construct()
    {
        parent::__construct();                // Init parent contructor
        $this->dbConnect();                    // Initiate Database connection
    }
    /*
           Database connection
        */

    private function dbConnect()
    {
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
        }
    }

public function processApi(){
     
$func = strtolower(trim(str_replace("/", "", $_REQUEST['rquest'] ?? '')));
        if((int)method_exists($this,$func) > 0){
            $this->$func();
        }
        else {
            if(isset($_GET['namespace'])){
    $namespace = trim($_GET['namespace'], '/');
    $dir = __DIR__ . '/apis/' . $namespace;
    $file = $dir . '/' . $func . '.php';
    if(file_exists($file)){
        include $file;
        if (isset(${$func}) && is_callable(${$func})) {
            $this->current_call = Closure::bind(${$func}, $this, get_class());
            call_user_func($this->current_call);
        } else if (function_exists($func)) {
            $this->current_call = $func;
            call_user_func($this->current_call);
        } else {
            $this->response($this->json(['error' => 'function_not_found_in_namespace']), 404);
        }
    } else {
        $this->response($this->json(['error'=>'method_not_found']),404);
    }
} else {
    $this->response($this->json(['error'=>'method_not_found!!!']),404);
}
   
        }
    }
    private function verify()
    {
        $user = $this->_request['user'];
        $password =  $this->_request['pass'];

        $flag = 0;
        if ($user == "admin") {
            if ($password == "adminpass123") {
                $flag = 1;
            }
        }

        if ($flag == 1) {
            $data = [
                "status" => "verified"
            ];
            $data = $this->json($data);
            $this->response($data, 200);
        } else {
            $data = [
                "status" => "unauthorized"
            ];
            $data = $this->json($data);
            $this->response($data, 403);
        }
    }

    private function test()
    {
        $data = $this->json(getallheaders());
        $this->response($data, 101);
    }

    public function auth(){
        $headers = getallheaders();
        if(isset($headers['Authorization'])){
            $token = explode(' ', $headers['Authorization']);
            $this->auth = new Auth($token[1]);
        }
    }


  public function isAuthenticated(){
        if($this->auth == null){
            return false;
        }
        if($this->auth->getOAuth()->authenticate() and isset($_SESSION['username'])){
            return true;
        } else {
            return false;
        }
    }

    public function die($e){
        $data = [
            "error" => $e->getMessage()
        ];
        $response_code = 400;
        if($e->getMessage() == "Expired token" || $e->getMessage() == "Unauthorized"){
            $response_code = 403;
        }

        if($e->getMessage() == "Not found"){
            $response_code = 404;
        }
        $data = $this->json($data);
        $this->response($data,$response_code);
    }




    public function generate_hash()
    {
        $bytes = random_bytes(16);
        return bin2hex($bytes);
    }

    public function getUsername()
    {
        return $_SESSION['username'];
    }

    public function getUserID()
    {
        return $_SESSION['user_id'];
    }
    private function json($data)
    {
        if (is_array($data)) {
            return json_encode($data, JSON_PRETTY_PRINT);
        } else {
            return "{}";
        }
    }
}

// Initiiate Library

$api = new API;
try {
    $api->auth();
    $api->processApi();
} catch (Exception $e){
    $api->die($e);
};
