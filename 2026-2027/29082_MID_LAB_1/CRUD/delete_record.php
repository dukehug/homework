<?php
require 'db_connect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['record_id'])) {
    
    $id_to_delete = $_POST['record_id'];

    // Using your table name: lab_db
    $sql = "DELETE FROM lab_db WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("i", $id_to_delete);
        
        if ($stmt->execute()) {
            // FIXED REDIRECT 1: Goes back to your group's table page
            header("Location: edit_user.php");
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
} else {
    // FIXED REDIRECT 2: Goes back to your group's table page
    header("Location: edit_user.php");
    exit();
}
?>