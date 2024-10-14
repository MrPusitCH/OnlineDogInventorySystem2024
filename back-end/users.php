<?php
session_start();

// ตรวจสอบว่าเป็น Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include('db_connection.php');

// ดึงข้อมูลผู้ใช้ทั้งหมดมาแสดง
$sql = "SELECT * FROM users WHERE role != 'admin'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
    <h1>Manage Users</h1>
    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Role</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['username']}</td>
                    <td>{$row['role']}</td>
                    <td>{$row['first_name']}</td>
                    <td>{$row['last_name']}</td>
                    <td>{$row['email']}</td>
                    <td><a href='edit_user.php?id={$row['id']}'>Edit</a> | <a href='delete_user.php?id={$row['id']}'>Delete</a></td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
