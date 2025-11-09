<?php
  include 'header.php'; // Include customer header  

if (!isset($_SESSION['customer_id'])) {
    header('Location: login.php'); // Redirect if not logged in as customer
    exit;
}

if (!isset($_GET['car_id'])) {
    echo "Invalid car ID.";
    exit;
}

$car_id = intval($_GET['car_id']);
$customer_id = $_SESSION['customer_id'];

// Fetch car details
$car_query = "SELECT * FROM cars WHERE id = $car_id";
$car_result = mysqli_query($conn, $car_query);
$car = mysqli_fetch_assoc($car_result);

// Calculate delivery charge based on city
$delivery_charge = 1000; // Default charge, can be dynamic based on the city

?>



<div class="container mt-4">
    <h2>Place Your Order for <?= $car['brand'] ?> <?= $car['model'] ?></h2>

    <form method="POST" action="process_order.php">
        <input type="hidden" name="car_id" value="<?= $car_id ?>">
        <input type="hidden" name="customer_id" value="<?= $customer_id ?>">

        <div class="form-group">
            <label for="payment_type">Payment Type</label>
            <select class="form-control" name="payment_type" id="payment_type" required>
                <option value="Full Payment">Full Payment</option>
                <option value="Instalments">Instalments</option>
            </select>
        </div>

        <div class="form-group">
            <label for="delivery_city">Delivery City</label>
            <select class="form-control" name="delivery_city" id="delivery_city" required>
                <option value="Lahore">Lahore</option>
                <option value="Islamabad">Islamabad</option>
                <option value="Peshawar">Peshawar</option>
                <option value="Karachi">Karachi</option>
            </select>
        </div>

        <div class="form-group">
            <label for="total_amount">Total Amount</label>
            <input type="text" class="form-control" name="total_amount" value="<?= $car['price'] + $delivery_charge ?>" readonly>
        </div>

        <div class="form-group">
            <label for="delivery_charge">Delivery Charge</label>
            <input type="text" class="form-control" name="delivery_charge" value="<?= $delivery_charge ?>" readonly>
        </div>

        <button type="submit" class="btn btn-primary">Place Order</button>
    </form>
</div>

<?php include 'footer.php'; // Include customer footer ?>
