<pre><?php
require __DIR__ . '/lib/User.class.php';
require __DIR__.'/lib/Folders.class.php';
require __DIR__ . '/vendor/autoload.php';

// $user = new User('Ramya@gmail.com');
// echo $user->getUsername();

session_start();
$_SESSION['username'] = 'kumaruuu';
try {
    $f = new folder(1);
    echo "Before: " . $f->getName() . "<br>";
    $f->rename("kuma");
    echo "After: " . $f->getName() . "<br>";
} catch(Exception $e) {
    echo "Error: " . $e->getMessage();
}

// print_r($GLOBALS);
// print_r($_SERVER);
// print_r($_REQUEST);
// print_r($_POST);
// print_r($_GET);
// print_r($_FILES);
// print_r($_ENV);
// print_r($_COOKIE);
// print_r($_SESSION);
?></pre>