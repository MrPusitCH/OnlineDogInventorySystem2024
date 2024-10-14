<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'manager') {
    header("Location: ../front-end/login.php");
    exit;
}

include('../front-end/db_connection.php');

// ดึงข้อมูลคำสั่งซื้อทั้งหมด
$sql = "SELECT orders.id, users.username, products.name AS product_name, orders.quantity, orders.order_date, orders.status 
        FROM orders 
        JOIN users ON orders.user_id = users.id
        JOIN products ON orders.product_id = products.id";
$result = $conn->query($sql);

// สร้างข้อมูลสำหรับกราฟ Chart.js และข้อมูลตาราง
$chartData = [];
$orderData = []; // ใช้เก็บข้อมูลคำสั่งซื้อสำหรับตาราง
while ($row = $result->fetch_assoc()) {
    $chartData[$row['product_name']][] = $row['quantity']; // เก็บจำนวนสินค้า
    $orderData[] = $row; // เก็บข้อมูลคำสั่งซื้อสำหรับตาราง
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .container {
            margin-top: 20px;
        }

        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .fade-in {
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .chart-container {
            margin-top: 30px;
        }

        .btn-back {
            display: block;
            margin: 40px auto;
            background-color: #007bff;
            color: white;
        }

        .btn-back:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body class="bg-light">
<div class="container">
    <h1 class="text-center">Order Dashboard (Manager)</h1>

    <!-- แสดงตารางคำสั่งซื้อ -->
    <div class="card fade-in">
        <div class="card-header">
            <h4>Order List</h4>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead class="thead-dark">
                <tr>
                    <th>Order ID</th>
                    <th>Username</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Order Date</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (!empty($orderData)) {
                    foreach ($orderData as $order) {
                        echo "<tr>";
                        echo "<td>" . $order['id'] . "</td>";
                        echo "<td>" . $order['username'] . "</td>";
                        echo "<td>" . $order['product_name'] . "</td>";
                        echo "<td>" . $order['quantity'] . "</td>";
                        echo "<td>" . $order['order_date'] . "</td>";
                        echo "<td>" . $order['status'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center text-danger'>No orders found.</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- กราฟแสดงยอดคำสั่งซื้อ -->
    <div class="chart-container">
        <canvas id="orderChart"></canvas>
    </div>

    <!-- ปุ่มกลับไปยังหน้า Manager Panel วางที่ล่างสุด -->
    <a href="manager_panel.php" class="btn btn-back btn-lg">Back to Manager Panel</a>

    <script>
        var ctx = document.getElementById('orderChart').getContext('2d');
        var orderChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_keys($chartData)); ?>,
                datasets: [{
                    label: 'Quantity Sold',
                    data: <?php echo json_encode(array_map('array_sum', $chartData)); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
