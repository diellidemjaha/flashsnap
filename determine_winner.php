<?php
require_once 'database.php';

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

        // Fetch the photo data for the most voted photo
        $photoDataQuery = "SELECT image FROM photos WHERE id = '$mostVotedPhotoID'";
        $photoDataResult = mysqli_query($connection, $photoDataQuery);

        if ($photoDataResult && mysqli_num_rows($photoDataResult) > 0) {
            $filenameData = mysqli_fetch_assoc($photoDataResult);
            $imageFileName = $filenameData['image'];
            $winningPhotosDirectory = 'flashsnaps/';

            // Construct the final image file path
            $new_directory = explode("/", $imageFileName);
            $decodedImageFileName = str_replace('%20', ' ', $new_directory[1]);
            $imagePath = $winningPhotosDirectory . $decodedImageFileName;
            // var_dump($imagePath);
            // Check if the file exists before attempting to read it
            if (file_exists($imagePath)) {
                // Read the image file and convert it to base64
                $image_base64 = base64_encode(file_get_contents($imagePath));
                $image_data = 'data:image/jpeg;base64,' . $image_base64;
                // var_dump($image_base64);
                    // Get the user ID who posted the most voted photo
                    $userIDQuery = "SELECT user_id FROM photos WHERE id = '$mostVotedPhotoID'";
                    $userIDResult = mysqli_query($connection, $userIDQuery);

                    if ($userIDResult && mysqli_num_rows($userIDResult) > 0) {
                        $userID = mysqli_fetch_assoc($userIDResult)['user_id'];

                        // Fetch the subject from the subjects table
                        $subjectQuery = "SELECT contest_subject FROM subjects WHERE id = '$subjectID'";
                        $subjectResult = mysqli_query($connection, $subjectQuery);
                        $subjectData = mysqli_fetch_assoc($subjectResult);
                        $subject = $subjectData['contest_subject'];

                        // Fetch the username from the users table
                        $usernameQuery = "SELECT username FROM users WHERE id = '$userID'";
                        $usernameResult = mysqli_query($connection, $usernameQuery);
                        $usernameData = mysqli_fetch_assoc($usernameResult);
                        $username = $usernameData['username'];

                        // Insert the winning photo into the winning_photos table
                        $insertQuery = "INSERT INTO winning_photos (subject, username, user_id, photo_id, photo_data) 
                        VALUES ('$subject', '$username', '$userID', '$mostVotedPhotoID', '$image_base64')";
        
        if (mysqli_query($connection, $insertQuery)) {
            echo "Insertion successful!";
        } else {
            echo "Error: " . mysqli_error($connection);
        }
                    } else {
                        echo "Failed to get user ID for photo ID: $mostVotedPhotoID";
                    }
                } else {
                    echo "Failed to read the image file: $imagePath";
                }
            } else {
                echo "Image file not found: $imagePath";
            }
        } else {
            echo "No result found for the given photo ID: $mostVotedPhotoID";
        }}
    // } else {
    //     echo "No result found for the given subject ID: $subjectID";
    // }

echo "Winners determined and photos inserted into winning_photos table successfully.";
