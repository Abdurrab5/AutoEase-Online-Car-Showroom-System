<?php
 include 'header.php';

 include '../includes/functions.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in as admin
    exit;
}
 
 

$total_users = getTotalUsers($conn);
$total_cars = getTotalCars($conn);
$total_orders = getTotalOrders($conn);
$pending_orders = getPendingOrders($conn);
?>

 
    <h2 class="mb-4">Admin Dashboard</h2>

    <div class="row g-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary h-100">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text fs-4"><?= $total_users ?></p>
                </div>
                <div class="card-footer text-end">
                    <a href="manage_users.php" class="text-white text-decoration-underline">View Users</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success h-100">
                <div class="card-body">
                    <h5 class="card-title">Total Cars</h5>
                    <p class="card-text fs-4"><?= $total_cars ?></p>
                </div>
                <div class="card-footer text-end">
                    <a href="manage_cars.php" class="text-white text-decoration-underline">View Cars</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning h-100">
                <div class="card-body">
                    <h5 class="card-title">Total Orders</h5>
                    <p class="card-text fs-4"><?= $total_orders ?></p>
                </div>
                <div class="card-footer text-end">
                    <a href="#orders" class="text-white text-decoration-underline">View Orders</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger h-100">
                <div class="card-body">
                    <h5 class="card-title">Pending Orders</h5>
                    <p class="card-text fs-4"><?= $pending_orders ?></p>
                </div>
                <div class="card-footer text-end">
                    <a href="#orders" class="text-white text-decoration-underline">Manage</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
