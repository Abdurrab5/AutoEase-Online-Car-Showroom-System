<?php
include 'header.php';

// Get all instalments that are due and not yet paid
$instalment_query = "SELECT instalments.*, customers.email 
                    FROM instalments
                    INNER JOIN orders ON instalments.order_id = orders.id
                    INNER JOIN customers ON orders.customer_id = customers.id
                    WHERE instalments.status = 'Pending' AND instalments.due_date <= CURDATE()";

$instalment_result = mysqli_query($conn, $instalment_query);

while ($instalment = mysqli_fetch_assoc($instalment_result)) {
    $email = $instalment['email'];
    $amount = $instalment['amount'];
    $instalment_number = $instalment['instalment_number'];

    // Send an email reminder
    mail($email, "Instalment Payment Reminder", "Dear Customer, you have a pending instalment of Rs $amount for instalment number $instalment_number. Please make the payment at the earliest.");
}
?>
