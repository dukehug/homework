<?php
// Variables always start with $
$studentName = "Maria Santos";     // String
$score = 95;                       // Integer
$gpa = 1.75;                       // Float
$isEnrolled = true;                // Boolean
$courses = array("IT101", "IT102", "IT103");  // Array
 
echo "Student Name: " . $studentName . "<br>";
echo "Score: " . $score . "<br>";
echo "GPA: " . $gpa . "<br>";
echo "Enrolled: " . ($isEnrolled ? "Yes" : "No") . "<br>";
echo "First Course: " . $courses[0] . "<br>";
 
// Check data types
echo "<br>Data Types:<br>";
echo "studentName is " . gettype($studentName) . "<br>";
echo "score is " . gettype($score) . "<br>";
echo "gpa is " . gettype($gpa) . "<br>";
echo "isEnrolled is " . gettype($isEnrolled) . "<br>";
echo "courses is " . gettype($courses) . "<br>";
?>
