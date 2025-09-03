<?php
require '../db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $user_id = $_SESSION['user_id'];
    // Only allow deleting your own items
    $conn->query("DELETE FROM lost_items WHERE id=$id AND user_id=$user_id");
}
header("Location: view_lost.php");
exit;
?>