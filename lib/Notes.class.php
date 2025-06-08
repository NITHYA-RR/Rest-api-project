<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/api/lib/Database.class.php');

class notes extends Share{
    public function __construct($id=NULL)
    {
        parent::__construct($id, 'note');
    }

    
    public static function getAllNotes($per_page=10, $page=1){
        
    }
}