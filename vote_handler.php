<!-- vote_handler.php -->
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['voted_photos'])) {
        $votedPhotos = $_POST['voted_photos'];

        foreach ($votedPhotos as $photoID) {
            $query = "INSERT INTO votes (user_id, photo_id) VALUES ('$userID', '$photoID')";
            mysqli_query($connection, $query);
        }

        echo "Votes submitted successfully.";
    } else {
        echo "No photos selected.";
    }
}
?>