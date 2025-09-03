<?php
require_once '../db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $user_id = $_SESSION['user_id'];
    // Only allow deleting your own items
    $conn->query("DELETE FROM found_items WHERE id=$id AND user_id=$user_id");
}
header("Location: search_found.php");
exit;
?>