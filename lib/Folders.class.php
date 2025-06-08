<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/api/lib/database.class.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/api/lib/Share.class.php');
class folder extends Share{

    private $db;
    private $data = null;
    private $id = null;  

    public function __construct($id = null){
        $this->db = Database::getConnection();
        if($id!=null){
            $query = "SELECT * FROM folders WHERE id = $id";
            $result = mysqli_query($this->db, $query);
            if($result){
                $this->data = mysqli_fetch_assoc($result);
                $this->id = $this->data['id'];
        }else{
                throw new Exception(("Note not found"));
            }
        }
    } 
    public function getName(){
        if($this->data and isset($this->data['name'])){
            return $this->data['name'];


    }
    }

    public function createNew($name = 'New Folder'){
        if(isset($_SESSION['username'])){
            $query = "INSERT INTO folders (`name`,`owner`) VALUES ('$name','$_SESSION[username]')";
            if(mysqli_query($this->db, $query)){
                $this->id = mysqli_insert_id($this->db);
                return $this->id;
            }
        }
    }        
    public function refresh(){
        if($this->id != null){
            $query = "SELECT * FROM folders WHERE id = $this->id";
            $result = mysqli_query($this->db, $query);
            if($result){
                $this->data = mysqli_fetch_assoc($result);
                $this->id = $this->data['id'];
        }else{
                throw new Exception("Notes not found".mysqli_error($this->db));
            }
        }
    }
    public function rename($name){
    if($this->id){
        $name = mysqli_real_escape_string($this->db, $name);
        $query = "UPDATE folders SET name = '$name' WHERE id = $this->id";
        $result = mysqli_query($this->db, $query);
        if($result){
            $this->refresh(); // refresh doesn't need a parameter
            return true;
        } else {
            throw new Exception("Rename failed: " . mysqli_error($this->db));
        }
    } else {
        throw new Exception("Folder not found! ID is missing.");
    }
}

    public function getAllFoldders(){

    }

}