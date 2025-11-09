<?php include 'header.php'; ?>
<?php


$cars = mysqli_query($conn, "SELECT * FROM cars WHERE available = 1 ");
?>

<h2>Available Cars</h2>

<div class="row">
    <?php while ($car = mysqli_fetch_assoc($cars)): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <img src="uploads/<?= $car['image'] ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title"><?= $car['brand'] . ' ' . $car['model'] ?></h5>
                    <p class="card-text"><strong>Price:</strong> PKR <?= number_format($car['price']) ?></p>
                    <p class="card-text"><?= substr($car['features'], 0, 100) ?>...</p>
                    <a href="view_car.php?id=<?= $car['id'] ?>" class="btn btn-primary">View Details / Order</a>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<?php include 'footer.php'; ?>
