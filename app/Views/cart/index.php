<?php include __DIR__ . '/../layouts/header.php'; ?>

<h2>Shopping Cart</h2>

<?php if (empty($cartItems)): ?>
    <p>Your cart is empty. <a href="/products">Go Shopping</a></p>
<?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cartItems as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td>$<?= number_format($item['price'], 2) ?></td>
                <td>
                    <form action="/cart/update" method="POST" class="d-flex" style="max-width: 150px;">
                        <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                        <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                        <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" class="form-control me-2">
                        <button type="submit" class="btn btn-sm btn-secondary">Update</button>
                    </form>
                </td>
                <td>$<?= number_format($item['subtotal'], 2) ?></td>
                <td>
                    <form action="/cart/remove" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                        <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                <td colspan="2"><strong>$<?= number_format($total, 2) ?></strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="d-flex justify-content-between">
        <a href="/products" class="btn btn-secondary">Continue Shopping</a>
        <a href="/checkout" class="btn btn-success">Proceed to Checkout</a>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
