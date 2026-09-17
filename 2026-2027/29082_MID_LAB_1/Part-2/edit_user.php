<?php
require_once __DIR__ . '/common.php';
$method = $_SERVER['REQUEST_METHOD'];
if ($method !== 'GET' && $method !== 'POST') {
    header('Allow: GET, POST');
    stop_request(405, 'Error: Only GET and POST methods are allowed.');
}
if ($method === 'POST') {
    check_csrf();
}
$id = positive_id($method === 'POST' ? ($_POST['id'] ?? null) : ($_GET['id'] ?? null));
$conn = database();
$stmt = $conn->prepare("SELECT name, email, age FROM $usersTableSql WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->bind_result($name, $email, $age);
$found = $stmt->fetch();
$stmt->close();
if (!$found) {
    stop_request(404, 'Error: User not found.');
}
$errors = [];
if ($method === 'POST') {
    list($name, $email, $age, $errors) = user_input();
    if (!$errors) {
        $stmt = $conn->prepare("UPDATE $usersTableSql SET name = ?, email = ?, age = ? WHERE id = ?");
        $ageNumber = (int) $age;
        $stmt->bind_param('ssii', $name, $email, $ageNumber, $id);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        back_to_list();
    }
}
$conn->close();
?>
<!doctype html>
<html lang="en-US">
<head><meta charset="utf-8"><title>Edit User</title></head>
<body>
<h1>Edit User</h1>
<?php foreach ($errors as $error): ?>
<p role="alert"><?= h($error) ?></p>
<?php endforeach; ?>
<form action="edit_user.php" method="post">
    <input type="hidden" name="id" value="<?= h($id) ?>">
    <input type="hidden" name="csrf_token" value="<?= h($_SESSION['csrf_token']) ?>">
    <label>Name: <input type="text" name="name" value="<?= h($name) ?>" required></label><br>
    <label>Email: <input type="email" name="email" value="<?= h($email) ?>" required></label><br>
    <label>Age: <input type="number" name="age" min="0" step="1" value="<?= h($age) ?>" required></label><br>
    <button type="submit">Save</button>
</form>
<p><a href="list_users.php">Back to list</a></p>
</body>
</html>
