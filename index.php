<?php require_once "database.php"; 

// $db = new Database('localhost', 'diellidemjaha', '33-Tea-rks@', 'flashsnapdbreal');

// // Attempt to connect to the database
// $db->connect();

// // Check if the connection was successful
// if ($db->getConnection()) {
//     echo "Database connection successful!";
// } else {
//     echo "Failed to connect to the database.";
// }

session_start();

?>


<html>
<head>
    <title>Welcome to Flash Time</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</head>
<body>
    <center> <?php
    if (isset($_SESSION['user_id'])) {
        // User is logged in, redirect to the profile page
        header("Location: profile.php");
        exit;
    } else {
        ?>
 <?php include("header.php"); ?>
<!-- <h1>Welcome to Flash Time</h1> -->
<h2>Login</h2>
<form action="login.php" method="POST">
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <input type="submit" class="submit-button" value="Login">
</form>
<h2>Sign Up</h2>
<form action="signup.php" method="POST" enctype="multipart/form-data">
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <label for="profile_pic"><b>Upload your profile picture:</b></label>
    <input type="file" name="profile_pic" required><br>
    <input type="hidden" name="created_at" value="<?php echo date('Y-m-d H:i:s'); ?>">
    <br><br><input type="submit" class="submit-button" value="Sign Up">
</form>
<?php } ?>
    </center>
</body>
</html>