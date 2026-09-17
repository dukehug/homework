<?php

require_once __DIR__ . '/../db-connect.php';

$sql = "SELECT id, name, email, age FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {

    echo "
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Age</th>
            <th>Action</th>
        </tr>
    ";

    while ($row = $result->fetch_assoc()) {

        echo "
        <tr>
            <td>{$row['id']}</td>
            <td>{$row['name']}</td>
            <td>{$row['email']}</td>
            <td>{$row['age']}</td>
            <td>
                <a href='edit_user.php?id={$row['id']}'>
                    Edit
                </a>
            </td>
        </tr>
        ";
    }

    echo "</table>";

} else {
    echo "0 results";
}

$conn->close();
?>