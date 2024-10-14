<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'manager') {
    header("Location: ../front-end/login.php");
    exit();
}

include('db_connection.php');

// ลบสินค้าตาม id ที่ได้รับ
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    echo "<script>alert('Product deleted successfully!'); window.location.href = 'edit_product.php';</script>";
}

// ปรับจำนวนสินค้า
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['adjust_stock'])) {
    $id = $_POST['id'];
    $adjust_amount = $_POST['adjust_amount'];

    // ปรับจำนวนสินค้าในฐานข้อมูล
    $sql = "UPDATE products SET stock = stock + ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $adjust_amount, $id);
    $stmt->execute();

    echo "<script>alert('Stock updated successfully!'); window.location.href = 'edit_product.php';</script>";
}

// เปลี่ยนหมวดหมู่สินค้า
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_category'])) {
    $id = $_POST['id'];
    $new_category = $_POST['category_id'];

    // อัปเดตหมวดหมู่ในฐานข้อมูล
    $sql = "UPDATE products SET category_id = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $new_category, $id);
    $stmt->execute();

    echo "<script>alert('Category updated successfully!'); window.location.href = 'edit_product.php';</script>";
}

// ค้นหาสินค้าจากชื่อ
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT * FROM products WHERE name LIKE ?";
$search_param = "%" . $search . "%";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $search_param);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products</title>
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
            background-color: #dc3545;
            color: white;
            transition: background-color 0.3s ease;
        }

        .btn-custom:hover {
            background-color: #c82333;
        }

        .form-container {
            padding-top: 30px;
            animation: fadeIn 1s ease-in-out;
        }

        .table-container {
            margin-top: 30px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container form-container">
        <h1 class="text-center mb-4">Manage Products</h1>

        <!-- ฟอร์มค้นหาสินค้า -->
        <form method="GET" action="edit_product.php" class="mb-4">
            <div class="form-group">
                <input type="text" class="form-control" name="search" placeholder="Search products by name" value="<?php echo $search; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <div class="table-responsive table-container">
            <table class="table table-hover table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Category ID</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td><?php echo $row['price']; ?></td>
                        <td><?php echo $row['stock']; ?></td>
                        <td><?php echo $row['category_id']; ?></td>
                        <td>
                            <!-- ฟอร์มสำหรับปรับสต็อก -->
                            <form action="edit_product.php" method="POST" class="form-inline">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <input type="number" name="adjust_amount" class="form-control mr-2" placeholder="Adjust stock" required>
                                <button type="submit" name="adjust_stock" class="btn btn-success btn-sm mr-2">Adjust</button>
                            </form>

                            <!-- ฟอร์มสำหรับเปลี่ยนหมวดหมู่ -->
                            <form action="edit_product.php" method="POST" class="form-inline mt-2">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <select name="category_id" class="form-control mr-2">
                                    <?php
                                    $category_sql = "SELECT * FROM categories";
                                    $category_result = $conn->query($category_sql);
                                    while ($category = $category_result->fetch_assoc()) {
                                        $selected = $category['id'] == $row['category_id'] ? 'selected' : '';
                                        echo "<option value='{$category['id']}' $selected>{$category['name']}</option>";
                                    }
                                    ?>
                                </select>
                                <button type="submit" name="change_category" class="btn btn-info btn-sm">Change Category</button>
                            </form>

                            <!-- ลบสินค้า -->
                            <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm btn-custom mt-2" onclick="return confirm('Are you sure you want to delete this product?');">
                                Delete
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <a href="manager_panel.php" class="btn btn-link mt-3">Back to Manager Panel</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
