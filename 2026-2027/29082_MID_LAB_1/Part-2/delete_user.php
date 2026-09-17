<?php
require_once __DIR__ . '/common.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    stop_request(405, 'Error: Only POST method is allowed.');
}
check_csrf();
$id = positive_id($_POST['id'] ?? null);
$conn = database();
$stmt = $conn->prepare("DELETE FROM $usersTableSql WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$deleted = $stmt->affected_rows;
$stmt->close();
$conn->close();
if ($deleted === 0) {
    stop_request(404, 'Error: User not found or already deleted.');
}
back_to_list();
