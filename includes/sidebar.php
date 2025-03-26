<div class="sidebar">
    <div class="logo">
        <h1>XYZ Commerce</h1>
    </div>
    <nav>
        <ul>
            <li class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">
                <a href="index.php"><i class="fas fa-home"></i> Dashboard</a>
            </li>
            <li class="<?= basename($_SERVER['PHP_SELF']) == 'customers.php' ? 'active' : '' ?>">
                <a href="customers.php"><i class="fas fa-users"></i> Clientes</a>
            </li>
            <li class="<?= basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : '' ?>">
                <a href="products.php"><i class="fas fa-box-open"></i> Productos</a>
            </li>
            <li class="<?= basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'active' : '' ?>">
                <a href="orders.php"><i class="fas fa-shopping-cart"></i> Pedidos</a>
            </li>
        </ul>
    </nav>
</div>