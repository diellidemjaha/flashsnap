<?php
require_once 'database.php';

// Assuming you have a MySQL connection established already
$db = new Database('localhost', 'diellidemjaha', '33-Tea-rks@', 'flashsnapdbreal');
$db->connect();
$connection = $db->getConnection();

// Get the subject IDs of all subjects that have at least one photo
$subjectIDsQuery = "SELECT DISTINCT subject_id FROM photos";
$subjectIDsResult = mysqli_query($connection, $subjectIDsQuery);

if (!$subjectIDsResult) {
    die("Query failed: " . mysqli_error($connection));
}

while ($subject = mysqli_fetch_assoc($subjectIDsResult)) {
    $subjectID = $subject['subject_id'];

    // Find the most voted photo for this subject
    $mostVotedQuery = "SELECT photo_id, COUNT(id) AS vote_count FROM votes
                      WHERE photo_id IN (SELECT id FROM photos WHERE subject_id = '$subjectID')
                      GROUP BY photo_id
                      ORDER BY vote_count DESC
                      LIMIT 1";
    $mostVotedResult = mysqli_query($connection, $mostVotedQuery);

    if ($mostVotedResult && mysqli_num_rows($mostVotedResult) > 0) {
        $mostVotedData = mysqli_fetch_assoc($mostVotedResult);
        $mostVotedPhotoID = $mostVotedData['photo_id'];

        // First, update the is_winner column for all photos of the subject to 0 (false)
        $updateQuery = "UPDATE votes SET is_winner = 0 WHERE photo_id IN (
            SELECT id FROM photos WHERE subject_id = '$subjectID'
        )";
        mysqli_query($connection, $updateQuery);

        // Next, update the is_winner column to 1 (true) for the most voted photo
        $updateWinnerQuery = "UPDATE votes SET is_winner = 1 WHERE photo_id = '$mostVotedPhotoID'";
        mysqli_query($connection, $updateWinnerQuery);
    }
}

echo "Winners determined successfully.";
?>
