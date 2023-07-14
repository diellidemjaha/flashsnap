<?php

require_once 'database.php';
require_once 'user.php';
require_once 'validate.php';

// Assuming you have a MySQL connection established already
$db = new Database('localhost', 'diellidemjaha', '33-Tea-rks@', 'flashsnapdbreal');
$db->connect();
$connection = $db->getConnection();

$user = new User($db);
$validator = new Validator($db);
//

session_start();
$userID = $_SESSION['user_id'];


// Fetch user information
$query = "SELECT * FROM users WHERE id = '$userID'";
$userResult = mysqli_query($connection, $query);

if (!$userResult) {
    die("Query failed: " . mysqli_error($connection));
}

$user = mysqli_fetch_assoc($userResult);

// Fetch user's photos and associated subjects
// $query_1 = "SELECT photos.*, subjects.subject FROM photos
//           INNER JOIN subjects ON subjects.id = photos.subject_id
//           WHERE photos.user_id = '$userID'";

$query_1 = "SELECT photos.*, subjects.contest_subject FROM photos
          INNER JOIN subjects ON subjects.id = photos.id
          WHERE photos.user_id = '$userID'";
$photoResult = mysqli_query($connection, $query_1);

if (!$photoResult) {
    die("Query failed: " . mysqli_error($connection));
}




// // Fetch user information
// $query = "SELECT * FROM users WHERE id = '$userID'";
// $userResult = mysqli_query($connection, $query);
// $user = mysqli_fetch_assoc($userResult);

// // Fetch user's photos and associated subjects
// $query = "SELECT photos.*, subjects.subject FROM photos
//           INNER JOIN subjects ON subjects.id = photos.subject_id
//           WHERE photos.user_id = '$userID'";
// $photoResult = mysqli_query($connection, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>FlashSnap Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="profile">


        <h1>Welcome, <?php echo $user['username']; ?></h1>
        <img width="100" height="auto" src="<?php echo $user['profile_pic']; ?>" alt="Profile Picture">
        <h2>Your Photos</h2>
        <?php while ($photo = mysqli_fetch_assoc($photoResult)) { ?>
            <div>
            <!-- <img height="auto" src="data:image/jpeg;base64,'. base64_encode($row['profile_pic']) .'"/> -->
            <img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($photo['image']); ?>" /> 
                <p>Subject: <?php echo isset($photo['contest_subject']) ? $photo['contest_subject'] : 'N/A'; ?></p>
    
            </div>
        <?php } ?>
        <h2>Navigation</h2>
        <ul>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="upload_photo.php">Submit a Flashsnap</a>
            <li><a href="vote.php">Vote</a></li>
            <li><a href="rank.php">Rank</a></li>
        </ul>
        <form action="logout.php" method="post">
        <button type="submit">Logout</button>
    </div>
</body>
</html>