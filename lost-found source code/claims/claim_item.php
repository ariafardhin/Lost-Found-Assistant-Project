<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

if (isset($_POST['item_id']) && isset($_POST['item_type'])) {
    $item_id = (int)$_POST['item_id'];
    $item_type = $_POST['item_type'] == 'lost' ? 'lost' : 'found';
    $message = isset($_POST['message']) ? $conn->real_escape_string($_POST['message']) : '';
    
    // Check if item exists
    $sql = "SELECT user_id FROM {$item_type}_items WHERE id=$item_id";
    $result = $conn->query($sql);
    
    if ($result->num_rows === 1) {
        $item = $result->fetch_assoc();
        
        // Prevent claiming your own item
        if ($item['user_id'] != $_SESSION['user_id']) {
            // Check for existing claim
            $check = $conn->query("SELECT id FROM claims 
                                  WHERE user_id={$_SESSION['user_id']} 
                                  AND item_id=$item_id 
                                  AND item_type='$item_type'");
            
            if ($check->num_rows === 0) {
                // Create claim
                $conn->query("INSERT INTO claims (user_id, item_id, item_type, message) 
                             VALUES ({$_SESSION['user_id']}, $item_id, '$item_type', '$message')");
                
                $_SESSION['message'] = "Claim submitted successfully!";
            } else {
                $_SESSION['message'] = "You've already claimed this item.";
            }
        } else {
            $_SESSION['message'] = "You can't claim your own item.";
        }
    }
}

header("Location: ../{$item_type}/view_{$item_type}.php");
exit;
?>