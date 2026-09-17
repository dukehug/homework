<?php
require_once __DIR__ . '/common.php';
$name = $email = $age = '';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    list($name, $email, $age, $errors) = user_input();
    if (!$errors) {
        $conn = database();
        $stmt = $conn->prepare("INSERT INTO $usersTableSql (name, email, age) VALUES (?, ?, ?)");
        $ageNumber = (int) $age;
        $stmt->bind_param('ssi', $name, $email, $ageNumber);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        back_to_list();
    }
} elseif ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header('Allow: GET, POST');
    stop_request(405, 'Error: Only GET and POST methods are allowed.');
}
?>
<!doctype html>
<html lang="en-US">
<head><meta charset="utf-8"><title>Add User</title></head>
<body>
<h1>Add User</h1>
<?php foreach ($errors as $error): ?>
<p role="alert"><?= h($error) ?></p>
<?php endforeach; ?>
<form action="add_user.php" method="post">
    <input type="hidden" name="csrf_token" value="<?= h($_SESSION['csrf_token']) ?>">
    <label>Name: <input type="text" name="name" value="<?= h($name) ?>" required></label><br>
    <label>Email: <input type="email" name="email" value="<?= h($email) ?>" required></label><br>
    <label>Age: <input type="number" name="age" min="0" step="1" value="<?= h($age) ?>" required></label><br>
    <button type="submit">Add User</button>
</form>
<p><a href="list_users.php">Back to list</a></p>
</body>
</html>
