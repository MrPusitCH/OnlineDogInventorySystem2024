<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'manager') {
    header("Location: ../front-end/login.php");
    exit();
}

include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category_id = $_POST['category_id'];

    // จัดการการอัปโหลดรูปภาพ
    $image = $_FILES['image']['name'];
    $target_dir = "../front-end/images/";
    $target_file = $target_dir . basename($image);
    
    // ตรวจสอบว่ารูปภาพอัปโหลดสำเร็จหรือไม่
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        // เพิ่มข้อมูลสินค้าพร้อมรูปภาพลงในฐานข้อมูล
        $sql = "INSERT INTO products (name, description, price, stock, image, category_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdiss", $name, $description, $price, $stock, $image, $category_id);
        $stmt->execute();
        header("Location: manager_panel.php");
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

$sql = "SELECT * FROM categories";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
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

        .form-container {
            padding-top: 30px;
            animation: fadeIn 1s ease-in-out;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container form-container">
        <h1 class="text-center mb-4">Add Product</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Product Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label>Price</label>
                <input type="number" step="0.01" name="price" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Stock</label>
                <input type="number" name="stock" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Category</label>
                <select name="category_id" class="form-control">
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label>Product Image</label>
                <input type="file" name="image" class="form-control-file" required>
            </div>
            <button type="submit" class="btn btn-success btn-custom mt-3">Add Product</button>
        </form>
        <a href="manager_panel.php" class="btn btn-link mt-3">Back to Manager Panel</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
