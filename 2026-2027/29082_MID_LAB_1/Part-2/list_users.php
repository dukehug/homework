<?php
require_once __DIR__ . '/common.php';
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header('Allow: GET');
    stop_request(405, 'Error: Only GET method is allowed.');
}
$conn = database();
$result = $conn->query("SELECT id, name, email, age FROM $usersTableSql ORDER BY id");
?>
<!doctype html>
<html lang="en-US">
<head><meta charset="utf-8"><title>Users</title></head>
<body>
<h1>Users</h1>
<p><a href="add_user.php">Add User</a> | <a href="list_users.php">Refresh</a> | <a href="/index.html">Back to Home</a></p> 
<?php if ($result->num_rows > 0): ?>
<table border="1">
    <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Age</th><th>Actions</th></tr></thead>
    <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= h($row['id']) ?></td>
        <td><?= h($row['name']) ?></td>
        <td><?= h($row['email']) ?></td>
        <td><?= h($row['age']) ?></td>
        <td>
            <a href="edit_user.php?id=<?= h($row['id']) ?>">Edit</a>
            <form action="delete_user.php" method="post" style="display:inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                <input type="hidden" name="id" value="<?= h($row['id']) ?>">
                <input type="hidden" name="csrf_token" value="<?= h($_SESSION['csrf_token']) ?>">
                <button type="submit">Delete</button>
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
    </tbody>
</table>
<?php else: ?>
<p>0 results</p>
<?php endif; ?>
</body>
</html>
<?php
$result->free();
$conn->close();
