<?php include __DIR__ . '/../../layouts/header.php'; ?>

<div class="row">
    <div class="col-md-3">
        <div class="list-group">
            <a href="/admin" class="list-group-item list-group-item-action">Dashboard</a>
            <a href="/admin/products" class="list-group-item list-group-item-action active">Products</a>
            <a href="/admin/categories" class="list-group-item list-group-item-action">Categories</a>
        </div>
    </div>
    <div class="col-md-9">
        <h2>Edit Product</h2>
        <form method="POST" action="/admin/products/edit?id=<?= $product['id'] ?>" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-control" required>
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $product['category_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($product['description']) ?></textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Price</label>
                    <input type="number" name="price" step="0.01" class="form-control" value="<?= $product['price'] ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" class="form-control" value="<?= $product['stock'] ?>" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Image</label>
                <?php if ($product['image']): ?>
                    <div class="mb-2">
                        <img src="<?= $product['image'] ?>" style="width: 100px;">
                    </div>
                <?php endif; ?>
                <input type="file" name="image" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Update Product</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
