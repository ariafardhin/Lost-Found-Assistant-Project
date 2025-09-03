<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM lost_items ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Lost Items</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="container">
    <h2>Lost Items</h2>
    <a href="../dashboard.html" class="back">Back to Dashboard</a>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="item">
            <strong><?= htmlspecialchars($row['name']) ?></strong><br>
            <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>
            <p><em>Location:</em> <?= htmlspecialchars($row['location']) ?></p>
            <?php if ($row['photo']): ?>
                <img src="../uploads/<?= htmlspecialchars($row['photo']) ?>" width="100">
            <?php endif; ?>
            <p><small>Posted on: <?= date('M j, Y g:i a', strtotime($row['created_at'])) ?></small></p>
            <?php if ($row['user_id'] == $user_id): ?>
                <a href="edit_lost.php?id=<?= $row['id'] ?>" class="btn">Edit</a>
                <a href="delete_lost.php?id=<?= $row['id'] ?>" class="btn" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
            <?php else: ?>
                <form action="../claims/claim_item.php" method="POST" class="claim-form" style="display:inline;">
                    <input type="hidden" name="item_id" value="<?= $row['id'] ?>">
                    <input type="hidden" name="item_type" value="lost">
                    <textarea name="message" placeholder="Add a message (optional)"></textarea>
                    <button type="submit" class="btn">Contact if Found</button>
                </form>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
</div>
<a href="logout.php" class="logout">Logout</a>
</body>
</html>