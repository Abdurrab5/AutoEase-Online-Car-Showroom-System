<?php include 'header.php'; ?>

<form method="post" action="register_process.php">
<h2>Customer Registration</h2>
    <label>Name:</label><br>
    <input type="text" name="name" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <label>Phone:</label><br>
    <input type="text" name="phone" required><br><br>

    <label>City:</label><br>
    <input type="text" name="city" required><br><br>

    <label>Guarantor Name:</label><br>
    <input type="text" name="guarantor_name" required><br><br>

    <label>Guarantor Bank Details:</label><br>
    <textarea name="guarantor_bank_details" required></textarea><br><br>

    <input type="submit" name="register" value="Register">
</form>
<?php include 'footer.php'; ?>
