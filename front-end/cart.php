<?php
session_start();
if (!isset($_SESSION['username'])) {  // ตรวจสอบว่าผู้ใช้ล็อกอินหรือยัง
    header("Location: ../login.php");
    exit;
}

include('db_connection.php');

// ดึงข้อมูลที่อยู่และหมายเลขโทรศัพท์จากฐานข้อมูล
$username = $_SESSION['username'];
$sql = "SELECT address, phone FROM users WHERE username = '$username'";
$result = $conn->query($sql);
$user_data = $result->fetch_assoc();  // เก็บที่อยู่และหมายเลขโทรศัพท์

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // อัปเดตหรือลบสินค้าในตะกร้า
    if (isset($_POST['remove'])) {
        $product_id = $_POST['product_id'];
        unset($_SESSION['cart'][$product_id]);  // ลบสินค้าออกจากตะกร้า
    } else if (isset($_POST['update'])) {
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        $_SESSION['cart'][$product_id] = $quantity;  // แก้ไขจำนวนสินค้าในตะกร้า
    } else if (isset($_POST['proceed'])) {
        // เมื่อกดปุ่ม Proceed
        $address = $_POST['address'] == 'new_address' ? $_POST['new_address'] : $user_data['address'];
        $phone = $_POST['phone'] == 'new_phone' ? $_POST['new_phone'] : $user_data['phone'];
        $payment_method = $_POST['payment_method']; // เก็บวิธีชำระเงิน

        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            // ลดจำนวนสินค้าลงในฐานข้อมูล
            $sql_update = "UPDATE products SET stock = stock - $quantity WHERE id = $product_id";
            $conn->query($sql_update);

            // บันทึกคำสั่งซื้อใหม่ลงในตาราง orders
            $user_id = $_SESSION['user_id'];  // สมมติว่า user_id ถูกเก็บไว้ใน session
            $sql_insert_order = "INSERT INTO orders (user_id, product_id, quantity, status) 
                                 VALUES ('$user_id', '$product_id', '$quantity', 'pending')";
            $conn->query($sql_insert_order);
        }

        // ล้างตะกร้าสินค้า
        unset($_SESSION['cart']);

        // แสดง popup ว่าสั่งซื้อสำเร็จและเปลี่ยนเส้นทางไปยังหน้าหลัก
        echo "<script>alert('Your order has been placed successfully!'); window.location.href = '../index.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .cart-item {
            animation: fadeIn 0.5s ease-in-out;
            background-color: #f9f9f9;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 10px;
            border: 1px solid #ddd;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .btn-custom {
            cursor: pointer;
            border-radius: 5px;
            padding: 5px 10px;
            color: white;
        }

        .btn-remove {
            background-color: #ff4d4d;
        }

        .btn-update {
            background-color: #4caf50;
        }

        .btn-checkout {
            background-color: #ff9900;
            margin-top: 20px;
        }

        .payment-method, .shipping-info {
            margin-top: 20px;
        }

        .shipping-info {
            background-color: #f0f8ff;
            padding: 20px;
            border-radius: 10px;
        }

        .shipping-info h3 {
            color: #007bff;
        }

        .payment-method h3 {
            color: #ff5722;
        }

        .list-group-item {
            border: 1px solid #ddd;
        }

        .custom-radio {
            background-color: #ffe0b3;
            padding: 10px;
            border-radius: 10px;
        }

        .btn-checkout:hover {
            background-color: #e68a00;
        }

        .btn-remove:hover {
            background-color: #e60000;
        }

        .btn-update:hover {
            background-color: #43a047;
        }

        .payment-icons {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <header>
            <h1 class="text-center mb-4">Your Cart</h1>
        </header>

        <main>
            <h2 class="mb-4">Items in your cart:</h2>
            <ul class="list-group">
                <?php
                if (!empty($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $id => $quantity) {
                        $sql = "SELECT * FROM products WHERE id='$id'";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            $product = $result->fetch_assoc();
                            echo "
                            <li class='cart-item list-group-item'>
                                <div class='row'>
                                    <div class='col-md-6'>
                                        <h5>{$product['name']}</h5>
                                        <p>Quantity: $quantity</p>
                                    </div>
                                    <div class='col-md-6 text-right'>
                                        <form method='POST' class='form-inline'>
                                            <input type='hidden' name='product_id' value='$id'>
                                            <input type='number' name='quantity' value='$quantity' min='1' max='{$product['stock']}' class='form-control mr-2' required>
                                            <button type='submit' name='update' class='btn btn-update btn-custom mr-2'>Update</button>
                                            <button type='submit' name='remove' class='btn btn-remove btn-custom'>Remove</button>
                                        </form>
                                    </div>
                                </div>
                            </li>";
                        }
                    }
                } else {
                    echo "<p class='text-danger'>Your cart is empty.</p>";
                }
                ?>
            </ul>

            <!-- ฟอร์มที่อยู่ เบอร์โทรศัพท์ และวิธีชำระเงิน -->
            <div class="shipping-info mt-4">
                <h3>Shipping Information & Payment Method:</h3>
                <form method="POST">
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <select id="address" name="address" class="form-control mb-3">
                            <option value="<?= $user_data['address'] ?>">Use Registered Address (<?= $user_data['address'] ?>)</option>
                            <option value="new_address">Use New Address</option>
                        </select>
                        <textarea id="new_address" name="new_address" rows="3" class="form-control" style="display: none;" placeholder="Enter new address..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number:</label>
                        <select id="phone" name="phone" class="form-control mb-3">
                            <option value="<?= $user_data['phone'] ?>">Use Registered Phone (<?= $user_data['phone'] ?>)</option>
                            <option value="new_phone">Use New Phone Number</option>
                        </select>
                        <input type="text" id="new_phone" name="new_phone" class="form-control" style="display: none;" placeholder="Enter new phone number...">
                    </div>

                    <div class="payment-method mt-4">
                        <h3>Select Payment Method:</h3>
                        <div class="form-check custom-radio">
                            <input class="form-check-input" type="radio" name="payment_method" value="credit_card" required>
                            <label class="form-check-label">
                                <i class="fas fa-credit-card payment-icons"></i>Credit Card
                            </label>
                        </div>
                        <div class="form-check custom-radio">
                            <input class="form-check-input" type="radio" name="payment_method" value="bank_transfer" required>
                            <label class="form-check-label">
                                <i class="fas fa-university payment-icons"></i>Bank Transfer
                            </label>
                        </div>
                        <div class="form-check custom-radio">
                            <input class="form-check-input" type="radio" name="payment_method" value="cash_on_delivery" required>
                            <label class="form-check-label">
                                <i class="fas fa-truck payment-icons"></i>Cash on Delivery
                            </label>
                        </div>
                    </div>

                    <button type="submit" name="proceed" class="btn btn-checkout btn-custom mt-3">Proceed to Checkout</button>
                </form>
            </div>

            <a href="../index.php" class="btn btn-link mt-3">Continue Shopping</a>
        </main>

        <footer class="text-center mt-5">
            <p>&copy; 2024 Dog Breeds Shop</p>
        </footer>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript เพื่อแสดง/ซ่อนฟิลด์เมื่อเลือกกรอกที่อยู่ใหม่หรือเบอร์ใหม่ -->
    <script>
        $(document).ready(function() {
            $('#address').change(function() {
                if ($(this).val() == 'new_address') {
                    $('#new_address').show();
                } else {
                    $('#new_address').hide();
                }
            });
            
            $('#phone').change(function() {
                if ($(this).val() == 'new_phone') {
                    $('#new_phone').show();
                } else {
                    $('#new_phone').hide();
                }
            });
        });
    </script>
</body>
</html>
