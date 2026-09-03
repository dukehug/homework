<?php
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"] ?? "");
    $books = $_POST["books"] ?? []; // Get array of selected books
    $quantities = $_POST["quantity"] ?? []; // Get array of quantities
    
    // Define book prices
    $prices = [
        "Fiction" => 350,
        "Non-Fiction" => 420,
        "Textbook" => 550
    ];
    
    // Validate: check if name is filled
    if (empty($name)) {
        $message = "<p style='color:red;'>Please fill in customer name.</p>";
    } 
    // Validate: check name format
    elseif (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $message = "<p style='color:red;'>Invalid name format.</p>";
    } 
    // Validate: check if at least one book is selected
    elseif (empty($books)) {
        $message = "<p style='color:red;'>Please select at least one book.</p>";
    } 
    else {
        $message = "<p style='color:green;'>";
        $message .= "<strong>Order successful!</strong><br><br>";
        $message .= "Customer Name: " . htmlspecialchars($name) . "<br><br>";
        
        $totalSubtotal = 0;
        $totalDiscount = 0;
        
        // Loop through each selected book
        foreach ($books as $index => $bookType) {
            $quantity = (int)($quantities[$index] ?? 0);
            
            // Validate: check if quantity is valid
            if ($quantity <= 0) {
                $message = "<p style='color:red;'>Quantity must be greater than 0.</p>";
                break;
            }
            
            // Validate: check if quantity is negative
            if ($quantity < 0) {
                $message = "<p style='color:red;'>Quantity cannot be negative.</p>";
                break;
            }
            
            $price = $prices[$bookType];
            $subtotal = $quantity * $price;
            
            // Check discount condition (quantity >= 5 applies 12% discount)
            $discountRate = ($quantity >= 5) ? 0.12 : 0;
            $discountAmount = $subtotal * $discountRate;
            $finalTotal = $subtotal - $discountAmount;
            
            // Accumulate totals
            $totalSubtotal += $subtotal;
            $totalDiscount += $discountAmount;
            
            // Display order details for each book
            $message .= "Book Type: " . htmlspecialchars($bookType) . "<br>";
            $message .= "Quantity: " . $quantity . "<br>";
            $message .= "Subtotal: ₱" . number_format($subtotal, 2) . "<br>";
            
            // Show discount only if applicable
            if ($discountAmount > 0) {
                $message .= "Discount (12%): -₱" . number_format($discountAmount, 2) . "<br>";
            }
            
            $message .= "Item Total: ₱" . number_format($finalTotal, 2) . "<br><br>";
        }
        
        // Display grand totals
        if (strpos($message, "Quantity must be greater than 0") === false && 
            strpos($message, "Quantity cannot be negative") === false) {
            $grandTotal = $totalSubtotal - $totalDiscount;
            $message .= "---<br>";
            $message .= "Total Subtotal: ₱" . number_format($totalSubtotal, 2) . "<br>";
            
            if ($totalDiscount > 0) {
                $message .= "Total Discount: -₱" . number_format($totalDiscount, 2) . "<br>";
            }
            
            $message .= "<strong>Grand Total: ₱" . number_format($grandTotal, 2) . "</strong>";
        }
        
        $message .= "</p>";
    }
}
?>
 
<!DOCTYPE html>
<html>
<head><title>Online Bookstore Order Checker</title></head>
  <style>
    input[type="number"] {
      width: 30px;
    }
  </style>
<body>

  <h2>Online Bookstore Order Checker</h2>
  <?php echo $message; ?>
  
  <form method="POST" action="">
    <label>Customer Name:</label><br>
    <input type="text" name="name" required><br><br>
    
    <label>Books:</label><br>
    <ul>
      <li>
        <input type="checkbox" name="books[]" value="Fiction" required> Fiction - ₱350
        <input type="number" name="quantity[]" min="0" required>
      </li>
      <li>
        <input type="checkbox" name="books[]" value="Non-Fiction" required> Non-Fiction - ₱420
        <input type="number"  name="quantity[]" min="0" required>
      </li>
      <li>
        <input type="checkbox" name="books[]" value="Textbook" required> Textbook - ₱550
        <input type="number"  name="quantity[]" min="0" required>
      </li>
    </ul><br>
    
    <input type="submit" value="Check Order">
  </form>
</body>
</html>
