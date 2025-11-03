<?php
session_start();
if (!isset($_SESSION['order_success'])) {
    header("Location: ../index.php");
    exit;
}
unset($_SESSION['order_success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment Successful</title>
  <style>
    body { font-family: Arial, sans-serif; text-align: center; background: #e9ffe9; padding: 50px; }
    h1 { color: green; }
    a { text-decoration: none; color: white; background: #007bff; padding: 10px 20px; border-radius: 5px; }
  </style>
</head>
<body>
  <h1>✅ Payment Successful!</h1>
  <p>Thank you for your purchase. Your order has been confirmed.</p>
  <a href="../index.php">Back to Home</a>
</body>
</html>
