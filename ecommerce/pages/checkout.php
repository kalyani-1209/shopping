<?php
session_start();

// Redirect if user not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include '../includes/db.php';

$user_id = $_SESSION['user_id'];

// Fetch cart items for the logged-in user
$stmt = $conn->prepare("
    SELECT p.id, p.name, p.price, p.image, c.quantity
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?
");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            background-color: #fff;
            padding: 25px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            font-size: 2em;
            color: #222;
            margin-bottom: 20px;
        }

        .item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #eee;
            padding: 15px 0;
        }

        .item:last-child {
            border-bottom: none;
        }

        .item img {
            width: 80px;
            height: 80px;
            object-fit: contain;
            border-radius: 6px;
            margin-right: 15px;
        }

        .item-details {
            flex: 1;
            text-align: left;
        }

        .item-name {
            font-size: 1.1em;
            font-weight: bold;
            color: #222;
        }

        .item-price {
            color: #555;
            font-size: 0.95em;
        }

        .subtotal {
            font-weight: bold;
            color: #28a745;
        }

        .total {
            text-align: right;
            font-size: 1.3em;
            font-weight: bold;
            margin-top: 25px;
            border-top: 2px solid #eee;
            padding-top: 15px;
        }

        .actions {
            text-align: center;
            margin-top: 30px;
        }

        .btn {
            background-color: #007bff;
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 6px;
            font-weight: bold;
            transition: background-color 0.3s ease;
            margin: 0 10px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .empty-cart {
            text-align: center;
            font-size: 1.1em;
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Checkout</h2>

    <?php if (empty($cart_items)): ?>
        <p class="empty-cart">Your cart is empty.</p>
        <div class="actions">
            <a href="../index.php" class="btn">Back to Shop</a>
        </div>
    <?php else: ?>
        <?php 
            $total = 0;
            foreach ($cart_items as $item): 
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
        ?>
        <div class="item">
            <img src="../images/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
            <div class="item-details">
                <div class="item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                <div class="item-price">₹<?php echo number_format($item['price'], 2); ?> × <?php echo $item['quantity']; ?></div>
            </div>
            <div class="subtotal">₹<?php echo number_format($subtotal, 2); ?></div>
        </div>
        <?php endforeach; ?>

        <div class="total">Total: ₹<?php echo number_format($total, 2); ?></div>

        <div class="actions">
            <a href="../index.php" class="btn">Back to Shop</a>
            <a href="payment.php" class="btn">Proceed to Payment</a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
