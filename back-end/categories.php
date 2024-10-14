<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'manager')) {
    header("Location: ../front-end/login.php");
    exit;
}

include('../front-end/db_connection.php');

// เพิ่มหมวดหมู่
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_category'])) {
    $category_name = $_POST['category_name'];
    $sql = "INSERT INTO categories (name) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $category_name);
    
    if ($stmt->execute()) {
        echo "<script>alert('Category added successfully!'); window.location.href = 'categories.php';</script>";
    } else {
        echo "<script>alert('Error: Could not add category.');</script>";
    }
}

// ลบหมวดหมู่
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM categories WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Category deleted successfully!'); window.location.href = 'categories.php';</script>";
    } else {
        echo "<script>alert('Error: Could not delete category.');</script>";
    }
}

// ดึงข้อมูลหมวดหมู่ที่มีอยู่
$sql = "SELECT * FROM categories";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Categories</title>
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

        .btn-danger {
            background-color: #dc3545;
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
        <h1 class="text-center mb-4">Manage Categories</h1>

        <!-- ฟอร์มสำหรับเพิ่มหมวดหมู่ -->
        <form method="POST" action="" class="mb-4">
            <div class="form-group">
                <label for="category_name">Category Name</label>
                <input type="text" class="form-control" id="category_name" name="category_name" placeholder="Enter new category name" required>
            </div>
            <button type="submit" name="add_category" class="btn btn-primary btn-custom">Add Category</button>
        </form>

        <!-- ตารางแสดงหมวดหมู่ที่มีอยู่ -->
        <div class="table-responsive table-container">
            <table class="table table-hover table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Category Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td>
                            <a href="categories.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this category?');">
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
