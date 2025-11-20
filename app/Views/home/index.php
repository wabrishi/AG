<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="p-5 mb-4 bg-light rounded-3">
    <div class="container-fluid py-5">
        <h1 class="display-5 fw-bold">Welcome to MyShop</h1>
        <p class="col-md-8 fs-4">Find the best products at the best prices.</p>
        <a href="/products" class="btn btn-primary btn-lg">Shop Now</a>
    </div>
</div>

<h2>Featured Products</h2>
<div class="row">
    <?php foreach ($products as $product): ?>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
             <?php if ($product['image']): ?>
                <img src="<?= $product['image'] ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>" style="height: 200px; object-fit: cover;">
            <?php else: ?>
                <div class="card-img-top bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">No Image</div>
            <?php endif; ?>
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                <p class="card-text">$<?= number_format($product['price'], 2) ?></p>
                <a href="/product?id=<?= $product['id'] ?>" class="btn btn-primary">View Details</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
