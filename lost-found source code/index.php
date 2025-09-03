<?php 
require_once 'db.php'; 
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Lost & Found</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container auth-container">
        <h2>Login</h2>
        <?php
            if (isset($_GET['error'])):
        ?>
        <div class="error">Invalid email or password</div>
        <?php
            endif;
        ?>
        <?php
            if (isset($_GET['registered'])):
        ?>
        <div class="success">Registration successful! Please login.</div>
        <?php
            endif;
        ?>

        <form action="login.php" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>