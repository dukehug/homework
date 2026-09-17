<?php
// Shared setup. Keep db_connect.php one directory above these files.
session_start();
header('Content-Type: text/html; charset=UTF-8');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function stop_request($status, $message)
{
    http_response_code($status);
    exit(h($message));
}

set_exception_handler(function ($error) {
    error_log((string) $error);
    stop_request(500, 'Error: Operation failed, please check the PHP error log and verify the database connection, table and column settings.');
});

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function check_csrf()
{
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || !hash_equals($_SESSION['csrf_token'], $token)) {
        stop_request(403, 'Error: Form validation failed, please reopen the form.');
    }
}

function positive_id($value)
{
    $id = is_string($value) ? filter_var($value, FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]) : false;
    if ($id === false) {
        stop_request(400, 'Error: Please provide a valid user ID.');
    }
    return $id;
}

function user_input()
{
    $name = is_string($_POST['name'] ?? null) ? trim($_POST['name']) : '';
    $email = is_string($_POST['email'] ?? null) ? trim($_POST['email']) : '';
    $age = is_string($_POST['age'] ?? null) ? trim($_POST['age']) : '';
    $errors = [];
    if ($name === '') {
        $errors[] = 'Error: Name cannot be empty.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Error: Please enter a valid email address.';
    }
    if (filter_var($age, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) === false) {
        $errors[] = 'Error: Age must be a non-negative integer.';
    }
    return [$name, $email, $age, $errors];
}

function back_to_list()
{
    header('Location: list_users.php', true, 303);
    exit;
}

function database()
{
    // This file must create $conn = new mysqli(...) without echo/HTML output.
    require __DIR__ . '/../db_connect.php';
    if (!isset($conn) || !($conn instanceof mysqli)) {
        throw new RuntimeException('db_connect.php must create $conn as a mysqli connection.');
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}

// Change ONLY this trusted configuration if the real table name is different.
// The four expected columns are id, name, email and age.
$usersTable = 'users';
$usersTableSql = '`' . str_replace('`', '``', $usersTable) . '`';
