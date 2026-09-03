<?php
$score = 90;
$userType = "Student";
 
// if / elseif / else
echo "Score: $score<br>";
if ($score >= 90) {
    echo "Grade: A - Excellent!<br>";
} elseif ($score >= 80) {
    echo "Grade: B - Very Good<br>";
} elseif ($score >= 75) {
    echo "Grade: C - Passed<br>";
} else {
    echo "Grade: F - Needs Improvement<br>";
}
 
echo "<br>";
 
// switch
switch ($userType) {
    case "Admin":
        echo "Welcome Admin – Full access granted.";
        break;
    case "Teacher":
        echo "Welcome Teacher – Manage classes and grades.";
        break;
    case "Student":
        echo "Welcome Student – View your grades and schedule.";
        break;
    default:
        echo "Welcome Guest – Limited access.";
}
?>
