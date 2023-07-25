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

    // Fetch winning photos for the specified user ID
    $winningPhotosQuery = "SELECT wp.photo_data, wp.subject, u.username, u.id AS user_id
                           FROM winning_photos wp
                           INNER JOIN users u ON u.id = wp.user_id
                           WHERE wp.user_id = '$profileUserID'";
    $winningPhotosResult = mysqli_query($connection, $winningPhotosQuery);

    if (!$winningPhotosResult) {
        die("Query failed: " . mysqli_error($connection));
    }

    // Create an array to store winning photo data
    $winningPhotoData = array();

    while ($photo = mysqli_fetch_assoc($winningPhotosResult)) {
        // Save the photo data in the array
        $winningPhotoData[] = array(
            'photo_data' => $photo['photo_data'],
            'subject' => $photo['subject'],
            'username' => $photo['username'],
            'user_id' => $photo['user_id']
        );
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
<?php include("header.php"); ?>
    <div class="profile">
        <h1>Welcome to <?php echo $usernameofuser; ?>'s Profile</h1>
        <?php
        // Construct the image URL
        $profilePicURL = 'gallery/' . $profilePicFilename;
        ?>
        <img src="<?php echo $profilePicURL; ?>" alt="Profile Picture" />
        <h2>Winning Photos</h2>
        <?php foreach ($winningPhotoData as $photoData) { ?>
            <div>
                <!-- Display winning photos -->
                <a href="view_profile.php?user_id=<?php echo $photoData['user_id']; ?>">
                    <img src="data:image/jpeg;base64,<?php echo $photoData['photo_data']; ?>" alt="Winning Photo" />
                </a>
                <!-- Display the subject of the winning photo here -->
                <p>Subject: <?php echo $photoData['subject']; ?></p>
            </div>
        <?php } ?>
    </div>
</body>
</html>
