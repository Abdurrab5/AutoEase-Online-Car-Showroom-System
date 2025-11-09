<?php
include '../includes/db_connect.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $delete_query = "DELETE FROM customers WHERE id = $id";

    if (mysqli_query($conn, $delete_query)) {
        header("Location: manage_users.php?msg=Customer deleted");
    } else {
        echo "Error deleting user: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request.";
}
?>
