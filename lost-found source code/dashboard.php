<?php
// filepath: c:\xampp\htdocs\WP Project\lost-found-system\dashboard.php
session_start();

if (!isset($_SESSION['email']) && isset($_COOKIE['email']) && isset($_COOKIE['user_id'])) {
    $_SESSION['email'] = $_COOKIE['email'];
    $_SESSION['user_id'] = $_COOKIE['user_id'];
}

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit;
}

// You can set any variables you want to use in the template here
$user_email = $_SESSION['email'];

include 'dashboard.html.php';