<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $location = $conn->real_escape_string($_POST['location']);
    $photo = '';
    
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["photo"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Check if image file is actual image
        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if ($check !== false) {
            // Generate unique filename
            $photo = uniqid() . '.' . $imageFileType;
            move_uploaded_file($_FILES["photo"]["tmp_name"], $target_dir . $photo);
        }
    }
    
    $sql = "INSERT INTO lost_items (user_id, name, description, location, photo) 
            VALUES ('{$_SESSION['user_id']}', '$name', '$description', '$location', '$photo')";
    
    if ($conn->query($sql)) {
        $_SESSION['message'] = "Lost item reported successfully!";
        header("Location: view_lost.php");
        exit;
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Report Lost Item</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h2>Report Lost Item</h2>
        <a href="../dashboard.html" class="back">Back to Dashboard</a>
        
        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Item Name" required>
            <textarea name="description" placeholder="Description" required></textarea>
            <input type="text" name="location" placeholder="Last Seen Location" required>
            <label>Photo (optional): <input type="file" name="photo"></label>
            <button type="submit">Report Lost Item</button>
        </form>
    </div>
</body>
</html>