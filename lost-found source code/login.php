<?php
session_start();
require_once 'db.php';

$error = '';
if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check email and password directly
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=? AND password=?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        
        // If "Remember Me" is checked, set cookies for 30 days
        if (isset($_POST['remember'])) {
            setcookie('user_id', $user['id'], time() + (86400 * 30), "/");
            setcookie('email', $user['email'], time() + (86400 * 30), "/");
        }

        header("Location: dashboard.html");
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container auth-container">
        <h2>Login</h2>
        <?php if (!empty($error)): ?>
            <div class="error" style="color:#d9534f; margin-bottom:10px;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['registered'])): ?>
            <div class="success" style="color:#5cb85c; margin-bottom:10px;">Registration successful! Please login.</div>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
            <label>
                <input type="checkbox" name="remember"> Remember Me
            </label>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>