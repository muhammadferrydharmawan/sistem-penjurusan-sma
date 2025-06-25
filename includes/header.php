<?php
// Cek apakah sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Menentukan direktori base berdasarkan role
if ($_SESSION['role'] == 'admin') {
    $base_dir = '../admin/';
} elseif ($_SESSION['role'] == 'siswa') {
    $base_dir = '../siswa/';
} elseif ($_SESSION['role'] == 'kepsek') {
    $base_dir = '../kepsek/';
} else {
    $base_dir = '../';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Sistem Penjurusan SMA</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Header -->
    <header class="bg-primary text-white py-3">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <!-- Logo -->
                        <div class="me-3">
                            <img src="../assets/img/logo19.png" alt="Logo SMA" class="img-fluid" style="height: 60px; width: auto;">
                        </div>
                        <!-- Judul -->
                        <div>
                            <h3 class="mb-1">SISTEM PENJURUSAN SISWA SMA NEGERI 19 Medan</h3>
                            <p class="mb-0">Berdasarkan Potensi Akademik dan Minat</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <p class="mb-0">
                        <i class="fas fa-user-circle me-2"></i> <?php echo $_SESSION['nama_lengkap']; ?>
                        <span class="badge bg-light text-primary ms-2">
                            <?php 
                            if ($_SESSION['role'] == 'admin') {
                                echo 'Administrator';
                            } elseif ($_SESSION['role'] == 'siswa') {
                                echo 'Siswa';
                            } elseif ($_SESSION['role'] == 'kepsek') {
                                echo 'Kepala Sekolah';
                            }
                            ?>
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </header>

    <!-- Wrapper untuk konten utama -->
    <main class="flex-grow-1">