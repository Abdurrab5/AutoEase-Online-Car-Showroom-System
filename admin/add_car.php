<?php
include 'header.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php'); // Redirect if not logged in as admin
    exit;
}
 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $brand = mysqli_real_escape_string($conn, $_POST['brand']);
    $model = mysqli_real_escape_string($conn, $_POST['model']);
    $price = floatval($_POST['price']);
    $features = mysqli_real_escape_string($conn, $_POST['features']);
    $image = $_FILES['image']['name'];

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

    // Insert car details into the database
    $insert_car_query = "INSERT INTO cars (brand, model, image, price, features, available)
                         VALUES ('$brand', '$model', '$image', '$price', '$features', 1)";
    if (mysqli_query($conn, $insert_car_query)) {
        $_SESSION['status_message'] = "Car added successfully!";
        header('Location: dashboard.php'); // Redirect to dashboard after successful addition
        exit;
    } else {
        echo "Error adding car: " . mysqli_error($conn);
    }
}

?>

<div class="container mt-4">
    
    <form action="add_car.php" method="POST" enctype="multipart/form-data">
    <h2>Add New Car</h2>
        <div class="form-group">
            <label for="brand">Brand</label>
            <input type="text" name="brand" id="brand" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="model">Model</label>
            <input type="text" name="model" id="model" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="price">Price (PKR)</label>
            <input type="number" name="price" id="price" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="features">Features</label>
            <textarea name="features" id="features" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label for="image">Car Image</label>
            <input type="file" name="image" id="image" class="form-control-file" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Car</button>
    </form>
</div>

<?php include 'footer.php'; ?>
