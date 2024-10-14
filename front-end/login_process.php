<?php
session_start();
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // ใช้ prepared statements เพื่อความปลอดภัย
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // เปรียบเทียบรหัสผ่าน (ตรวจสอบตรงๆ)
        if ($user['password'] === $password) { 
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // ตรวจสอบบทบาทและเปลี่ยนหน้า
            if ($user['role'] == 'admin') {
                header("Location: ../back-end/admin_panel.php");
            } elseif ($user['role'] == 'manager') {
                header("Location: ../back-end/manager_panel.php");
            } else {
                header("Location: ../index.php");
            }
            exit;
        } else {
            // แจ้งเตือนรหัสผ่านไม่ถูกต้อง
            $_SESSION['login_error'] = "Incorrect password. Please try again.";
            header("Location: login.php");
            exit;
        }
    } else {
        // แจ้งเตือนชื่อผู้ใช้ไม่ถูกต้อง
        $_SESSION['login_error'] = "Username not found. Please try again.";
        header("Location: login.php");
        exit;
    }
}
?>
