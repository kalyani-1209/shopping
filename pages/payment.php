<?php
session_start();
require '../includes/db.php';

$user_id = 1; // demo user ID

// Check if Confirm Payment button was clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_payment'])) {
    try {
        // Clear the user's cart
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->execute([$user_id]);

        // Set a success flag in the session
        $_SESSION['order_success'] = true;

        // Redirect to success page
        header("Location: payment_success.php");
        exit;
    } catch (Exception $e) {
        die("Payment processing failed: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment</title>
  <style>
    body { font-family: Arial, sans-serif; text-align: center; background: #f4f4f4; padding: 50px; }
    form { display: inline-block; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    input { width: 90%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; }
    button { background-color: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
    button:hover { background-color: #218838; }
  </style>
</head>
<body>

  <h1>Payment Details</h1>
  <form method="POST" action="payment.php">
    <label>Cardholder Name</label><br>
    <input type="text" name="card_name" required><br>

    <label>Card Number</label><br>
    <input type="text" name="card_number" maxlength="16" required><br>

    <label>Expiry Date</label><br>
    <input type="month" name="expiry_date" required><br>

    <label>CVV</label><br>
    <input type="text" name="cvv" maxlength="3" required><br>

    <button type="submit" name="confirm_payment">Confirm Payment</button>
  </form>

</body>
</html>
