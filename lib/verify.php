<?php

require_once("signup.class.php");

$token = $_GET['token'] ?? null;
try{
        if(signup::verifyAccount($token)){
    ?>
            <h1 style="color: green;">Account Verified Successfully!</h1>
    <?php
        } else {
    ?>
             <h1 style="color: red;">Account Verification Failed!</h1>
    <?php
        }
    }
catch(Exception $e){
    ?>
    
    <h1 style="color: orange;">Already verified</h1>
<?php
}