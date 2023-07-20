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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Rank</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="rank">


        <h1>Ranking</h1>
        <table>
            <tr>
                <th>Image</th>
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