<?php include 'header.php'; ?>
<?php


$cars = mysqli_query($conn, "SELECT * FROM cars  ");
?>

<h2>Manage Cars</h2>
<a href="add_car.php" class="btn btn-success mb-3">+ Add New Car</a>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
         <th>ID</th>
            <th>Image</th>
            <th>Brand</th>
            <th>Model</th>
            <th>Price</th>
            <th>Available</th>
            <th>Features</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($car = mysqli_fetch_assoc($cars)): ?>
            <tr>
            <td><?= $car['id'] ?></td>
                <td><img src="../uploads/<?= $car['image'] ?>" width="100"></td>
                <td><?= $car['brand'] ?></td>
                <td><?= $car['model'] ?></td>
                <td>PKR <?= number_format($car['price']) ?></td>
                <td><?= $car['available'] ? 'Yes' : 'No' ?></td>
                <td><?= $car['features'] ?></td>
                <td>
                    <a href="edit_car.php?id=<?= $car['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                    <a href="delete_car.php?id=<?= $car['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to delete this car?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include 'footer.php'; ?>
