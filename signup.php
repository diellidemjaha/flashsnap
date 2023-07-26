<?php

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'database.php';
require_once 'user.php';
require_once 'validate.php';

define("SAVED_DIRECTORY", "gallery/");

$db = new Database('localhost', 'diellidemjaha', '33-Tea-rks@', 'flashsnapdbreal');
$db->connect();
$connection = $db->getConnection();

$user = $db->getUser();
$validator = new Validator($db, $user);
// $validator = new Validator($db, $user);



$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$profilePic = $_FILES['profile_pic'];

$createdAt = $_POST['created_at'];
$extension = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
$newFilename = $username . '_' . time() . '.' . $extension;
$destination = SAVED_DIRECTORY . $newFilename;

if ($validator->validateSignup($username, $email, $password, $newFilename, $createdAt, $user)) {
    echo "Validation passed.<br>";


    if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $destination)) {
        echo "File uploaded successfully.<br>";
        try {
            $userID = $user->signup($username, $email, $password, $newFilename, $createdAt);
            if ($userID) {
                echo "User created successfully.<br>";
                session_start();
                $_SESSION['user_id'] = $userID;
                $_SESSION['profile_pic'] = $newFilename; // Store the new profile pic filename in session
                header("Location: profile.php");
                exit();
            } else {
                echo "Error creating user.<br>";
            }
        } catch (Exception $e) {
            echo "Error creating user: " . $e->getMessage() . "<br>";
        }
    } else {
        echo "Error uploading profile picture.<br>";
    }
} else {
    echo "Invalid signup details. The following errors were encountered:<br>";
    foreach ($validator->getErrors() as $error) {
        echo $error . "<br>";
    }
}
?>
