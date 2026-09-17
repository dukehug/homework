<?php

$servername = "{your_db_server}";  
$username = "lab_db";
$password = "{your_db_password}";
$database = "lab_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// Check if form was submitted  
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = $_POST['username'];
    $user_email = $_POST['email'];

    // Use prepared statements for secure database insertion
    $stmt = $conn->prepare("INSERT INTO lab_db (username, email) VALUES (?, ?)");
    $stmt->bind_param("ss", $user_name, $user_email);

    if ($stmt->execute()) {
        $message = "User added successfully.";
    } else {
        $message = "Error adding user: " . $stmt->error;
    }

    $stmt->close();
    }

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add User</title>
</head>
<body>
    <h2>Registration</h2>

    <?php if (!empty($message)): ?>
        <p><strong><?php echo $message; ?></strong></p>
    <?php endif; ?>

    <form action="add_user.php" method="POST">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <button type="submit">Submit</button>
    </form>
</body>
</html>