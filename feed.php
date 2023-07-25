<?php
require_once 'database.php';

$db = new Database('localhost', 'diellidemjaha', '33-Tea-rks@', 'flashsnapdbreal');
$db->connect();
$connection = $db->getConnection();

// Fetch all the winning photos with corresponding subject and username
$winningPhotosQuery = "SELECT wp.photo_data, wp.subject, u.username, u.id AS user_id
                       FROM winning_photos wp
                       INNER JOIN users u ON u.id = wp.user_id";
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
?>

<!DOCTYPE html>
<html>

<head>
    <title>Feed</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
<?php include("header.php"); ?>
    <div class="feed">
            <div class="feed_title">

                <h1>Flash Time Winning Photos</h1>
            </div>
            <?php foreach ($winningPhotoData as $photoData) { ?>
                <div class="feed_body">
                    <!-- Display winning photos -->
                  
                            <a href="view_profile.php?user_id=<?php echo $photoData['user_id']; ?>">
                            <img src="data:image/jpeg;base64,<?php echo $photoData['photo_data']; ?>" alt="Winning Photo" />
                            </a>
                        <!-- Display the contest subject and username here -->
                            <p>Won the Subject: <?php echo $photoData['subject']; ?></p>
                            <p>Username:<a href="view_profile.php?user_id=<?php echo $photoData['user_id']; ?>"> <?php echo $photoData['username']; ?></a></p>
                     
                </div>
                <?php } ?>
    </div>
</body>

</html>