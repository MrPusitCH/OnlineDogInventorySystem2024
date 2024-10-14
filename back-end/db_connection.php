<?php
$servername = "localhost";
$username = "root"; // เปลี่ยนตามการตั้งค่าฐานข้อมูลของคุณ
$password = ""; // เปลี่ยนตามการตั้งค่าฐานข้อมูลของคุณ
$dbname = "dog_breeds_shop";

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
