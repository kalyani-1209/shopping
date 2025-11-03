<?php
session_start();
include 'includes/db.php';

// Fetch all products
try {
    $stmt = $conn->prepare("SELECT * FROM products");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Store</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        header {
            background: #343a40;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        nav {
            margin-top: 10px;
        }

        nav a {
            color: #fff;
            margin: 0 15px;
            text-decoration: none;
            font-weight: bold;
        }

        main {
            padding: 30px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .card {
            width: 250px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-align: center;
            padding: 20px;
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card img {
            width: 100%;
            height: 180px;
            object-fit: contain;
            border-radius: 10px;
        }

        .btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }

        .btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome to Our Store</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="pages/cart.php">Cart</a>
            <a href="pages/login.php">Login</a>
            <a href="pages/register.php">Register</a>
        </nav>
    </header>

    <main>
        <?php if (empty($products)) : ?>
            <p>No products available.</p>
        <?php else : ?>
            <?php foreach ($products as $product) : ?>
                <div class="card">
                    <img src="images/<?= htmlspecialchars($product['image']); ?>" 
                         alt="<?= htmlspecialchars($product['name']); ?>">
                    <h3><?= htmlspecialchars($product['name']); ?></h3>
                    <p>₹<?= htmlspecialchars($product['price']); ?></p>
                    <p><?= htmlspecialchars($product['description']); ?></p>

                    <form method="POST" action="pages/cart.php">
                        <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                        <input type="number" name="quantity" value="1" min="1">
                        <button type="submit" name="add_to_cart" class="btn">Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
</body>
</html>
