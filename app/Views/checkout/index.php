<?php include __DIR__ . '/../layouts/header.php'; ?>

<h2>Checkout</h2>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Shipping & Payment</div>
            <div class="card-body">
                <form action="/order/place" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                    <div class="mb-3">
                        <label class="form-label">Shipping Address</label>
                        <textarea name="address" class="form-control" required rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select name="payment_method" class="form-select">
                            <option value="cod">Cash on Delivery</option>
                            <option value="card">Credit Card (Dummy)</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Place Order ($<?= number_format($total, 2) ?>)</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Order Summary</div>
            <ul class="list-group list-group-flush">
                <?php foreach ($cartItems as $item): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= htmlspecialchars($item['name']) ?> x <?= $item['quantity'] ?>
                    <span>$<?= number_format($item['subtotal'], 2) ?></span>
                </li>
                <?php endforeach; ?>
                <li class="list-group-item d-flex justify-content-between align-items-center fw-bold">
                    Total
                    <span>$<?= number_format($total, 2) ?></span>
                </li>
            </ul>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
