/*
Author: Duke Hsu
Date: Sep 10, 2026
Project: CRUD Group work
*/

<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

//db info - not recommand
$servername = "{your_db_server}";  
$username = "lab_db";
$password = "{your_db_password}";
$database = "lab_db";

try {
    // Create database connection
    $mysqli = new mysqli($servername, $username, $password, $dbname);

    // Set charset
    $mysqli->set_charset("utf8mb4");

    echo "<h3>Connected successfully!</h3>";

    // Get all tables in current database
    $result = $mysqli->query("SHOW TABLES");

    echo "<h3>Database: $dbname</h3>";

    while ($row = $result->fetch_array()) {

        $tableName = $row[0];

        // Count records in each table
        $countResult = $mysqli->query(
            "SELECT COUNT(*) AS total FROM `$tableName`"
        );

        $countRow = $countResult->fetch_assoc();
        $total = $countRow['total'];

        //output table info
        echo "Table: <strong>$tableName</strong>";
        echo " — Records: <strong>$total</strong><br>";
    }

} catch (mysqli_sql_exception $e) {
    //display error infomation
    echo "Database connection failed!<br>";
    echo "Error message: " . $e->getMessage() . "<br>";
    echo "Error code: " . $e->getCode() . "<br>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine();
}
?>