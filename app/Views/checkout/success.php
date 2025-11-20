<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="text-center mt-5">
    <h1 class="text-success">Order Placed Successfully!</h1>
    <p>Thank you for your purchase. Your order ID is #<?= $order['id'] ?>.</p>
    <button onclick="window.print()" class="btn btn-secondary mb-4">Print Invoice</button>
</div>

<div class="card">
    <div class="card-header">
        Invoice #<?= $order['id'] ?>
        <span class="float-end"><?= $order['created_at'] ?></span>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-sm-6">
                <h6 class="mb-3">To:</h6>
                <div><?= nl2br(htmlspecialchars($order['shipping_address'])) ?></div>
            </div>
        </div>

        <div class="table-responsive-sm">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-end">Price</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td class="text-center"><?= $item['quantity'] ?></td>
                        <td class="text-end">$<?= number_format($item['price'], 2) ?></td>
                        <td class="text-end">$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-lg-4 col-sm-5 ms-auto">
                <table class="table table-clear">
                    <tbody>
                        <tr>
                            <td class="text-end"><strong>Total</strong></td>
                            <td class="text-end"><strong>$<?= number_format($order['total_amount'], 2) ?></strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
