<?php
session_start();
include '../includes/db_connect.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>AutoEase - Online Car Showroom</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg  ">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">AutoEase</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="index.php">HOME</a></li>
        <?php if (isset($_SESSION['admin_logged_in'])): ?>
          <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="manage_users.php">Manage Users</a></li>
         <li class="nav-item"><a class="nav-link" href="manage_cars.php">Manage Cars</a></li>
          
          <li class="nav-item"><a class="nav-link" href="view_order.php">View Order</a></li>
          <li class="nav-item"><a class="nav-link" href="view_orders.php">View Orders</a></li>
          <li class="nav-item"><a class="nav-link" href="manage_installments.php">Manage Installments </a></li>
          <li class="nav-item"><a class="nav-link" href="manage_installment_plans.php">Manage Installments Plan</a></li>
          <li class="nav-item"><a class="nav-link" href="manage_delivery_charges.php">Manage Delivery Charges </a></li>
          <li class="nav-item"><a class="nav-link" href="manage_payments.php">Manage Payments </a></li>
          
        
        
          <?php endif; ?>
      </ul>

      <ul class="navbar-nav ms-auto">
        <?php if (isset($_SESSION['admin_logged_in'])): ?>
         
          <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        <?php else: ?>
        
           <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        <?php endif; ?>
        <li class="nav-item"><a class="nav-link" > Admin Panel</a></li>
      
      </ul>  
    </div>
  </div>
</nav>

<div class="container mt-4">
