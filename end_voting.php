<?php
// Your database and connection setup
require_once 'database.php';

$db = new Database('localhost', 'diellidemjaha', '33-Tea-rks@', 'flashsnapdbreal');
$db->connect();
$connection = $db->getConnection();

// Assuming you have the subject ID for which voting has ended
$subjectID = $_POST['subject_id']; // Adjust the way you get the subject ID based on your application's logic

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

    // Fetch the photo data for the most voted photo
    $photoDataQuery = "SELECT image, user_id FROM photos WHERE id = '$mostVotedPhotoID'";
    $photoDataResult = mysqli_query($connection, $photoDataQuery);

    if ($photoDataResult && mysqli_num_rows($photoDataResult) > 0) {
        $photoData = mysqli_fetch_assoc($photoDataResult);
        $imageDataEncoded = base64_encode(file_get_contents($photoData['image']));
        $userID = $photoData['user_id'];

        // Get the subject name
        $subjectNameQuery = "SELECT contest_subject FROM subjects WHERE id = '$subjectID'";
        $subjectNameResult = mysqli_query($connection, $subjectNameQuery);

        if ($subjectNameResult && mysqli_num_rows($subjectNameResult) > 0) {
            $subjectName = mysqli_fetch_assoc($subjectNameResult)['contest_subject'];

            // Insert the winning photo into the winning_photos table
            $insertQuery = "INSERT INTO winning_photos (subject_id, user_id, photo_id, photo_data) 
                            VALUES ('$subjectID', '$userID', '$mostVotedPhotoID', '$imageDataEncoded')";
            mysqli_query($connection, $insertQuery);

            // Additional debug message to display the fetched data (you can remove this in production)
            echo "Subject: $subjectName - User ID: $userID - Most Voted Photo ID: $mostVotedPhotoID - Photo Data: $imageDataEncoded<br>";
        }
    }
}

// You can add any additional logic or redirection after determining the winner
// For example, redirect back to the feed.php page or display a success message
header("Location: feed.php");
exit();
?>
