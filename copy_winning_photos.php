<?php
require_once 'database.php';
require_once 'user.php';
require_once 'validate.php';

$db = new Database('localhost', 'diellidemjaha', '33-Tea-rks@', 'flashsnapdbreal');
$db->connect();
$connection = $db->getConnection();

$user = new User($db);
$validator = new Validator($db);

// Fetch winning photo filenames
$winningPhotosQuery = "SELECT photos.image, subjects.contest_subject 
                       FROM photos
                       INNER JOIN subjects ON subjects.id = photos.subject_id
                       INNER JOIN votes ON votes.photo_id = photos.id
                       WHERE votes.is_winner = 1
                       ORDER BY votes.photo_vote DESC ";
// LIMIT 10";  Change '10' to the desired number of winning photos to retrieve
$winningPhotosResult = mysqli_query($connection, $winningPhotosQuery);

if (!$winningPhotosResult) {
    die("Query failed: " . mysqli_error($connection));
}

// Create an array to store winning photo filenames
$winningPhotoFilenames = array();

while ($photo = mysqli_fetch_assoc($winningPhotosResult)) {
    // Save the filenames in the array
    $winningPhotoFilenames[] = $photo['image'];
}

$sourceDirectory = 'flashsnaps/'; // Update this to the correct source directory path
$destinationDirectory = 'winning_photos/'; // Update this to the correct destination directory path


// rename('flashsnaps/BeFunky-collage-19-9.jpg', 'winning_photos/BeFunky-collage-19-9.jpg');


var_dump($winningPhotoFilenames);


foreach ($winningPhotoFilenames as $filename) {
    // $sourcePath = $sourceDirectory . $filename;
    // $destinationPath = $destinationDirectory . $filename;


    if (!file_exists($filename))
        continue;

    $new_directory = explode("/", $filename);

    if (file_exists('winning_photos/' . $new_directory[1]))
        continue;
    rename($filename, 'winning_photos/' . $new_directory[1]);


    // echo 'source path:' . var_dump($sourcePath) . '<br>';
    // echo 'destination path:' . var_dump($destinationPath) . '<br>';

    // Check if the source file exists before attempting to move it
    // if (file_exists($sourcePath)) {
    //     // Check if the destination file already exists
    //     if (file_exists($destinationPath)) {
    //         echo "Destination file '$filename' already exists. Skipping...<br>";
    //     } else {
    //         // Move the file from the gallery folder to the winning_photos folder
    //         if (rename($sourcePath, $destinationPath)) {
    //             echo "Winning photo '$filename' saved successfully.<br>";
    //         } else {
    //             echo "Failed to save the winning photo '$filename'.<br>";
    //         }
    //     }
    // } else {
    //     echo "Source file '$filename' does not exist.<br>";
    //     // Let's check the actual paths to debug the issue
    //     echo "Source path: $sourcePath<br>";
    //     echo "Destination path: $destinationPath<br>";
    // }
}
