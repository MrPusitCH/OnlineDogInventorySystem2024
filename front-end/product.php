<?php 
session_start();
include('db_connection.php');

$id = $_GET['id'];
$sql = "SELECT * FROM products WHERE id='$id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    echo "Product not found.";
    exit;
}

// ตรวจสอบว่ามีการส่งข้อมูลจากฟอร์ม
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // เพิ่มสินค้าในตะกร้า
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity; // ถ้ามีสินค้านี้แล้ว เพิ่มจำนวน
    } else {
        $_SESSION['cart'][$product_id] = $quantity; // ถ้ายังไม่มีให้เพิ่มสินค้าใหม่
    }

    // กลับไปยังหน้าร้านค้า
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $product['name']; ?> - Dog Breeds Shop</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa; /* พื้นหลังสีอ่อน */
            font-family: 'Arial', sans-serif;
        }
        .product-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh; /* ทำให้เนื้อหาตรงกลางทั้งแนวตั้งและแนวนอน */
            padding: 20px;
        }
        .product-img {
            width: 350px;
            height: 450px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .product-img:hover {
            transform: scale(1.05);
        }
        .product-details {
            text-align: center;
            margin-top: 20px;
        }
        .product-details h2 {
            margin-bottom: 15px;
        }
        .product-details p {
            font-size: 18px;
        }
        .product-details .price {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
        }
        .back-btn {
            margin-top: 30px;
            background-color: #6c757d;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .back-btn:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container product-container">
        <img src="images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="product-img">
        <div class="product-details">
            <h2><?php echo $product['name']; ?></h2>
            <p><?php echo $product['description']; ?></p>
            <p class="price">Price: <?php echo $product['price']; ?> บาท</p>
            <p>In Stock: <?php echo $product['stock']; ?></p>

            <form action="" method="POST">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <input type="hidden" name="quantity" value="1"> <!-- ตั้งค่าปริมาณสินค้า -->
                <button type="submit" name="add_to_cart" class="btn btn-primary mt-2">Add to Cart</button>
            </form>
        </div>

        <!-- ปุ่มกลับไปยังหน้าร้านค้า -->
        <a href="../index.php" class="back-btn">Back to Shop</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
