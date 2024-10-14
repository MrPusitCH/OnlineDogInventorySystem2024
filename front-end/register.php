<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f0f8ff;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
        }

        .register-container {
            max-height: 90vh; /* จำกัดความสูงของฟอร์มไม่ให้เกินหน้าจอ */
            max-width: 600px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
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

        .eye-icon {
            cursor: pointer;
            color: #007bff;
        }

        .eye-icon:hover {
            color: #0056b3;
        }

        .error-message {
            color: red;
            font-size: 14px;
        }
    </style>
</head>
<body class="fadeIn">
    <div class="container register-container">
        <h2 class="text-center mb-4">Register</h2>
        <?php
        session_start();
        if (isset($_SESSION['register_error'])) {
            echo "<div class='alert alert-danger' role='alert'>{$_SESSION['register_error']}</div>";
            unset($_SESSION['register_error']);
        }
        ?>
        <form action="register_process.php" method="POST" id="registerForm">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo isset($_SESSION['register_username']) ? $_SESSION['register_username'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($_SESSION['register_email']) ? $_SESSION['register_email'] : ''; ?>" required>
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
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="fas fa-eye eye-icon" id="toggleConfirmPassword"></i>
                        </span>
                    </div>
                </div>
                <span id="passwordError" class="error-message"></span>
            </div>
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo isset($_SESSION['register_first_name']) ? $_SESSION['register_first_name'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo isset($_SESSION['register_last_name']) ? $_SESSION['register_last_name'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" class="form-control" id="dob" name="dob" value="<?php echo isset($_SESSION['register_dob']) ? $_SESSION['register_dob'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="gender">Gender</label>
                <select class="form-control" id="gender" name="gender" required>
                    <option value="" disabled selected>Select your gender</option>
                    <option value="male" <?php echo (isset($_SESSION['register_gender']) && $_SESSION['register_gender'] == 'male') ? 'selected' : ''; ?>>Male</option>
                    <option value="female" <?php echo (isset($_SESSION['register_gender']) && $_SESSION['register_gender'] == 'female') ? 'selected' : ''; ?>>Female</option>
                    <option value="other" <?php echo (isset($_SESSION['register_gender']) && $_SESSION['register_gender'] == 'other') ? 'selected' : ''; ?>>Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <textarea class="form-control" id="address" name="address" rows="3" required><?php echo isset($_SESSION['register_address']) ? $_SESSION['register_address'] : ''; ?></textarea>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo isset($_SESSION['register_phone']) ? $_SESSION['register_phone'] : ''; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Register</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });

        const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
        const confirmPassword = document.querySelector('#confirm_password');
        toggleConfirmPassword.addEventListener('click', function () {
            const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPassword.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });

        // Validate password match
        const registerForm = document.getElementById('registerForm');
        const passwordError = document.getElementById('passwordError');
        registerForm.addEventListener('submit', function (e) {
            if (password.value !== confirmPassword.value) {
                e.preventDefault(); // หยุดการส่งฟอร์ม
                passwordError.textContent = "Passwords do not match!";
            }
        });
    </script>
</body>
</html>
