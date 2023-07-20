<?php
require_once 'database.php';
require_once 'user.php';
require_once 'validate.php';

$db = new Database('localhost', 'diellidemjaha', '33-Tea-rks@', 'flashsnapdbreal');
$db->connect();
$connection = $db->getConnection();

$user = new User($db);
$validator = new Validator($db);
define("SAVED_DIRECTORY", "gallery/");
// ...
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['user_id'];
$userObj = new User($db);

// Fetch user information
$query = "SELECT * FROM users WHERE id = '$userID'";
$userResult = mysqli_query($connection, $query);

if (!$userResult) {
    die("Query failed: " . mysqli_error($connection));
}

$userData = mysqli_fetch_assoc($userResult);

$profilePicFilename = $userObj->getProfilePicFilename($userID);
$newProfilePicFilename = $_SESSION['profile_pic'] ?? null;
// var_dump($profilePicFilename);
// var_dump($newProfilePicFilename);

// Fetch winning photo filenames
$winningPhotosQuery = "SELECT photos.image, subjects.contest_subject 
                       FROM photos
                       INNER JOIN subjects ON subjects.id = photos.subject_id
                       INNER JOIN votes ON votes.photo_id = photos.id
                       WHERE photos.user_id = '$userID' AND votes.is_winner = 1";
$winningPhotosResult = mysqli_query($connection, $winningPhotosQuery);

if (!$winningPhotosResult) {
    die("Query failed: " . mysqli_error($connection));
}

// Create an array to store winning photo filenames
$winningPhotoFilenames = array();

while ($photo = mysqli_fetch_assoc($winningPhotosResult)) {
    // Save the filenames in the array
    $winningPhotoFilenames[] = $photo['image'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Flash Time Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="profile">
        <!-- ... (other profile content) ... -->

        <h1>Welcome, <?php echo $userData['username']; ?></h1>
        <?php
        // Construct the image URL
        $profilePicURL = 'gallery/' . ($newProfilePicFilename ?? $profilePicFilename);
        // var_dump($profilePicURL);
        ?>
        <img src="<?php echo $profilePicURL; ?>" alt="Profile Picture" />
        <h2>Your Winning Photos</h2>
        <?php foreach ($winningPhotoFilenames as $filename) { ?>
            <div>
                <!-- Display winning photos -->
                <img src="gallery/<?php echo $filename; ?>" /> 
                <!-- You can also display the contest subject here if needed -->
            </div>
        <?php } ?>

        <h2>Navigation</h2>
        <ul>
            <li><a href="profile.php">Profile</a></li>
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
