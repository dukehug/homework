<?php
echo "<h3>for loop – Count 1 to 5</h3>";
for ($i = 1; $i <= 5; $i++) {
    echo "Number: $i<br>";
}
 
echo "<h3>while loop – Countdown</h3>";
$count = 3;
while ($count > 0) {
    echo "Countdown: $count<br>";
    $count--;
}
echo "Go!<br>";
 
echo "<h3>foreach – Shopping Cart Items</h3>";
$cart = array("Laptop", "Mouse", "Keyboard", "Headset");
foreach ($cart as $item) {
    echo "- $item<br>";
}
?>
