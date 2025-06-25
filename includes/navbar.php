<?php
// Menentukan halaman aktif untuk navigasi
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php">
                        <i class="fas fa-home me-1"></i> Home
                    </a>
                </li>
                
                <?php if ($_SESSION['role'] == 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'data_user.php') ? 'active' : ''; ?>" href="data_user.php">
                            <i class="fas fa-users-cog me-1"></i> Data User
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'data_siswa.php') ? 'active' : ''; ?>" href="data_siswa.php">
                            <i class="fas fa-user-graduate me-1"></i> Data Siswa
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'process.php') ? 'active' : ''; ?>" href="process.php">
                            <i class="fas fa-cogs me-1"></i> Proses Naïve Bayes
                        </a>
                    </li>
                <?php endif; ?>
                
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'hasil.php') ? 'active' : ''; ?>" href="hasil.php">
                        <i class="fas fa-chart-bar me-1"></i> Hasil Penjurusan
                    </a>
                </li>
                
                <?php if ($_SESSION['role'] == 'siswa' || $_SESSION['role'] == 'kepsek'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'profile.php') ? 'active' : ''; ?>" href="profile.php">
                            <i class="fas fa-user me-1"></i> Profil
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
            
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-danger" href="../logout.php">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>