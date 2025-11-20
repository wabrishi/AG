<?php include __DIR__ . '/../../layouts/header.php'; ?>

<div class="row">
    <div class="col-md-3">
        <div class="list-group">
            <a href="/admin" class="list-group-item list-group-item-action active">Dashboard</a>
            <a href="/admin/products" class="list-group-item list-group-item-action">Products</a>
            <a href="/admin/categories" class="list-group-item list-group-item-action">Categories</a>
            <a href="/admin/orders" class="list-group-item list-group-item-action">Orders</a>
        </div>
    </div>
    <div class="col-md-9">
        <h2>Admin Dashboard</h2>
        <p>Welcome to the admin panel.</p>
        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header">Products</div>
                    <div class="card-body">
                        <h5 class="card-title">Manage Products</h5>
                        <p class="card-text">Add, edit, or delete products.</p>
                        <a href="/admin/products" class="btn btn-light">Go</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Categories</div>
                    <div class="card-body">
                        <h5 class="card-title">Manage Categories</h5>
                        <p class="card-text">Organize your catalog.</p>
                        <a href="/admin/categories" class="btn btn-light">Go</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-header">Orders</div>
                    <div class="card-body">
                        <h5 class="card-title">Manage Orders</h5>
                        <p class="card-text">View and update orders.</p>
                        <a href="/admin/orders" class="btn btn-light">Go</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
