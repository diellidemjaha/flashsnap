<?php
require_once 'database.php';
require_once 'user.php';

// Assuming you have a MySQL connection established already
$db = new Database('localhost', 'diellidemjaha', '33-Tea-rks@', 'flashsnapdbreal');
$db->connect();
$connection = $db->getConnection(); // Obtain the database connection instance

$user = new User($db);

session_start();
$userID = $_SESSION['user_id'];

// Check if the user has already voted 10 times
$voteCountQuery = "SELECT COUNT(*) AS vote_count FROM votes WHERE user_id = '$userID'";
$voteCountResult = mysqli_query($connection, $voteCountQuery);
$voteCount = mysqli_fetch_assoc($voteCountResult)['vote_count'];

if ($voteCount >= 10) {
    echo "<a href='profile.php'>Go to your Profile</a>";
    die("You have reached the maximum limit of 10 votes.");
}

// Fetch the subject for voting
$query = "SELECT * FROM subjects WHERE ends_at > NOW() ORDER BY created_at ASC LIMIT 1";
$subjectResult = mysqli_query($connection, $query);
$subject = mysqli_fetch_assoc($subjectResult);

if (!$subject) {
    die("No subject available for voting.");
}

// Fetch two random photos for voting from the subject
$photoQuery = "SELECT * FROM photos WHERE subject_id = '{$subject['id']}' ORDER BY RAND() LIMIT 2";
$photoResult = mysqli_query($connection, $photoQuery);

if (!$photoResult) {
    die("Query failed: " . mysqli_error($connection));
}

$photo1 = mysqli_fetch_assoc($photoResult);
$photo2 = mysqli_fetch_assoc($photoResult);

// Handle the vote submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['photo_id'])) {
        $imageID = $_POST['photo_id'];

        // Insert the vote into the votes table
        $insertQuery = "INSERT INTO votes (user_id, photo_id) VALUES ('$userID', '$imageID')";
        $insertResult = mysqli_query($connection, $insertQuery);

        if (!$insertResult) {
            die("Vote submission failed: " . mysqli_error($connection));
        }

        // Redirect the user back to the voting page
        header("Location: vote.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Vote</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Vote</h1>
    <h2>Subject: <?php echo $subject['contest_subject']; ?></h2>
    <?php if ($photo1 && $photo2) { ?>
        <div>
        <img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($photo1['image']); ?>" /> 
            <form method="POST">
                <input type="hidden" name="photo_id" value="<?php echo $photo1['id']; ?>">
                <button type="submit">Vote for Photo 1</button>
            </form>
        </div>
        <div>
        <img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($photo2['image']); ?>" /> 
            <form method="POST">
                <input type="hidden" name="photo_id" value="<?php echo $photo2['id']; ?>">
                <button type="submit">Vote for Photo 2</button>
            </form>
        </div>
    <?php } else { ?>
        <p>No more photos available for voting.</p>
        <a href="profile.php">Go to profile</a>
    <?php } ?>
</body>
</html>