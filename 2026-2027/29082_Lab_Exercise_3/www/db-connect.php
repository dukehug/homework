<?php
//mysql connection information 
$host = 'mysql';
$db = 'my_app';
$user = 'app_user';
$password = 'app_password';

// Create a new PDO instance and connect to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    echo "<h2>Connected Successfully!</h2>";
} catch (PDOException $e) {
    echo "<h2>Connection Failed: " . $e->getMessage() . "</h2>";
}
?>
