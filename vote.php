<?php
require_once 'database.php';
session_start();

// Assuming you have a MySQL connection established already
$db = new Database('localhost', 'diellidemjaha', '33-Tea-rks@', 'flashsnapdbreal');
$db->connect();
$connection = $db->getConnection(); // Obtain the database connection instance

// Fetch the current subject
$currentTime = date('Y-m-d H:i:s');
$query = "SELECT * FROM subjects WHERE created_at <= '$currentTime' AND ends_at >= '$currentTime' LIMIT 1";
$subjectResult = mysqli_query($connection, $query);
$subject = mysqli_fetch_assoc($subjectResult);

// Check if a subject is available
if (!$subject) {
    die("No current subject available.");
}

// Check if the user has reached the maximum limit of votes
$userID = $_SESSION['user_id'];
$voteCountQuery = "SELECT COUNT(*) AS vote_count FROM votes WHERE user_id = '$userID'";
$voteCountResult = mysqli_query($connection, $voteCountQuery);
$voteCountData = mysqli_fetch_assoc($voteCountResult);
$voteCount = $voteCountData['vote_count'];

if ($voteCount >= 10) {
    die("You have reached the maximum limit of 10 votes.");
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $photoID = $_POST['photo_id'];

    // Insert the vote into the database
    $insertQuery = "INSERT INTO votes (user_id, photo_id) VALUES ('$userID', '$photoID')";
    $insertResult = mysqli_query($connection, $insertQuery);

    if (!$insertResult) {
        die("Vote failed: " . mysqli_error($connection));
    }

    // Increase the vote count for the user
    $voteCount++;
    if ($voteCount >= 10) {
        die("You have reached the maximum limit of 10 votes.");
    }

    // Update the vote count in the database
    $updateCountQuery = "UPDATE users SET vote_count = '$voteCount' WHERE id = '$userID'";
    $updateCountResult = mysqli_query($connection, $updateCountQuery);

    if (!$updateCountResult) {
        die("Vote count update failed: " . mysqli_error($connection));
    }

    // Redirect the user back to the voting page
    header("Location: vote.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Vote</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="vote">
        <h1>Current Subject: <?php echo $subject['contest_subject']; ?></h1>

        <h2>Choose your favorite photo:</h2>

        <?php
        $photosQuery = "SELECT * FROM photos WHERE subject_id = '{$subject['id']}' ORDER BY RAND() LIMIT 2";
        $photosResult = mysqli_query($connection, $photosQuery);

        if ($photosResult && mysqli_num_rows($photosResult) > 0) {
            while ($photo = mysqli_fetch_assoc($photosResult)) {
                ?>
                <div>
                <img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($photo['image']); ?>" /> 
                    <form method="POST">
                        <input type="hidden" name="photo_id" value="<?php echo $photo['id']; ?>">
                        <button type="submit">Vote</button>
                    </form>
                </div>
                <?php
            }
        } else {
            echo "No photos available for voting.";
        }
        ?>
    </div>
</body>
</html>