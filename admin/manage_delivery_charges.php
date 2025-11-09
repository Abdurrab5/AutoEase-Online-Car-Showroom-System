<?php
include 'header.php';
 

// Handle add/edit form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $charge = floatval($_POST['charge']);

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Edit existing
        $id = intval($_POST['id']);
        $query = "UPDATE delivery_charges SET city='$city', charge='$charge' WHERE id=$id";
    } else {
        // Add new
        $query = "INSERT INTO delivery_charges (city, charge) VALUES ('$city', '$charge')";
    }

    mysqli_query($conn, $query);
    header("Location: manage_delivery_charges.php");
    exit;
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM delivery_charges WHERE id=$id");
    header("Location: manage_delivery_charges.php");
    exit;
}

// Fetch all charges
$charges_result = mysqli_query($conn, "SELECT * FROM delivery_charges ORDER BY city ASC");

// If editing
$edit_mode = false;
$edit_data = ['id' => '', 'city' => '', 'charge' => ''];
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $id = intval($_GET['edit']);
    $result = mysqli_query($conn, "SELECT * FROM delivery_charges WHERE id=$id");
    if ($row = mysqli_fetch_assoc($result)) {
        $edit_data = $row;
    }
}
?>

<div class="container mt-4">
    <h2>Manage Delivery Charges</h2>

    <form method="post" class="mb-4">
        <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
        <div class="row">
            <div class="col-md-4">
                <label>City</label>
                <input type="text" name="city" class="form-control" required value="<?= htmlspecialchars($edit_data['city']) ?>">
            </div>
            <div class="col-md-4">
                <label>Charge (PKR)</label>
                <input type="number" name="charge" class="form-control" step="0.01" required value="<?= $edit_data['charge'] ?>">
            </div>
            <div class="col-md-4 mt-4">
                <button type="submit" class="btn btn-primary mt-2"><?= $edit_mode ? 'Update' : 'Add' ?> Charge</button>
                <?php if ($edit_mode): ?>
                    <a href="manage_delivery_charges.php" class="btn btn-secondary mt-2">Cancel</a>
                <?php endif; ?>
            </div>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#ID</th>
                <th>City</th>
                <th>Charge (PKR)</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($charge = mysqli_fetch_assoc($charges_result)): ?>
            <tr>
                <td><?= $charge['id'] ?></td>
                <td><?= htmlspecialchars($charge['city']) ?></td>
                <td><?= number_format($charge['charge'], 2) ?></td>
                <td><?= $charge['created_at'] ?></td>
                <td>
                    <a href="manage_delivery_charges.php?edit=<?= $charge['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="manage_delivery_charges.php?delete=<?= $charge['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this delivery charge?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
