<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'created_at DESC';

// Validate sort option
$allowed_sorts = [
    'created_at DESC' => 'Newest First',
    'created_at ASC' => 'Oldest First',
    'name ASC' => 'Name (A-Z)',
    'name DESC' => 'Name (Z-A)'
];

if (!array_key_exists($sort, $allowed_sorts)) {
    $sort = 'created_at DESC';
}

$sql = "SELECT * FROM lost_items 
        WHERE status = 'lost' 
        AND (name LIKE '%$search%' OR description LIKE '%$search%' OR location LIKE '%$search%') 
        ORDER BY $sort";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Search Lost Items</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h2>Search Lost Items</h2>
        <a href="../dashboard.html" class="back">Back to Dashboard</a>
        
        <form method="GET" class="search-form">
            <input type="text" name="search" placeholder="Search by name, description or location" value="<?php echo htmlspecialchars($search); ?>">
            
            <select name="sort">
                <?php foreach ($allowed_sorts as $key => $label): ?>
                    <option value="<?php echo $key; ?>" <?php echo $sort == $key ? 'selected' : ''; ?>>
                        <?php echo $label; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <button type="submit">Search</button>
        </form>
        
        <div class="items-list">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="item">
                        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                        <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
                        <p><strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?></p>
                        <p><small>Posted on: <?php echo date('M j, Y g:i a', strtotime($row['created_at'])); ?></small></p>
                        
                        <?php if (!empty($row['photo'])): ?>
                            <img src="../uploads/<?php echo htmlspecialchars($row['photo']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                        <?php endif; ?>
                        
                        <?php if ($row['user_id'] != $_SESSION['user_id']): ?>
                            <form action="../claims/claim_item.php" method="POST" class="claim-form">
                                <input type="hidden" name="item_id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="item_type" value="lost">
                                <textarea name="message" placeholder="Add a message (optional)"></textarea>
                                <a href="mailto:<?= htmlspecialchars($owner_email) ?>?subject=Regarding your lost item: <?= urlencode($row['name']) ?>&body=Hi, I have information about your lost item '<?= htmlspecialchars($row['name']) ?>'."
                                class="btn">
                                    Contact if Found
                                </a>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No lost items found matching your search.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>