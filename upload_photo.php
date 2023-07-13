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

$existingPhoto = null;
// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if a subject is available
    if (!$subject) {
        die("No current subject available.");
    }

    // Check if the user has already uploaded an image for the current subject
    $userID = $_SESSION['user_id'];
    $existingPhotoQuery = "SELECT * FROM photos WHERE subject_id = '{$subject['id']}' AND user_id = '$userID' LIMIT 1";
    $existingPhotoResult = mysqli_query($connection, $existingPhotoQuery);
    $existingPhoto = mysqli_fetch_assoc($existingPhotoResult);

    if ($existingPhoto) {
        die("You have already uploaded an image for this subject.");
    }

    // Retrieve form data
    $subjectID = $subject['id'];

    // Retrieve and process the uploaded image file
    $imageFile = $_FILES['photo']['tmp_name'];
    $imagePath = 'flashsnaps/' . $_FILES['photo']['name'];
    move_uploaded_file($imageFile, $imagePath);

    // Insert the photo information into the database
    $insertQuery = "INSERT INTO photos (subject_id, user_id, image) VALUES ('$subjectID', '$userID', '$imagePath')";
    $insertResult = mysqli_query($connection, $insertQuery);

    if (!$insertResult) {
        die("Photo upload failed: " . mysqli_error($connection));
    }

    // Redirect the user to the voting page
    header("Location: profile.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Photo</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="upload-photo">
        <h1>Current Subject:</h1>

        <?php if ($subject) { ?>
            <h2><?php echo $subject['contest_subject']; ?></h2>

            <?php if ($existingPhoto) { ?>
                <p>You have already uploaded an image for this subject.</p>
            <?php } else { ?>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="subject_id" value="<?php echo $subject['id']; ?>">
                    <input type="file" name="photo" required>
                    <button type="submit">Upload Photo</button>
                </form>
            <?php } ?>
        <?php } else { ?>
            <p>No current subject available.</p>
        <?php } ?>
    </div>
</body>
</html>