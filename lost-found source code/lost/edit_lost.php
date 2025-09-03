<?php
require '../db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch item
$item = $conn->query("SELECT * FROM lost_items WHERE id=$id AND user_id=$user_id")->fetch_assoc();
if (!$item) {
    header("Location: view_lost.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $location = $conn->real_escape_string($_POST['location']);
    $conn->query("UPDATE lost_items SET name='$name', description='$description', location='$location' WHERE id=$id AND user_id=$user_id");
    header("Location: view_lost.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Lost Item</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<h2>Edit Lost Item</h2>
<form method="POST">
    <input type="text" name="name" value="<?= htmlspecialchars($item['name']) ?>" required>
    <textarea name="description" required><?= htmlspecialchars($item['description']) ?></textarea>
    <input type="text" name="location" value="<?= htmlspecialchars($item['location']) ?>" required>
    <button type="submit" class="btn">Update</button>
    <a href="view_lost.php" class="btn">Cancel</a>
</form>
</body>
</html>