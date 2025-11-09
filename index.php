<?php include 'includes/db_connect.php'; ?>
<?php include 'header.php'; ?>

 
 

    <div class="row">
        <?php
        $result = $conn->query("SELECT * FROM cars WHERE available = 1 ORDER BY id DESC LIMIT 6");
        while ($row = $result->fetch_assoc()) {
        ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="uploads/<?php echo $row['image']; ?>" class="card-img-top" alt="Car Image" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['brand'] . ' ' . $row['model']; ?></h5>
                        <p class="card-text"><strong>Price:</strong> Rs. <?php echo number_format($row['price']); ?></p>
                        <p class="card-text"><?php echo substr($row['features'], 0, 80); ?>...</p>
                    </div>
                    <div class="card-footer bg-white border-top-0 text-center">
                    <?php if (isset($_SESSION['customer_id'])): ?>
           <a href="car_details.php?id=<?= $row['id'] ?>" class="btn btn-primary">View Details / Order</a> 
        <?php else: ?>
            <a href="login.php" class="btn btn-outline-primary btn-sm">Login to Order</a>
        <?php endif; ?> 
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?php include 'footer.php'; ?>
