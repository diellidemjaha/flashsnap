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

//

//
$winningPhotosDirectory = 'winning_photos/';


// Fetch the winners' data from the database
$winnersQuery = "SELECT users.id AS user_id, users.username, photos.image 
                FROM users
                INNER JOIN photos ON users.id = photos.user_id
                INNER JOIN votes ON photos.id = votes.photo_id
                WHERE votes.is_winner = 1";
$winnersResult = mysqli_query($connection, $winnersQuery);

if (!$winnersResult) {
    die("Query failed: " . mysqli_error($connection));
}

$winners = mysqli_fetch_all($winnersResult, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Feed</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="feed">
        <h1>Flash Time Feed</h1>

        <h2>Winners</h2>
        <?php foreach ($winners as $winner) { 
            $imageFilename = $winner['image']; 
            $new_directory = explode("/", $imageFilename);
            $imagePath = $winningPhotosDirectory . $new_directory[1]
            

    // if (file_exists($winningPhotosDirectory . $new_directory[1]))
    //     continue;
            
            
            ?>
            <div>
                <a href="view_profile.php?user_id=<?php echo $winner['user_id']; ?>">
                    <?php echo $winner['username']; ?>
                </a>
                <img height="200" length="auto" src="<?php echo $imagePath; ?>"/>
            </div>
        <?php } ?>

        <h2>Navigation</h2>
        <ul>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="upload_photo.php">Submit a Flash Time</a></li>
            <li><a href="vote.php">Vote</a></li>
            <li><a href="rank.php">Rank</a></li>
        </ul>

        <form action="logout.php" method="post">
            <button type="submit">Logout</button>
        </form>
    </div>
</body>
</html>
