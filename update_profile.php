<?php

require_once 'database.php';
require_once 'user.php';
require_once 'validate.php';

$db = new Database('localhost', 'diellidemjaha', '33-Tea-rks@', 'flashsnapdbreal');
$db->connect();
$connection = $db->getConnection();

$user = new User($db);
$validator = new Validator($db);

session_start();
$userID = $_SESSION['user_id'];

if (isset($_POST['submit'])) {
    $newUsername = $_POST['new_username'];
    $newPassword = $_POST['new_password'];
    $newProfilePic = $_FILES['new_profile_pic']['name']; // Get the filename, not the temporary path

    // Generate a unique filename for the uploaded profile picture
    $extension = pathinfo($newProfilePic, PATHINFO_EXTENSION);
    $newFilename = $newUsername . '_' . time() . '.' . $extension;
    $destination = 'gallery/' . $newFilename;
    move_uploaded_file($_FILES['new_profile_pic']['tmp_name'], $destination);

    // Perform validation and update the user profile
    if ($validator->validateProfileUpdate($newUsername, $newPassword, $newFilename)) {
        // Update the user's profile
        $user->updateUsername($userID, $newUsername);
        $user->updatePassword($userID, $newPassword);
        // $user->updateProfilePic($userID, $newFilename);
        if ($newProfilePic !== '') {
            $user->updateProfilePic($userID, $newFilename);
            // Store the new profile pic filename in session
            $_SESSION['profile_pic'] = $newFilename;
        }
        // Store the new profile pic filename in session
        $_SESSION['profile_pic'] = $newFilename;

        // Redirect the user back to the profile page
        header("Location: profile.php");
        exit();
    } else {
        echo "Invalid profile update data.";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="profile">
        <h1>Update Profile</h1>
        <form action="update_profile.php" method="post" enctype="multipart/form-data">
            <label for="new_username">New Username:</label>
            <input type="text" name="new_username" id="new_username"><br>
            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" id="new_password"><br>
            <label for="new_profile_pic">New Profile Picture:</label>
            <input type="file" name="new_profile_pic" id="new_profile_pic"><br><br><br>
            <input type="submit" name="submit" class="submit-button" value="Update">
        </form>
    </div>
</body>
</html>