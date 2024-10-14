<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f0f8ff; /* พื้นหลังสีฟ้าอ่อน */
            height: 100vh; /* กำหนดความสูงให้เต็มหน้าจอ */
            display: flex;
            align-items: center; /* จัดกลางแนวตั้ง */
            justify-content: center; /* จัดกลางแนวนอน */
            margin: 0;
        }

        .login-container {
            max-width: 400px; /* ความกว้างสูงสุด */
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .eye-icon {
            cursor: pointer;
        }

        .eye-icon:hover {
            color: #007bff;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #007bff;
        }

        .fadeIn {
            animation: fadeInEffect 1s ease-in-out;
        }

        @keyframes fadeInEffect {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .register-link {
            text-align: center; /* จัดกลางข้อความ */
            margin-top: 15px;
        }

        /* เพิ่มการตกแต่งแจ้งเตือน */
        .alert {
            animation: fadeInEffect 0.5s ease-in-out;
        }
    </style>
</head>
<body class="fadeIn">
    <div class="container login-container">
        <h2 class="text-center mb-4">Login</h2>

        <!-- แสดงการแจ้งเตือน -->
        <?php
        session_start();
        if (isset($_SESSION['login_error'])) {
            echo "<div class='alert alert-danger' role='alert'>{$_SESSION['login_error']}</div>";
            unset($_SESSION['login_error']); // ลบข้อความหลังจากแสดงผลแล้ว
        }
        ?>

        <form action="login_process.php" method="POST"> <!-- ส่งข้อมูลไปยัง login_process.php -->
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" required>
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="fas fa-eye eye-icon" id="togglePassword"></i>
                        </span>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>

        <!-- ข้อความเชิญชวนให้สมัครสมาชิก -->
        <div class="register-link">
            <p>If you haven't registered yet, <a href="register.php">click here to register</a>.</p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        togglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>
