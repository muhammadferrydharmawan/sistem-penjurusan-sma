<?php
/**
 * Dashboard Kepala Sekolah
 * 
 * Halaman utama untuk kepala sekolah
 * 
 * @author M Ferry Dharmawan
 */

// Mulai session
session_start();

// Cek apakah sudah login dan role-nya kepsek
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'kepsek') {
    header("Location: ../login.php");
    exit();
}

// Set judul halaman
$page_title = "Dashboard Kepala Sekolah";

// Include file koneksi database dan class
require_once '../config/database.php';
require_once '../classes/Student.php';
require_once '../classes/Result.php';
require_once '../classes/User.php';

// Inisialisasi objek
$studentObj = new Student($conn);
$resultObj = new Result($conn);
$userObj = new User($conn);

// Ambil statistik
$total_students = $studentObj->getTotalStudents();
$jurusan_stats = $resultObj->getJurusanStatistics();
$total_results = $resultObj->getTotalResultCount();
$user_counts = $userObj->getUserCountByRole();

// Ambil hasil penjurusan terbaru
$results = $resultObj->getAllResults();
$latest_results = array_slice($results, 0, 5);

// Include header
include '../includes/header.php';

// Include navbar
include '../includes/navbar.php';
?>

<!-- Content -->
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-tachometer-alt me-2"></i> Dashboard Kepala Sekolah</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h5><i class="fas fa-user-tie me-2"></i> Selamat Datang, <?php echo $_SESSION['nama_lengkap']; ?>!</h5>
                        <p class="mb-0">Selamat datang di Sistem Penjurusan Siswa SMA. Gunakan menu navigasi di atas untuk mengakses fitur-fitur yang tersedia.</p>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="dashboard-card bg-primary text-white">
                                <div class="dashboard-icon">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                                <div class="dashboard-info">
                                    <h5><?php echo $total_students; ?></h5>
                                    <p>Total Siswa</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="dashboard-card bg-success text-white">
                                <div class="dashboard-icon">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                                <div class="dashboard-info">
                                    <h5><?php echo $total_results; ?></h5>
                                    <p>Siswa Terprediksi</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="dashboard-card bg-info text-white">
                                <div class="dashboard-icon">
                                    <i class="fas fa-microscope"></i>
                                </div>
                                <div class="dashboard-info">
                                    <h5><?php echo $jurusan_stats['IPA']; ?></h5>
                                    <p>Jurusan IPA</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="dashboard-card bg-warning text-white">
                                <div class="dashboard-icon">
                                    <i class="fas fa-landmark"></i>
                                </div>
                                <div class="dashboard-info">
                                    <h5><?php echo $jurusan_stats['IPS']; ?></h5>
                                    <p>Jurusan IPS</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-7">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i> Hasil Penjurusan Terbaru</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Jurusan</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($latest_results)): ?>
                                    <?php foreach($latest_results as $result): ?>
                                        <tr>
                                            <td><?php echo $result['nis']; ?></td>
                                            <td><?php echo $result['nama_lengkap']; ?></td>
                                            <td><?php echo $result['kelas']; ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $result['jurusan'] == 'IPA' ? 'info' : 'warning'; ?>">
                                                    <?php echo $result['jurusan']; ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('d-m-Y', strtotime($result['tanggal_penjurusan'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Belum ada data hasil penjurusan</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php if (count($results) > 5): ?>
                        <div class="text-end mt-3">
                            <a href="hasil.php" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye me-1"></i> Lihat Semua Hasil
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-5">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i> Distribusi Jurusan</h5>
                </div>
                <div class="card-body">
                    <?php if ($total_results > 0): ?>
                        <canvas id="chart-jurusan" width="400" height="300"></canvas>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> Belum ada data untuk ditampilkan.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-users-cog me-2"></i> Pengguna Sistem</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-light">
                            <tr>
                                <th>Jenis Pengguna</th>
                                <th class="text-center">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><i class="fas fa-user-shield me-2 text-primary"></i> Administrator</td>
                                <td class="text-center"><?php echo $user_counts['admin']; ?></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-user-graduate me-2 text-success"></i> Siswa</td>
                                <td class="text-center"><?php echo $user_counts['siswa']; ?></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-user-tie me-2 text-warning"></i> Kepala Sekolah</td>
                                <td class="text-center"><?php echo $user_counts['kepsek']; ?></td>
                            </tr>
                            <tr class="bg-light">
                                <th>Total Pengguna</th>
                                <th class="text-center"><?php echo array_sum($user_counts); ?></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Tentang Sistem Penjurusan</h5>
                </div>
                <div class="card-body">
                    <h5>APLIKASI PENJURUSAN SISWA TINGKAT SMA MENGGUNAKAN ALGORITMA NAÏVE BAYES</h5>
                    <p>Sistem ini dikembangkan untuk membantu pihak sekolah dalam melakukan penjurusan siswa SMA berdasarkan potensi akademik dan minat siswa. Dengan algoritma Naïve Bayes, sistem dapat memberikan rekomendasi jurusan yang lebih akurat, efisien, dan objektif.</p>
                    
                    <h6 class="mt-4 mb-3">Keuntungan Penggunaan Sistem:</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <ul>
                                <li>Penjurusan yang lebih objektif berdasarkan data</li>
                                <li>Menghemat waktu dan tenaga dalam proses penjurusan</li>
                                <li>Transparansi dalam proses pengambilan keputusan</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul>
                                <li>Integrasi data akademik dan minat siswa</li>
                                <li>Laporan dan statistik yang komprehensif</li>
                                <li>Akses mudah untuk siswa dan orang tua</li>
                            </ul>
                        </div>
                    </div>
                    
                    <p class="mt-4">
                        <i class="fas fa-book me-2"></i> <strong>Referensi:</strong> Implementasi algoritma Naïve Bayes dalam sistem ini mengacu pada prinsip-prinsip klasifikasi probabilistik yang mempertimbangkan berbagai atribut (nilai mata pelajaran, minat) untuk memberikan rekomendasi jurusan yang paling sesuai untuk setiap siswa.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
include '../includes/footer.php';
?>

<!-- Script tambahan untuk chart -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if ($total_results > 0): ?>
    // Data untuk chart jurusan
    var ctx = document.getElementById('chart-jurusan').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['IPA', 'IPS'],
            datasets: [{
                label: 'Jumlah Siswa',
                data: [<?php echo $jurusan_stats['IPA']; ?>, <?php echo $jurusan_stats['IPS']; ?>],
                backgroundColor: [
                    'rgba(23, 162, 184, 0.7)',
                    'rgba(40, 167, 69, 0.7)'
                ],
                borderColor: [
                    'rgba(23, 162, 184, 1)',
                    'rgba(40, 167, 69, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Distribusi Jurusan Siswa'
                }
            }
        }
    });
    <?php endif; ?>
});
</script>