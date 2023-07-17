<!-- signup.php -->
<?php
require_once 'database.php';
require_once 'user.php';
require_once 'validate.php';


define("SAVED_DIRECTORY", "gallery/");
// C:\xampp\tmp

$db = new Database('localhost', 'diellidemjaha', '33-Tea-rks@', 'flashsnapdbreal');
$db->connect();
$connection = $db->getConnection();

$user = new User($db);
$validator = new Validator($db);

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$uploaded_file_tmp  = $_FILES['profile_pic']['tmp_name'];
$name = $_FILES["profile_pic"]["name"];
$saved_file_name = $name;
// $res = move_uploaded_file($profilePic, "$profilePicPath/$name");
move_uploaded_file($uploaded_file_tmp, SAVED_DIRECTORY . $saved_file_name);


$createdAt = $_POST['created_at'];

if ($validator->validateSignup($username, $email, $password, $uploaded_file_tmp, $createdAt)) {
    $userID = $user->signup($username, $email, $password, $uploaded_file_tmp, $createdAt);
    if ($userID) {
        session_start();
        $_SESSION['user_id'] = $userID;
        header("Location: profile.php");
        exit();
    }
}

echo "Invalid signup details.";
?>