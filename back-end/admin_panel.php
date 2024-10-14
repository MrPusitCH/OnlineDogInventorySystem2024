<?php
session_start();

// ตรวจสอบว่าเป็นผู้ดูแลระบบหรือไม่
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../front-end/login.php");
    exit();
}

include('db_connection.php');

// ดึงข้อมูลทั้งหมดของผู้จัดการและลูกค้า
$sql = "SELECT * FROM users WHERE role IN ('manager', 'customer')";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/admin_style.css">
    <style>
        /* แอนิเมชันการเลื่อน */
        .fade-in {
            animation: fadeIn 0.8s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
            transition: background-color 0.3s ease;
        }
        
        .btn-custom {
            background-color: #007bff;
            color: white;
            transition: background-color 0.3s ease;
        }

        .btn-custom:hover {
            background-color: #0056b3;
        }

        .table {
            animation: fadeIn 1s ease-in-out;
        }

        h1, h2 {
            margin-bottom: 20px;
        }

        .header {
            padding-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container">
        <div class="header">
            <h1 class="fade-in">Admin Panel</h1>
            <h2 class="fade-in">Manage Accounts</h2>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-bordered fade-in">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['role']; ?></td>
                            <td><?php echo $row['first_name']; ?></td>
                            <td><?php echo $row['last_name']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['phone']; ?></td>
                            <td>
                                <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete_user.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="add_user.php" class="btn btn-success btn-custom">Add New User</a>
            <a href="../front-end/logout.php" class="btn btn-danger btn-custom">Logout</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
