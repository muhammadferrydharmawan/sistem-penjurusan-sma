<?php
/**
 * Dashboard Admin
 * 
 * Halaman utama untuk admin
 * 
 * @author M Ferry Dharmawan
 */

// Mulai session
session_start();

// Cek apakah sudah login dan role-nya admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Set judul halaman
$page_title = "Dashboard Admin";

// Include file koneksi database dan class
require_once '../config/database.php';
require_once '../classes/User.php';
require_once '../classes/Student.php';
require_once '../classes/Result.php';

// Inisialisasi objek
$userObj = new User($conn);
$studentObj = new Student($conn);
$resultObj = new Result($conn);

// Ambil statistik
$user_counts = $userObj->getUserCountByRole();
$total_students = $studentObj->getTotalStudents();
$jurusan_stats = $resultObj->getJurusanStatistics();
$total_results = $resultObj->getTotalResultCount();

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
                    <h5 class="mb-0"><i class="fas fa-tachometer-alt me-2"></i> Dashboard Admin</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
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
                        <div class="col-lg-3 col-md-6">
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
                        <div class="col-lg-3 col-md-6">
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
                        <div class="col-lg-3 col-md-6">
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
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i> Distribusi Jurusan</h5>
                </div>
                <div class="card-body">
                    <canvas id="chart-jurusan" width="400" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
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
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Tentang Aplikasi</h5>
                </div>
                <div class="card-body">
                    <h5>APLIKASI PENJURUSAN SISWA TINGKAT SMA MENGGUNAKAN ALGORITMA NAÏVE BAYES</h5>
                    <p>Aplikasi ini dikembangkan untuk membantu pihak sekolah dalam melakukan penjurusan siswa SMA berdasarkan potensi akademik dan minat siswa. Dengan algoritma Naïve Bayes, sistem dapat memberikan rekomendasi jurusan yang lebih akurat, efisien, dan objektif.</p>
                    
                    <h6 class="mt-4">Fitur Utama:</h6>
                    <ul>
                        <li>Pengelolaan data siswa dan nilai akademik</li>
                        <li>Implementasi algoritma Naïve Bayes untuk klasifikasi jurusan</li>
                        <li>Penjurusan berdasarkan nilai akademik dan minat siswa</li>
                        <li>Laporan hasil penjurusan yang komprehensif</li>
                    </ul>
                    
                    <h6 class="mt-4">Petunjuk Penggunaan:</h6>
                    <ol>
                        <li>Kelola data siswa dengan memasukkan nilai akademik dan minat</li>
                        <li>Lakukan proses penjurusan dengan algoritma Naïve Bayes</li>
                        <li>Lihat dan cetak hasil penjurusan siswa</li>
                    </ol>
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
});
</script>