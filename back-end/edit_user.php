<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../front-end/login.php");
    exit();
}

include('db_connection.php');

// ตรวจสอบว่ามี ID ผู้ใช้หรือไม่
if (!isset($_GET['id'])) {
    header("Location: admin_panel.php");
    exit();
}

$id = $_GET['id'];

// ดึงข้อมูลผู้ใช้ที่ต้องการแก้ไข
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // อัปเดตข้อมูลผู้ใช้
    $update_sql = "UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssssi", $first_name, $last_name, $email, $phone, $id);
    $stmt->execute();

    header("Location: admin_panel.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* การตกแต่งและแอนิเมชัน */
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
            animation: fadeIn 1s ease-in-out;
            padding-top: 30px;
        }

        .form-group label {
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container form-container">
        <h1 class="fade-in text-center">Edit User Information</h1>
        <form method="POST" class="fade-in">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="first_name" value="<?php echo $user['first_name']; ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="last_name" value="<?php echo $user['last_name']; ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?php echo $user['email']; ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" value="<?php echo $user['phone']; ?>" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success btn-custom mt-3">Update User</button>
        </form>
        <a href="admin_panel.php" class="btn btn-link mt-3">Back to Admin Panel</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
