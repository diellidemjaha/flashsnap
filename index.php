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
    <title>Welcome to FlashSnap</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <center> <?php
    if (isset($_SESSION['user_id'])) {
        // User is logged in, redirect to the profile page
        header("Location: profile.php");
        exit;
    } else {
        ?>

<h1>Welcome to FlashSnap</h1>
<h2>Login</h2>
<form action="login.php" method="POST">
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <input type="submit" value="Login">
</form>
<h2>Sign Up</h2>
<form action="signup.php" method="POST" enctype="multipart/form-data">
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <input type="file" name="profile_pic" required><br>
    <input type="hidden" name="created_at" value="<?php echo date('Y-m-d H:i:s'); ?>">
    <input type="submit" value="Sign Up">
</form>
<?php } ?>
    </center>
</body>
</html>