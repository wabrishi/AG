<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="row">
    <div class="col-md-6">
        <?php if ($product['image']): ?>
            <img src="<?= $product['image'] ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($product['name']) ?>">
        <?php else: ?>
            <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 400px;">No Image</div>
        <?php endif; ?>
    </div>
    <div class="col-md-6">
        <h1><?= htmlspecialchars($product['name']) ?></h1>
        <h4 class="text-muted"><?= htmlspecialchars($product['category_name']) ?></h4>
        <h2 class="text-primary">$<?= number_format($product['price'], 2) ?></h2>
        <p class="mt-4"><?= nl2br(htmlspecialchars($product['description'])) ?></p>

        <?php if ($product['stock'] > 0): ?>
            <form action="/cart/add" method="POST" class="mt-4">
                <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <div class="input-group mb-3" style="max-width: 200px;">
                    <span class="input-group-text">Qty</span>
                    <input type="number" name="quantity" class="form-control" value="1" min="1" max="<?= $product['stock'] ?>">
                    <button class="btn btn-success" type="submit">Add to Cart</button>
                </div>
            </form>
        <?php else: ?>
            <div class="alert alert-warning mt-4">Out of Stock</div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
