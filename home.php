<?php foreach ($products as $product): ?>
    <div class="card">
        <h3><?= $product['name']; ?></h3>
        <p>Price: $<?= $product['price']; ?></p>
        <p><?= $product['description']; ?></p>
    </div>
<?php endforeach; ?>
