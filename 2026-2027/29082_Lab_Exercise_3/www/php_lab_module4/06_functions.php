<?php
// Function that calculates total with discount
function calculateTotal($price, $qty, $discountRate = 0) {
    $subtotal = $price * $qty;
    $discount = $subtotal * $discountRate;
    return $subtotal - $discount;
}
 
// Function that returns a greeting
function greetStudent($name) {
    return "Hello, $name! Welcome to the PHP Lab.";
}
 
// Call the functions
echo greetStudent("Juan Dela Cruz") . "<br><br>";
 
$total1 = calculateTotal(1500, 3);           // no discount
$total2 = calculateTotal(2500, 2, 0.15);     // 15% discount
 
echo "Order 1 Total: ₱" . number_format($total1, 2) . "<br>";
echo "Order 2 Total (15% off): ₱" . number_format($total2, 2) . "<br>";
?>
