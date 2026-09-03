<?php
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name  = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $course = $_POST["course"] ?? "";
 
    // Simple validation
    if (empty($name) || empty($email) || empty($course)) {
        $message = "<p style='color:red;'>Please fill in all required fields.</p>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<p style='color:red;'>Invalid email address.</p>";
    } else {
        $message = "<p style='color:green;'>";
        $message .= "Registration successful!<br>";
        $message .= "Name: " . htmlspecialchars($name) . "<br>";
        $message .= "Email: " . htmlspecialchars($email) . "<br>";
        $message .= "Course: " . htmlspecialchars($course);
        $message .= "</p>";
    }
}
?>
 
<!DOCTYPE html>
<html>
<head><title>Student Registration</title></head>
<body>
  <h2>Student Registration Form</h2>
  <?php echo $message; ?>
  <form method="POST" action="">
    <label>Full Name:</label><br>
    <input type="text" name="name" required><br><br>
    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>
    <label>Course:</label><br>
    <select name="course" required>
      <option value="">-- Select Course --</option>
      <option value="BSIT">BS Information Technology</option>
      <option value="BSCS">BS Computer Science</option>
      <option value="BSIS">BS Information Systems</option>
    </select><br><br>
    <input type="submit" value="Register">
  </form>
</body>
</html>
