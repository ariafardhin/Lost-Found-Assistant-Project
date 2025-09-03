<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Only show claims for found items
$sql = "SELECT claims.*, users.email, found_items.name AS item_name 
        FROM claims 
        JOIN users ON claims.user_id = users.id 
        JOIN found_items ON claims.item_id = found_items.id 
        WHERE claims.item_type = 'found'
        ORDER BY claims.created_at DESC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Claims Report</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="container">
    <h2>Claims Report</h2>
    <a href="../dashboard.html" class="back">Back to Dashboard</a>
    <table border="1" cellpadding="8" style="width:100%; border-collapse:collapse; margin-top:20px;">
        <tr>
            <th>User</th>
            <th>Item</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['item_name']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td><?= htmlspecialchars($row['created_at']) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <a href="/WP%20Project/lost-found-system/logout.php" class="logout">Logout</a>
</div>
</body>
</html>