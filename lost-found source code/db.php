<?php
// Database connection
$host = 'localhost';
$user = 'group3';
$password = 'group3';
$dbname = 'lost_found_db';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/*$sql = "CREATE DATABASE lost_found_db";
if (mysqli_query($conn, $sql)) {
  echo "Database created successfully";
} else {
  echo "Error creating database: " . mysqli_error($conn);
}

mysqli_close($conn);
*/

?>