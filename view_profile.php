<?php
require_once 'database.php';
require_once 'user.php';

$db = new Database('localhost', 'diellidemjaha', '33-Tea-rks@', 'flashsnapdbreal');
$db->connect();
$connection = $db->getConnection();

$user = new User($db);
define("SAVED_DIRECTORY", "gallery/");

// Check if the user_id parameter is present in the URL
if (isset($_GET['user_id'])) {
    $profileUserID = $_GET['user_id'];

    // Fetch user information for the specified user ID
    $query = "SELECT * FROM users WHERE id = '$profileUserID'";
    $userResult = mysqli_query($connection, $query);

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

    // Fetch winning photo filenames for the specified user ID
    $winningPhotosQuery = "SELECT photos.image, subjects.contest_subject 
                           FROM photos
                           INNER JOIN subjects ON subjects.id = photos.subject_id
                           INNER JOIN votes ON votes.photo_id = photos.id
                           WHERE photos.user_id = '$profileUserID' AND votes.is_winner = 1";
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
} else {
    // If the user_id parameter is not present, redirect to feed.php or any other page as needed
    header("Location: feed.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="profile">
        <h1>Welcome to <?php echo $usernameofuser; ?>'s Profile</h1>
        <?php
        // Construct the image URL
        $profilePicURL = 'gallery/' . $profilePicFilename;
        $winningPhotosDirectory = 'winning_photos/';
        ?>
        <img src="<?php echo $profilePicURL; ?>" alt="Profile Picture" />
        <h2>Winning Photos</h2>
        <?php foreach ($winningPhotoFilenames as $filename) { 

            $imageFilename = $filename; 
            $new_directory = explode("/", $imageFilename);
            $imagePath = $winningPhotosDirectory . $new_directory[1];
            ?>
            <div>
                <!-- Display winning photos -->
              <!--  -->
                <img src="<?php echo $imagePath; ?>" /> 
                <!-- You can also display the contest subject here if needed -->
            </div>
        <?php } ?>
    </div>
</body>
</html>
