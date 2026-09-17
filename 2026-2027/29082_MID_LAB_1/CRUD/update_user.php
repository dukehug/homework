<?php
require_once "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id       = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');

    if ($id > 0 && !empty($username) && !empty($email)) {
        // Secure update with prepared statement
        $stmt = $conn->prepare("UPDATE lab_db SET username = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $username, $email, $id);

        if ($stmt->execute()) {
            echo "<h2>User updated successfully.</h2>";
            echo '<p><a href="edit_user.php">Return to Users List</a></p>';
        } else {
            echo "Error updating record: " . htmlspecialchars($stmt->error);
        }

        $stmt->close();
    } else {
        echo "Invalid input data.";
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
?>