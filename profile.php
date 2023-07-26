<?php
require_once 'database.php';
require_once 'user.php';
require_once 'validate.php';

$db = new Database('localhost', 'diellidemjaha', '33-Tea-rks@', 'flashsnapdbreal');
$db->connect();
$connection = $db->getConnection();

$user = new User($db);
$validator = new Validator($db, $user);
define("SAVED_DIRECTORY", "gallery/");
// ...
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


// if (isset($_GET['user_id'])) {
//     $profileUserID = $_GET['user_id'];
//     echo "Profile User ID: " . $profileUserID . "<br>";
// } else {
//     echo "No Profile User ID specified in the URL.<br>";
// }

// Debugging: Check the logged-in user ID
// session_start();
// if (isset($_SESSION['user_id'])) {
//     $loggedInUserID = $_SESSION['user_id'];
//     echo "Logged-In User ID: " . $loggedInUserID . "<br>";
// } else {
//     echo "No Logged-In User ID found in the session.<br>";
// }
?>
<?php

//
$userID = $_SESSION['user_id'];
$userObj = new User($db);

// Fetch the user ID from the URL if it exists
// $profileUserID = $_GET['user_id'] ?? $_SESSION['user_id'];

// Fetch user information for the specified user ID
$query = "SELECT * FROM users WHERE id = '$userID'";
$userResult = mysqli_query($connection, $query);


// Fetch user information
// $query = "SELECT * FROM users WHERE id = '$userID'";
// $userResult = mysqli_query($connection, $query);

if (!$userResult) {
    die("Query failed: " . mysqli_error($connection));
}

if (mysqli_num_rows($userResult) > 0) {
    $userData = mysqli_fetch_assoc($userResult);
    $profilePicFilename = $userData['profile_pic'];
    $usernameofuser = $userData['username'];
} else {
    die("No user data found.");
}

$userData = mysqli_fetch_assoc($userResult);

// Debug output to check if user data is fetched correctly
// var_dump($userData);

$userData = mysqli_fetch_assoc($userResult);
// $profilePicFilename = $userData['profile_pic'];

$userData = mysqli_fetch_assoc($userResult);

$profilePicFilename = $userObj->getProfilePicFilename($userID);
$newProfilePicFilename = $_SESSION['profile_pic'] ?? null;
// var_dump($profilePicFilename);
// var_dump($newProfilePicFilename);

// Fetch winning photo filenames
$winningPhotosQuery = "SELECT photo_data, subject FROM winning_photos WHERE user_id = '$userID'";
$winningPhotosResult = mysqli_query($connection, $winningPhotosQuery);

if (!$winningPhotosResult) {
    die("Query failed: " . mysqli_error($connection));
}

// Create an array to store winning photo filenames
$winningPhotoFilenames = array();

while ($photo = mysqli_fetch_assoc($winningPhotosResult)) {
    // Save the filenames in the array
     // Save the photo data in the array
     $winningPhotoFilenames[] = array(
        'photo_data' => $photo['photo_data'],
        'subject' => $photo['subject'],
        // 'username' => $photo['username'],
        // 'user_id' => $photo['user_id']
    );
}
//  else {
// // If the user_id parameter is not present, redirect to feed.php or any other page as needed
// header("Location: feed.php");
// exit();
// }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Flash Time Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include("header.php"); ?>
    <div class="profile">

        <h1>Welcome, <?php echo $usernameofuser; ?></h1>
        <?php
        // Construct the image URL
        // Construct the image URL

//         var_dump($newProfilePicFilename);
// var_dump($profilePicFilename);
$profilePicURL = 'gallery/' . (isset($newProfilePicFilename) ? $newProfilePicFilename : $profilePicFilename);
$winningPhotosDirectory = 'winning_photos/';
// var_dump($profilePicURL);
        // var_dump($profilePicURL);
        ?>
        <img src="<?php echo $profilePicURL; ?>" alt="Profile Picture" />
        <h2>Your Winning Photos</h2>
     
        <?php foreach ($winningPhotoFilenames as $photoData) { ?>
            <div>
                <!-- Display winning photos -->
                    <img src="data:image/jpeg;base64,<?php echo $photoData['photo_data']; ?>" alt="Winning Photo" />
                <!-- Display the subject of the winning photo here -->
                <p>Subject: <?php echo $photoData['subject']; ?></p>
            </div>
        <?php } ?>

        <h2>Navigation</h2>
        <ul>
            <li><a href="feed.php">Flash Time Feed</a></li>
            <li><a href="upload_photo.php">Submit a Flash Time</a></li>
            <li><a href="vote.php">Vote</a></li>
            <li><a href="rank.php">Rank</a></li>
        </ul>

        <form action="update_profile.php" method="post">
            <button type="submit">Update Profile</button>
        </form><br>
        
        <form action="logout.php" method="post">
            <button type="submit">Logout</button>
        </form>
    </div>
</body>
</html>
