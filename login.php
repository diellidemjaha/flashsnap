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


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($validator->validateLogin($email, $password)) {
        $userID = $user->login($email, $password);
        if ($userID) {
            session_start();
            $_SESSION['user_id'] = $userID;
            // $_SESSION['loggedin'] = true;
            header("Location: profile.php");
            exit();
        }
    }
    // Validate the login credentials and check against the database
    // ...
    echo "Invalid email or password.";
} else {
    header("Location index.php");
}
// $email = $_POST['email'];
// $password = $_POST['password'];


?>