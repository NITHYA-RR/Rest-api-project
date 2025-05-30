<?php
error_reporting(E_ALL ^ E_DEPRECATED);
require_once("REST.api.php");
require_once("lib/database.class.php");
require_once("lib/signup.class.php");

class API extends REST
{

    public $data = "";

    private $db = NULL;
    private $current_call;

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

    /*
         * Public method for access api.
         * This method dynmically call the method based on the query string
         *
         */
    public function processApi()
    {

        $func = strtolower(trim(str_replace("/", "", $_REQUEST['rquest'] ?? '')));
        if ((int)method_exists($this, $func) > 0) {
            $this->$func();
        } else if (isset($_GET['namespace'])) {
            // Always ensure a single slash between 'apis' and namespace
            $dir = __DIR__ . '/api/apis/' . ltrim($_GET['namespace'], '/');
            if (!is_dir($dir)) {
                $this->response($this->json(['error' => 'namespace not found']), 404);
                return;
            }
            $files = scandir($dir);
            $found = false;
            foreach ($files as $file) {
                if ($file == "." || $file == "..") {
                    continue;
                }
                $baseName = basename($file, '.php');
                if ($baseName == $func) {
                    include($dir . '/' . $file);
                    if (isset(${$baseName}) && is_callable(${$baseName})) {
                        $this->current_call = Closure::bind(${$baseName}, $this, get_class());
                        $this->$baseName();
                    } else if (function_exists($baseName)) {
                        $this->current_call = $baseName;
                        $this->$baseName();
                    } else {
                        $this->response($this->json(['error' => 'function not found in namespace']), 404);
                    }
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $this->response($this->json(['error' => 'method not found in namespace']), 404);
            }
        } else {
            $this->response($this->json(['error' => 'method not found??????????????']), 404);
            echo $dir;
        }
    }
/*************API SPACE START*******************/

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

/*************API SPACE END*******************/
private function about()
    {

        if ($this->get_request_method() != "POST") {
            $error = array('status' => 'WRONG_CALL', "msg" => "The type of call cannot be accepted by our servers.");
            $error = $this->json($error);
            $this->response($error, 406);
        }
        $data = array('version' => '0.1', 'desc' => 'This API is created by Blovia Technologies Pvt. Ltd., for the public usage for accessing data about vehicles.');
        $data = $this->json($data);
        $this->response($data, 200);
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






    function generate_hash()
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
$api->processApi();
