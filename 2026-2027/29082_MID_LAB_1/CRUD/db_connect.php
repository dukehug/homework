<?php
$servername = "{your_db_server}";  
$username = "lab_db";
$password = "{your_db_password}";
$database = "lab_db";


// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
