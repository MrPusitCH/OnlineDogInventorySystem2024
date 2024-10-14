<?php 
session_start();
include('front-end/db_connection.php'); 

// รับค่าจากช่องค้นหาและหมวดหมู่
$keyword = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : 'all';

// กำหนดเงื่อนไขการค้นหาตามหมวดหมู่ที่เลือก
$categoryQuery = "";
if ($category != 'all') {
    $categoryQuery = " AND category_id = '$category'";
}

// สร้างคำสั่ง SQL สำหรับการค้นหา
$sql = "SELECT * FROM products WHERE (name LIKE '%$keyword%' OR description LIKE '%$keyword%') $categoryQuery";
$result = $conn->query($sql);

// จำนวนสินค้าที่อยู่ในตะกร้า
$total_items = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;

// จัดการเพิ่มสินค้าในตะกร้า
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1; // จำนวนสินค้าเริ่มต้นที่ 1

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity; // เพิ่มจำนวนในตะกร้า
    } else {
        $_SESSION['cart'][$product_id] = $quantity; // สร้างใหม่ถ้าไม่มีในตะกร้า
    }
    header("Location: index.php"); // เปลี่ยนหน้าไปที่ index.php
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dog Breeds Shop</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="front-end/css/style.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #f8f9fa; /* เปลี่ยนสีพื้นหลังให้ดูสบายตา */
        }
        /* การตั้งค่าอนิเมชั่น */
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .fade-in {
            animation: fadeIn 0.5s ease-in-out forwards; /* เรียกใช้อนิเมชั่น */
        }
        .card-body .btn {
            margin-top: 10px; /* เพิ่มระยะห่างระหว่างปุ่ม */
        }
    </style>
</head>
<body>

<!-- แถบเมนู -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">
        <img src="front-end/images/logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
        Dog Breeds Shop
    </a>
    <form class="form-inline my-2 my-lg-0 ml-auto" action="index.php" method="GET">
        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="search">
        <select class="form-control mr-sm-2" name="category">
            <option value="all">All</option>
            <?php
            $category_sql = "SELECT * FROM categories";
            $category_result = $conn->query($category_sql);
            if ($category_result->num_rows > 0) {
                while($category_row = $category_result->fetch_assoc()) {
                    echo "<option value='{$category_row['id']}'>{$category_row['name']}</option>";
                }
            }
            ?>
        </select>
        <button class="btn btn-primary my-2 my-sm-0" type="submit">Go</button>
    </form>

    <ul class="navbar-nav ml-auto">
        <?php if (!isset($_SESSION['username'])) { ?>
            <li class="nav-item">
                <a class="nav-link btn btn-success text-white" href="front-end/login.php">Login</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="front-end/register.php">Register</a>
            </li>
        <?php } else { ?>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fa-solid fa-user"></i> <?php echo $_SESSION['username']; ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link btn btn-danger text-white" href="front-end/logout.php">Logout</a>
            </li>
        <?php } ?>
        
        <li class="nav-item">
            <a class="nav-link" href="front-end/cart.php">
                Cart <span class="badge badge-danger"><?php echo $total_items; ?></span>
            </a>
        </li>
    </ul>
</nav>

<!-- สไลด์โชว์ -->
<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" data-interval="5000">
    <ol class="carousel-indicators">
        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="front-end/images/bar/1.jpg" class="d-block w-100" alt="...">
        </div>
        <div class="carousel-item">
            <img src="front-end/images/bar/2.jpg" class="d-block w-100" alt="...">
        </div>
        <div class="carousel-item">
            <img src="front-end/images/bar/3.jpg" class="d-block w-100" alt="...">
        </div>
    </div>
    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>

<!-- ส่วนแสดงสินค้า -->
<div class="container mt-5">
    <div class="row">
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "
                <div class='col-md-4 fade-in'>
                    <div class='card mb-4 shadow-sm'>
                        <img src='front-end/images/{$row['image']}' class='card-img-top' alt='{$row['name']}' style='height: 200px; object-fit: cover;'>
                        <div class='card-body'>
                            <h5 class='card-title'>{$row['name']}</h5>
                            <p class='card-text'>Price: {$row['price']} บาท</p>
                            <p class='card-text'>Stock: {$row['stock']} ชิ้น</p> <!-- แสดงจำนวนสินค้า -->
                            <form method='POST' action='index.php'>
                                <input type='hidden' name='product_id' value='{$row['id']}'>
                                ";
                // เช็คว่าผู้ใช้ล็อกอินอยู่หรือไม่
                if (isset($_SESSION['username'])) {
                    echo "<button type='submit' name='add_to_cart' class='btn btn-primary'>Add to Cart</button>";
                } else {
                    echo "<button type='button' class='btn btn-primary' disabled>Add to Cart</button>";
                }
                echo "
                                <a href='front-end/product.php?id={$row['id']}' class='btn btn-secondary'>View Details</a>
                            </form>
                        </div>
                    </div>
                </div>";
            }
        } else {
            echo "<p>No products found.</p>";
        }
        ?>
    </div>
</div>

<!-- ส่วนท้าย -->
<footer class="bg-dark text-white text-center py-3">
    <p>&copy; 2024 Dog Breeds Shop. All Rights Reserved.</p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
