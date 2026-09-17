<?php
require_once "db_connect.php";

// Check if an ID is passed via GET parameter to edit a specific record
$edit_user = null;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT id, username, email FROM lab_db WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_user = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lab 3: Edit Users</title>
</head>
<body>

    <h2>User List</h2>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
        <?php
        $sql = "SELECT id, username, email FROM lab_db";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['username'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo '<td>';
                echo '<a href="edit_user.php?id=' . $row['id'] . '">Edit</a> &nbsp;&nbsp;';
                echo '<form action="delete_record.php" method="POST" style="display:inline-block; margin:0;">';
                echo '<input type="hidden" name="record_id" value="' . $row['id'] . '">';
                echo '<button type="submit" onclick="return confirm(\'Are you sure you want to proceed in deleting this user?\');" style="background-color: #dc3545; color: white; border: none; border-radius: 3px; padding: 2px 6px; font-size: 12px; font-weight: bold; cursor: pointer;">X</button>';
                echo '</form>';
                echo '</td>';
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No users found.</td></tr>";
        }
        ?>
    </table>

    <br>

    <!-- Edit Form -->
    <?php if ($edit_user) { ?>
        <h2>Edit User</h2>
        <form action="update_user.php" method="POST">
            <!-- Hidden ID -->
            <input type="hidden" name="id" value="<?php echo $edit_user['id']; ?>">

            <label>Username:</label><br>
            <input type="text" name="username" value="<?php echo $edit_user['username']; ?>" required><br><br>

            <label>Email:</label><br>
            <input type="email" name="email" value="<?php echo $edit_user['email']; ?>" required><br><br>

            <input type="submit" value="Update User">
            <a href="edit_user.php">Cancel</a>
        </form>
    <?php } ?>
    <a href='/index.html'>Back to Home </a>
</body>
</html>
<?php $conn->close(); ?>
