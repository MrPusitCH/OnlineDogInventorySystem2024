<?php
session_start();
session_destroy();  // ทำลาย session ทั้งหมด
header("Location: ../index.php");  // กลับไปที่หน้าแรก
exit();
?>
