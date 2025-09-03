<?php
session_start();
require_once 'db.php';

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Check if email exists
    $check = $conn->query("SELECT id FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        header("Location: register.php?error=exists");
        exit;
    }
    
    // Insert new user
    if ($conn->query("INSERT INTO users (email, password) VALUES ('$email', '$password')")) {
        header("Location: login.php?registered=1");
        exit;
    } else {
        header("Location: register.php?error=db");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - Lost & Found</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container auth-container">
        <h2>Register</h2>
        <?php
            if (isset($_GET['error']) && $_GET['error'] == 'exists'): 
        ?>
        <div class="error">Email already registered</div>
        <?php
            endif;
        ?>
        <?php
            if (isset($_GET['error']) && $_GET['error'] == 'db'): ?>
            <div class="error">Database error. Please try again.</div>
        <?php
            endif;
        ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>