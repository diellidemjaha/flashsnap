<!-- signup.php -->
<?php
require_once 'database.php';
require_once 'user.php';
require_once 'validate.php';

$db = new Database('localhost', 'diellidemjaha', '33-Tea-rks@', 'flashsnapdbreal');
$db->connect();
$connection = $db->getConnection();

$user = new User($db);
$validator = new Validator($db);

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$profilePic = $_FILES['profile_pic']['tmp_name'];
$profilePicPath = 'profile_pic/' . $_FILES['profile_pic']['name'];
    move_uploaded_file($imageFile, $profilePicPath);
$createdAt = $_POST['created_at'];

if ($validator->validateSignup($username, $email, $password, $profilePicPath, $createdAt)) {
    $userID = $user->signup($username, $email, $password, $profilePicPath, $createdAt);
    if ($userID) {
        session_start();
        $_SESSION['user_id'] = $userID;
        header("Location: profile.php");
        exit();
    }
}

echo "Invalid signup details.";
?>