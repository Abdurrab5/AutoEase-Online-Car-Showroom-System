<?php
include 'header.php';
if (!isset($_GET['id'])) {
    echo "<p>Car not found.</p>";
    include 'footer.php';
    exit;
}

$id = intval($_GET['id']);
$car_query = mysqli_query($conn, "SELECT * FROM cars WHERE id = $id AND available = 1 LIMIT 1");
if (mysqli_num_rows($car_query) === 0) {
    echo "<p>Car not found or unavailable.</p>";
    include 'footer.php';
    exit;
}

$car = mysqli_fetch_assoc($car_query);

// Installment Plans
$installment_plans = [];
$plan_query = mysqli_query($conn, "SELECT * FROM installment_plans");
while ($plan = mysqli_fetch_assoc($plan_query)) {
    $installment_plans[] = $plan;
}

// Delivery Cities
$delivery_result = mysqli_query($conn, "SELECT * FROM delivery_charges");
$delivery_cities = [];
while ($row = mysqli_fetch_assoc($delivery_result)) {
    $delivery_cities[] = $row;
}
?>

<div class="container mt-4">
    <h2><?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?></h2>
    <div class="row">
        <div class="col-md-6">
            <img src="uploads/<?= htmlspecialchars($car['image']) ?>" class="img-fluid" style="max-height: 400px;">
        </div>
        <div class="col-md-6">
            <p><strong>Price:</strong> PKR <?= number_format($car['price']) ?></p>
            <p><strong>Features:</strong><br><?= nl2br(htmlspecialchars($car['features'])) ?></p>

            <form method="POST" action="place_order.php">
                <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
                <input type="hidden" name="delivery_charge" id="delivery_charge">
                <input type="hidden" name="total_amount" id="total_amount">
                <input type="hidden" name="down_payment_amount" id="down_payment_amount">
                <input type="hidden" name="down_payment_method" value="online">
                <input type="hidden" name="delivery_charge_method" value="standard">

                <div class="mb-3">
                    <label for="city" class="form-label">Select Delivery City:</label>
                    <select name="city" id="city" class="form-select" required>
                        <option value="">-- Select City --</option>
                        <?php foreach ($delivery_cities as $city): ?>
                            <option value="<?= htmlspecialchars($city['city']) ?>"
                                    data-charge="<?= $city['charge'] ?>">
                                <?= htmlspecialchars($city['city']) ?> (PKR <?= number_format($city['charge']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Payment Method:</label><br>
                    <input type="radio" name="payment_type" value="full" required> Full Payment<br>
                    <input type="radio" name="payment_type" value="installment"> Installment Plan
                </div>

                <div id="installment_section" class="d-none">
                    <div class="mb-3">
                        <label for="installment_plan" class="form-label">Select Installment Plan:</label>
                        <select name="installment_plan" id="installment_plan" class="form-select">
                            <option value="">-- Select Plan --</option>
                            <?php foreach ($installment_plans as $plan): ?>
                                <option value="<?= $plan['id'] ?>"
                                        data-duration="<?= $plan['duration_months'] ?>"
                                        data-interest="<?= $plan['interest_rate_percent'] ?>"
                                        data-downpayment="<?= $plan['down_payment_percent'] ?>">
                                    <?= $plan['duration_months'] ?> Months (Interest: <?= $plan['interest_rate_percent'] ?>%, Down: <?= $plan['down_payment_percent'] ?>%)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="down_payment" class="form-label">Down Payment:</label>
                        <input type="text" id="down_payment" class="form-control" readonly>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Delivery Address:</label>
                    <textarea name="address" id="address" rows="3" class="form-control" required></textarea>
                </div>

                <button type="submit" class="btn btn-success">Place Order</button>
            </form>
        </div>
    </div>
</div>

<script>
    const carPrice = <?= (int) $car['price'] ?>;
    const citySelect = document.getElementById('city');
    const deliveryChargeInput = document.getElementById('delivery_charge');
    const totalAmountInput = document.getElementById('total_amount');
    const downPaymentInput = document.getElementById('down_payment_amount');

    const paymentRadios = document.querySelectorAll('input[name="payment_type"]');
    const installmentSection = document.getElementById('installment_section');
    const installmentPlanSelect = document.getElementById('installment_plan');
    const downPaymentField = document.getElementById('down_payment');

    let currentCharge = 0;

    citySelect.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        currentCharge = parseFloat(selectedOption.dataset.charge || 0);
        deliveryChargeInput.value = currentCharge;
        totalAmountInput.value = carPrice + currentCharge;
    });

    paymentRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            if (this.value === 'installment') {
                installmentSection.classList.remove('d-none');
            } else {
                installmentSection.classList.add('d-none');
                downPaymentField.value = '';
                downPaymentInput.value = '';
            }
        });
    });

    installmentPlanSelect.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        const downPercent = parseFloat(selected.dataset.downpayment || 0);
        const downAmount = Math.round(carPrice * (downPercent / 100));
        downPaymentField.value = `PKR ${new Intl.NumberFormat().format(downAmount)}`;
        downPaymentInput.value = downAmount;
    });
</script>

<?php include 'footer.php'; ?>
