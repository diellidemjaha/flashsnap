<?php
require_once 'database.php';

// Assuming you have a MySQL connection established already
$db = new Database('localhost', 'diellidemjaha', '33-Tea-rks@', 'flashsnapdbreal');
$db->connect();
$connection = $db->getConnection(); // Obtain the database connection instance

// Calculate the datetime 24 hours ago
$expiryTime = date('Y-m-d H:i:s', strtotime('-24 hours'));

// Delete expired subjects
$deleteQuery = "DELETE FROM subjects WHERE ends_at <= '$expiryTime'";
$deleteResult = mysqli_query($connection, $deleteQuery);

if (!$deleteResult) {
    die("Deletion failed: " . mysqli_error($connection));
}

$deletedRowCount = mysqli_affected_rows($connection);
echo "Deleted $deletedRowCount expired subject(s).";
?>