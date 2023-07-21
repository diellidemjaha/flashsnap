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

session_start();
$userID = $_SESSION['user_id'];

// Get the current date and time in the MySQL datetime format
$currentDateTime = date('Y-m-d H:i:s');

// Query to select the contest_subject from the subjects table that is currently being voted
$activeSubjectQuery = "SELECT contest_subject FROM subjects
                       WHERE '$currentDateTime' BETWEEN starts_at AND ends_at";

$activeSubjectResult = mysqli_query($connection, $activeSubjectQuery);

if (!$activeSubjectResult) {
    die("Query failed: " . mysqli_error($connection));
}

$subjectData = mysqli_fetch_assoc($activeSubjectResult);

if ($subjectData) {
    $contestSubject = $subjectData['contest_subject'];
    echo "Currently Voting for: " . $contestSubject;
} else {
    echo "No active contest subject found for voting.";
    // You can add additional logic here, such as redirecting the user or displaying a message.
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['voted_photos'])) {
        $votedPhotos = $_POST['voted_photos'];

        foreach ($votedPhotos as $photoID) {
            // Insert the votes only if an active contest subject is found
            $query = "INSERT INTO votes (user_id, photo_id) VALUES ('$userID', '$photoID')";
            mysqli_query($connection, $query);
        }

        echo "Votes submitted successfully.";
    } else {
        echo "No photos selected.";
    }
}
?>
