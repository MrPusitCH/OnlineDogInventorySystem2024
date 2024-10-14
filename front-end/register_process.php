<?php
session_start();
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    // เก็บข้อมูลที่กรอกลงใน session
    $_SESSION['register_username'] = $username;
    $_SESSION['register_email'] = $email;
    $_SESSION['register_password'] = $password;
    $_SESSION['register_first_name'] = $first_name;
    $_SESSION['register_last_name'] = $last_name;
    $_SESSION['register_dob'] = $dob;
    $_SESSION['register_gender'] = $gender;
    $_SESSION['register_address'] = $address;
    $_SESSION['register_phone'] = $phone;

    // ตรวจสอบว่าอีเมลมีอยู่ในระบบหรือยัง
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // ถ้าอีเมลซ้ำ ให้ส่งข้อความแจ้งเตือนกลับไปยังหน้า register.php
        $_SESSION['register_error'] = "Email already exists! Please enter a different email.";
        header("Location: register.php");
        exit;
    } else {
        // ถ้าอีเมลไม่ซ้ำ ให้เพิ่มข้อมูลลงฐานข้อมูล
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, first_name, last_name, dob, gender, address, phone) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $username, $email, $password, $first_name, $last_name, $dob, $gender, $address, $phone);
        if ($stmt->execute()) {
            $_SESSION['register_success'] = "Registration successful! You can now login.";
            // ลบข้อมูลที่เก็บไว้ใน session
            session_unset();
            session_destroy();
            header("Location: login.php");
            exit;
        } else {
            $_SESSION['register_error'] = "Error registering. Please try again.";
            header("Location: register.php");
            exit;
        }
    }
}
?>
