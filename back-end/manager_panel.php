<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'manager') {
    header("Location: ../front-end/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manager Panel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .fade-in {
            animation: fadeIn 0.8s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .btn-custom {
            background-color: #007bff;
            color: white;
            transition: background-color 0.3s ease;
        }

        .btn-custom:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container fade-in">
        <h1 class="text-center my-4">Manager Panel</h1>
        <div class="list-group">
            <a href="add_product.php" class="list-group-item list-group-item-action">Add Product</a>
            <a href="edit_product.php" class="list-group-item list-group-item-action">Edit Product</a>
            <a href="categories.php" class="list-group-item list-group-item-action">Manage Categories</a>
            <a href="view_orders.php" class="list-group-item list-group-item-action">View Orders</a>
            <a href="../front-end/logout.php" class="list-group-item list-group-item-action text-danger">Logout</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
