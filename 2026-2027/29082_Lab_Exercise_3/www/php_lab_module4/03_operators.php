<?php
$price = 45000.50;
$quantity = 2;
$discount = 0.10;   // 10%
$stock = 25;
 
// Arithmetic operators
$subtotal = $price * $quantity;
$discountAmount = $subtotal * $discount;
$total = $subtotal - $discountAmount;
 
echo "Price: ₱" . number_format($price, 2) . "<br>";
echo "Quantity: " . $quantity . "<br>";
echo "Subtotal: ₱" . number_format($subtotal, 2) . "<br>";
echo "Discount (10%): ₱" . number_format($discountAmount, 2) . "<br>";
echo "Total: ₱" . number_format($total, 2) . "<br><br>";
 
// Comparison & Logical
$inStock = $stock > 0;
$canBuy = ($stock >= $quantity) && ($total > 0);
 
echo "In stock? " . ($inStock ? "Yes" : "No") . "<br>";
echo "Can buy? " . ($canBuy ? "Yes" : "No") . "<br>";
?>
