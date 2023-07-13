<!-- login.php -->
<?php
require_once 'database.php';
require_once 'user.php';
require_once 'validate.php';

$db = new Database('localhost', 'diellidemjaha', '33-Tea-rks@', 'flashsnapdbreal');
$db->connect();
$connection = $db->getConnection();

$user = new User($db);
$validator = new Validator($db);

$email = $_POST['email'];
$password = $_POST['password'];

if ($validator->validateLogin($email, $password)) {
    $userID = $user->login($email, $password);
    if ($userID) {
        session_start();
        $_SESSION['user_id'] = $userID;
        header("Location: profile.php");
        exit();
    }
}

echo "Invalid email or password.";
?>