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

$query = "SELECT photos.*, COUNT(votes.id) AS vote_count FROM photos
          LEFT JOIN votes ON votes.photo_id = photos.id
          GROUP BY photos.id
          ORDER BY vote_count DESC";
$result = mysqli_query($connection, $query);


if (!$result) {
    die("Query failed: " . mysqli_error($connection));
}

$currentDateTime = date('Y-m-d H:i:s');
$query_1 = "SELECT contest_subject FROM subjects
WHERE '$currentDateTime' BETWEEN created_at AND ends_at";

$subject_result = mysqli_query($connection, $query_1);

if (!$subject_result) {
    die("Query failed: " . mysqli_error($connection));
}

$subjectData = mysqli_fetch_assoc($subject_result);


?>

<!DOCTYPE html>
<html>
<head>
    <title>Rank</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="rank">


        <h1>Flash Time Ranking</h1>
 <?php       if ($subjectData) {
    $contestSubject = $subjectData['contest_subject'];
    echo "<h2>Currently Voting for: " . $contestSubject . '</h2>';
} else {
    echo "<h2>No active contest subject found.</h2>";
} ?>
        <table>
            <tr>
                <th>Photo</th>
                <th>Vote Count</th>
            </tr>
            <?php while ($photo = mysqli_fetch_assoc($result)) { ?>
                <tr>
                <td><img src="<?php echo $photo['image']; ?>" /> </td>
                    <td><?php echo $photo['vote_count']; ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>