<?php

require_once("signup.class.php");

$token = $_GET['token'] ?? null;
        if(signup::verifyAccount($token)){
    ?>
            <h1 style="color: green;">Account Verified Successfully!</h1>
    <?php
        } else {
    ?>
             <h1 style="color: red;">Account Verification Failed!</h1>
    <?php

}