<?php
include 'header.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php'); // Redirect if not logged in as admin
    exit;
}
 

if (!isset($_GET['id'])) {
    echo "Invalid car ID.";
    exit;
}

$car_id = intval($_GET['id']);

// Fetch car details from the database
$car_query = "SELECT * FROM cars WHERE id = $car_id";
$car_result = mysqli_query($conn, $car_query);

if (mysqli_num_rows($car_result) == 0) {
    echo "Car not found.";
    exit;
}

$car = mysqli_fetch_assoc($car_result);

// Handle form submission for updating car details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $brand = mysqli_real_escape_string($conn, $_POST['brand']);
    $model = mysqli_real_escape_string($conn, $_POST['model']);
    $price = floatval($_POST['price']);
    $features = mysqli_real_escape_string($conn, $_POST['features']);
    $image = $_FILES['image']['name'];

    if (!empty($image)) {
        // Check if the file is an image
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $image_extension = pathinfo($image, PATHINFO_EXTENSION);
        if (!in_array(strtolower($image_extension), $allowed_extensions)) {
            echo "Invalid image format. Only JPG, JPEG, PNG, and GIF are allowed.";
            exit;
        }

        // Move uploaded image to the server folder
        $target_directory = 'uploads/';
        $target_file = '../'.$target_directory . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
        
        // Update car details with new image
        $update_query = "UPDATE cars SET brand = '$brand', model = '$model', image = '$image', price = '$price', features = '$features' WHERE id = $car_id";
    } else {
        // Update car details without changing the image
        $update_query = "UPDATE cars SET brand = '$brand', model = '$model', price = '$price', features = '$features' WHERE id = $car_id";
    }

    if (mysqli_query($conn, $update_query)) {
        $_SESSION['status_message'] = "Car details updated successfully!";
        header('Location: manage_cars.php'); // Redirect to dashboard after update
        exit;
    } else {
        echo "Error updating car details: " . mysqli_error($conn);
    }
}

?>

<div class="container mt-4">
  
    <form action=" " method="POST" enctype="multipart/form-data">
    <h2>Edit Car - <?= $car['brand'] . ' ' . $car['model'] ?></h2>
        <div class="form-group">
            <label for="brand">Brand</label>
            <input type="text" name="brand" id="brand" class="form-control" value="<?= $car['brand'] ?>" required>
        </div>
        <div class="form-group">
            <label for="model">Model</label>
            <input type="text" name="model" id="model" class="form-control" value="<?= $car['model'] ?>" required>
        </div>
        <div class="form-group">
            <label for="price">Price (PKR)</label>
            <input type="number" name="price" id="price" class="form-control" value="<?= $car['price'] ?>" required>
        </div>
        <div class="form-group">
            <label for="features">Features</label>
            <textarea name="features" id="features" class="form-control" required><?= $car['features'] ?></textarea>
        </div>
        <div class="form-group">
            <label for="image">Car Image (Optional)</label>
            <input type="file" name="image" id="image" class="form-control-file">
            <small>Current image: <img src="../uploads/<?= $car['image'] ?>" width="100"></small>
        </div>
        <button type="submit" class="btn btn-primary">Update Car</button>
    </form>
</div>

<?php include 'footer.php'; ?>
