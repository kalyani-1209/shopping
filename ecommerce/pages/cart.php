<?php
session_start();
include '../includes/db.php';

// Dummy login fallback
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // Temporary for testing
}

$user_id = $_SESSION['user_id'];

// ✅ Add to Cart Logic
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = (int)($_POST['quantity'] ?? 1);

    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        $new_qty = $existing['quantity'] + $quantity;
        $update = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $update->execute([$new_qty, $user_id, $product_id]);
    } else {
        $insert = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $insert->execute([$user_id, $product_id, $quantity]);
    }
}

// ✅ Remove Item
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
}

// ✅ Update Quantity
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$quantity, $user_id, $product_id]);
}

// ✅ Fetch Cart Items
$stmt = $conn->prepare("
    SELECT c.*, p.name, p.price, p.image 
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = ?
");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
        }

        .cart-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 10px;
            border-bottom: 1px solid #ddd;
        }

        .cart-item img {
            width: 80px;
            height: 80px;
            object-fit: contain;
            border-radius: 8px;
        }

        .item-info {
            flex: 1;
            margin-left: 20px;
        }

        .quantity-input {
            width: 60px;
            text-align: center;
        }

        .btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 6px 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn:hover {
            background: #0056b3;
        }

        .total {
            text-align: right;
            font-size: 1.3em;
            font-weight: bold;
            margin-top: 20px;
        }

        .actions {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .actions a {
            background: #28a745;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
        }

        .actions a:hover {
            background: #218838;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Your Cart</h2>
    <?php if (empty($cart_items)) : ?>
        <p style="text-align:center;">Your cart is empty.</p>
    <?php else : ?>
        <?php foreach ($cart_items as $item) : ?>
            <?php $total += $item['price'] * $item['quantity']; ?>
            <div class="cart-item">
                <img src="../images/<?= htmlspecialchars($item['image']); ?>" alt="<?= htmlspecialchars($item['name']); ?>">
                <div class="item-info">
                    <strong><?= htmlspecialchars($item['name']); ?></strong><br>
                    ₹<?= number_format($item['price'], 2); ?>
                </div>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="product_id" value="<?= $item['product_id']; ?>">
                    <input type="number" name="quantity" value="<?= $item['quantity']; ?>" min="1" class="quantity-input">
                    <button type="submit" name="update_quantity" class="btn">Update</button>
                </form>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="product_id" value="<?= $item['product_id']; ?>">
                    <button type="submit" name="remove_from_cart" class="btn" style="background:#dc3545;">Remove</button>
                </form>
            </div>
        <?php endforeach; ?>

        <div class="total">Total: ₹<?= number_format($total, 2); ?></div>

        <div class="actions">
            <a href="../index.php">Continue Shopping</a>
            <a href="checkout.php" class="btn">Proceed to Checkout</a>
        </div>
    <?php endif; ?>
</div>
</body>
</html>

 