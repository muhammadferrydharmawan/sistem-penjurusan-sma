<?php
/**
 * Halaman Login
 * 
 * Halaman untuk autentikasi user masuk ke sistem
 * 
 * @author M Ferry Dharmawan
 * @version 2.0
 */

// Mulai session
session_start();

// Cek jika user sudah login, redirect sesuai role
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: admin/index.php");
    } elseif ($_SESSION['role'] == 'siswa') {
        header("Location: siswa/index.php");
    } elseif ($_SESSION['role'] == 'kepsek') {
        header("Location: kepsek/index.php");
    }
    exit();
}

// Include file koneksi database
require_once 'config/database.php';

$error = '';
$success = '';

// Proses form login jika ada POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = isset($_POST['role']) ? $_POST['role'] : '';
    
    // Validasi input
    if (empty($username) || empty($password)) {
        $error = "Username dan password harus diisi";
    } elseif (empty($role)) {
        $error = "Silahkan pilih role untuk login";
    } else {
        // Query untuk mencari user berdasarkan username dan role
        $query = "SELECT * FROM users WHERE username = ? AND role = ? AND status = 'aktif'";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $username, $role);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            
            // Verifikasi password
            if (password_verify($password, $user['password'])) {
                // Set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                $_SESSION['role'] = $user['role'];
                
                // Set pesan sukses
                $success = "Login berhasil! Mengalihkan...";
                
                // Redirect sesuai role menggunakan JavaScript setelah menampilkan pesan
                echo "<script>
                        setTimeout(function() {
                            window.location.href = '{$user['role']}/index.php';
                        }, 1000);
                      </script>";
            } else {
                $error = "Password salah. Silakan coba lagi.";
            }
        } else {
            $error = "Akun {$role} dengan username '{$username}' tidak ditemukan atau tidak aktif";
        }
    }
}

// Role options for the form
$roles = [
    'admin' => [
        'icon' => 'user-shield',
        'color' => 'primary',
        'title' => 'Admin'
    ],
    'siswa' => [
        'icon' => 'user-graduate',
        'color' => 'success',
        'title' => 'Siswa'
    ],
    'kepsek' => [
        'icon' => 'user-tie',
        'color' => 'warning',
        'title' => 'Kepsek'
    ]
];

// Simpan selected role jika ada error
$selected_role = isset($_POST['role']) ? $_POST['role'] : '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login Sistem Penjurusan SMA ">
    <title>Login - Sistem Penjurusan SMA</title>
    
    <!-- Favicon -->
    <link rel="icon" href="assets/images/favicon.png" type="image/png">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            height: 100%;
            overflow: hidden;
        }
        
        body {
            font-family: 'Nunito', sans-serif;
            background: url('assets/img/sma19.jpg') no-repeat center center;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.39);
            z-index: 1;
        }
        
        .login-container {
            width: 400px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            position: relative;
            z-index: 2;
        }
        
        .login-header {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .login-logo-custom {
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
}

.custom-logo {
    width: 140px;
    height: 140px;
    object-fit: contain;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
}
        
        .login-logo {
            width: 70px;
            height: 70px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .login-logo i {
            font-size: 35px;
            color: #2563eb;
        }
        
        .login-title {
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 5px;
        }
        
        .login-subtitle {
            font-size: 13px;
            opacity: 0.9;
            line-height: 1.4;
        }
        
        .login-body {
            padding: 25px;
        }
        
        .role-selection {
            margin-bottom: 20px;
        }
        
        .role-title {
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 14px;
            color: #374151;
            text-align: center;
        }
        
        .role-options {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }
        
        .role-option {
            flex: 1;
            text-align: center;
            padding: 10px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }
        
        .role-option:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .role-option.selected-admin {
            border-color: #2563eb;
            background-color: rgba(37, 99, 235, 0.05);
        }
        
        .role-option.selected-siswa {
            border-color: #10b981;
            background-color: rgba(16, 185, 129, 0.05);
        }
        
        .role-option.selected-kepsek {
            border-color: #f59e0b;
            background-color: rgba(245, 158, 11, 0.05);
        }
        
        .role-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-bottom: 6px;
        }
        
        .role-admin .role-icon {
            background-color: rgba(37, 99, 235, 0.1);
            color: #2563eb;
        }
        
        .role-siswa .role-icon {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }
        
        .role-kepsek .role-icon {
            background-color: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }
        
        .role-option i {
            font-size: 20px;
        }
        
        .role-name {
            font-weight: 600;
            font-size: 12px;
            color: #374151;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #374151;
            font-size: 14px;
        }
        
        .input-group {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .input-icon-wrapper {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .input-icon {
            color: #6b7280;
            font-size: 16px;
        }
        
        .form-control {
            width: 100%;
            padding: 10px 35px 10px 35px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s;
        }
        
        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            outline: none;
        }
        
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            cursor: pointer;
            font-size: 16px;
        }
        
        .login-button {
            width: 100%;
            background: #2563eb;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 5px;
        }
        
        .login-button:disabled {
            background: #9ca3af;
            cursor: not-allowed;
        }
        
        .login-button:not(:disabled):hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }
        
        .login-button i {
            margin-right: 8px;
        }
        
        .login-footer {
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
            margin-top: 10px;
        }
        
        .alert {
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            font-size: 13px;
        }
        
        .alert i {
            margin-right: 8px;
            font-size: 16px;
        }
        
        .alert-danger {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        
        .alert-success {
            background-color: #d1fae5;
            color: #047857;
        }
        
        .checkmark {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            color: white;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .selected-admin .checkmark {
            background-color: #2563eb;
        }
        
        .selected-siswa .checkmark {
            background-color: #10b981;
        }
        
        .selected-kepsek .checkmark {
            background-color: #f59e0b;
        }
        
        @media (max-width: 450px) {
            .login-container {
                width: 95%;
            }
            
            .login-body {
                padding: 20px 15px;
            }
            
            .role-options {
                flex-direction: row;
            }
            
            .role-icon {
                width: 35px;
                height: 35px;
            }
            
            .login-title {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Login Header -->
        <div class="login-header">
            <div class="login-logo-custom">
    <img src="assets/img/logo19.png" alt="Logo" class="custom-logo">
</div>
            <h1 class="login-title">SMA NEGERI 19 MEDAN</h1>
            <p class="login-subtitle">Login - Sistem Penjurusan</p>
        </div>
        
        <!-- Login Body -->
        <div class="login-body">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo $error; ?></span>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span><?php echo $success; ?></span>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <!-- Role Selection -->
                <div class="role-selection">
                    <div class="role-title">Pilih Jenis Akun</div>
                    <div class="role-options">
                        <?php foreach ($roles as $role_key => $role_data): ?>
                            <div class="role-option role-<?php echo $role_key; ?> <?php echo ($selected_role === $role_key) ? 'selected-'.$role_key : ''; ?>" data-role="<?php echo $role_key; ?>">
                                <div class="role-icon">
                                    <i class="fas fa-<?php echo $role_data['icon']; ?>"></i>
                                </div>
                                <div class="role-name"><?php echo $role_data['title']; ?></div>
                                <div class="checkmark" style="<?php echo ($selected_role === $role_key) ? 'display:block;' : 'display:none;'; ?>">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <input type="hidden" name="role" id="selected_role" value="<?php echo $selected_role; ?>">
                </div>
                
                <!-- Username -->
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-group">
                        <div class="input-icon-wrapper">
                            <i class="fas fa-user input-icon"></i>
                        </div>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                    </div>
                </div>
                
                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <div class="input-icon-wrapper">
                            <i class="fas fa-lock input-icon"></i>
                        </div>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                        <div class="password-toggle" id="password-toggle">
                            <i class="fas fa-eye-slash"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="login-button" id="login-button" <?php echo empty($selected_role) ? 'disabled' : ''; ?>>
                    <i class="fas fa-sign-in-alt"></i> Masuk
                </button>
            </form>
            
            <!-- Footer -->
            <div class="login-footer">
                <p>&copy; <?php echo date('Y'); ?> SMA Negeri 19 Medan. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </div>
    
    <!-- jQuery -->
    <script src="assets/js/jquery.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Role selection
            $('.role-option').click(function() {
                $('.role-option').removeClass('selected-admin selected-siswa selected-kepsek');
                $('.checkmark').hide();
                
                var selectedRole = $(this).data('role');
                $(this).addClass('selected-' + selectedRole);
                $(this).find('.checkmark').show();
                
                $('#selected_role').val(selectedRole);
                
                // Enable login button when role is selected
                $('#login-button').prop('disabled', false);
            });
            
            // Password toggle
            $('#password-toggle').click(function() {
                var passwordField = $('#password');
                var passwordToggleIcon = $(this).find('i');
                
                if (passwordField.attr('type') === 'password') {
                    passwordField.attr('type', 'text');
                    passwordToggleIcon.removeClass('fa-eye-slash').addClass('fa-eye');
                } else {
                    passwordField.attr('type', 'password');
                    passwordToggleIcon.removeClass('fa-eye').addClass('fa-eye-slash');
                }
            });
            
            // Focus username field on load
            $('#username').focus();
            
            // Auto-fade out alerts after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        });
    </script>
</body>
</html>