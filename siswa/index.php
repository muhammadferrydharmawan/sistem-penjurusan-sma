<?php
/**
 * Dashboard Siswa
 * 
 * Halaman utama untuk siswa
 * 
 * @author M Ferry Dharmawan
 */

// Mulai session
session_start();

// Cek apakah sudah login dan role-nya siswa
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'siswa') {
    header("Location: ../login.php");
    exit();
}

// Set judul halaman
$page_title = "Dashboard Siswa";

// Include file koneksi database dan class
require_once '../config/database.php';
require_once '../classes/Student.php';
require_once '../classes/Result.php';

// Inisialisasi objek
$studentObj = new Student($conn);
$resultObj = new Result($conn);

// Ambil data siswa
$student = $studentObj->getStudentByUserId($_SESSION['user_id']);
$has_result = false;
$result = null;

// Cek apakah sudah ada hasil penjurusan
if ($student) {
    $result = $resultObj->getResultByStudentId($student['id']);
    if ($result) {
        $has_result = true;
    }
}

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
                    <h5 class="mb-0"><i class="fas fa-tachometer-alt me-2"></i> Dashboard Siswa</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h5><i class="fas fa-user-graduate me-2"></i> Selamat Datang, <?php echo $_SESSION['nama_lengkap']; ?>!</h5>
                        <p class="mb-0">Selamat datang di Sistem Penjurusan Siswa SMA. Gunakan menu navigasi di atas untuk mengakses fitur-fitur yang tersedia.</p>
                    </div>
                    
                    <?php if ($student): ?>
                        <div class="row mt-4">
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-id-card me-2"></i> Data Pribadi</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th width="30%">NIS</th>
                                                <td><?php echo $student['nis']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Nama Lengkap</th>
                                                <td><?php echo $student['nama_lengkap']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Kelas</th>
                                                <td><?php echo $student['kelas']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Umur</th>
                                                <td><?php echo $student['umur']; ?> tahun</td>
                                            </tr>
                                            <tr>
                                                <th>Jenis Kelamin</th>
                                                <td><?php echo ($student['jenis_kelamin'] == 'L') ? 'Laki-laki' : 'Perempuan'; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Minat</th>
                                                <td>
                                                    <?php if ($student['minat']): ?>
                                                        <span class="badge bg-<?php echo $student['minat'] == 'IPA' ? 'info' : 'warning'; ?>">
                                                            <?php echo $student['minat']; ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Belum Ada</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Bakat/Hobi</th>
                                                <td><?php echo $student['bakat'] ? $student['bakat'] : '-'; ?></td>
                                            </tr>
                                        </table>
                                        
                                        <div class="mt-3">
                                            <a href="profile.php" class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit me-1"></i> Edit Profil
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i> Status Penjurusan</h6>
                                    </div>
                                    <div class="card-body">
                                        <?php if ($has_result): ?>
                                            <div class="alert alert-success">
                                                <h5 class="mb-2">Penjurusan Anda telah selesai!</h5>
                                                <p>Berdasarkan Hasil, Anda direkomendasikan untuk:</p>
                                                
                                                <div class="text-center my-4">
                                                    <h3>
                                                        <span class="badge bg-<?php echo $result['jurusan'] == 'IPA' ? 'info' : 'warning'; ?> p-3">
                                                            Jurusan <?php echo $result['jurusan']; ?>
                                                        </span>
                                                    </h3>
                                                </div>
                                                
                                                <p class="mb-0">
                                                    <a href="hasil.php" class="btn btn-primary">
                                                        <i class="fas fa-eye me-1"></i> Lihat Detail Hasil
                                                    </a>
                                                </p>
                                            </div>
                                        <?php else: ?>
                                            <div class="alert alert-warning">
                                                <h5 class="mb-2">Penjurusan Belum Diproses</h5>
                                                <p>Proses penjurusan Anda belum dilakukan oleh administrator. Silahkan hubungi administrator atau Guru BK untuk informasi lebih lanjut.</p>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="mt-4">
                                            <h6 class="border-bottom pb-2">Nilai Akademik Anda</h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <ul class="list-group">
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            Biologi
                                                            <span class="badge bg-primary rounded-pill"><?php echo $student['biologi']; ?></span>
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            Fisika
                                                            <span class="badge bg-primary rounded-pill"><?php echo $student['fisika']; ?></span>
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            Kimia
                                                            <span class="badge bg-primary rounded-pill"><?php echo $student['kimia']; ?></span>
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            Bahasa Inggris
                                                            <span class="badge bg-primary rounded-pill"><?php echo $student['bahasa_inggris']; ?></span>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-6">
                                                    <ul class="list-group">
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            Ekonomi
                                                            <span class="badge bg-primary rounded-pill"><?php echo $student['ekonomi']; ?></span>
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            Sosiologi
                                                            <span class="badge bg-primary rounded-pill"><?php echo $student['sosiologi']; ?></span>
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            Geografi
                                                            <span class="badge bg-primary rounded-pill"><?php echo $student['geografi']; ?></span>
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            Bahasa Indonesia
                                                            <span class="badge bg-primary rounded-pill"><?php echo $student['bahasa_indonesia']; ?></span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-circle me-2"></i> Data siswa Anda belum lengkap. Silahkan hubungi administrator untuk melengkapi data Anda.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Informasi Jurusan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0">Jurusan IPA (Ilmu Pengetahuan Alam)</h6>
                                </div>
                                <div class="card-body">
                                    <p>Jurusan IPA fokus pada ilmu-ilmu eksakta seperti:</p>
                                    <ul>
                                        <li>Matematika</li>
                                        <li>Biologi</li>
                                        <li>Fisika</li>
                                        <li>Kimia</li>
                                    </ul>
                                    
                                    <p>Prospek karir untuk lulusan IPA antara lain:</p>
                                    <ul>
                                        <li>Dokter, farmasi, dan profesi kesehatan lainnya</li>
                                        <li>Insinyur dan profesi teknik</li>
                                        <li>Peneliti sains</li>
                                        <li>Ahli matematika dan statistika</li>
                                        <li>Ahli teknologi informasi</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-warning text-white">
                                    <h6 class="mb-0">Jurusan IPS (Ilmu Pengetahuan Sosial)</h6>
                                </div>
                                <div class="card-body">
                                    <p>Jurusan IPS fokus pada ilmu-ilmu sosial seperti:</p>
                                    <ul>
                                        <li>Ekonomi</li>
                                        <li>Sosiologi</li>
                                        <li>Geografi</li>
                                        <li>Sejarah</li>
                                    </ul>
                                    
                                    <p>Prospek karir untuk lulusan IPS antara lain:</p>
                                    <ul>
                                        <li>Ekonom, akuntan, dan profesi keuangan</li>
                                        <li>Ahli hukum dan praktisi legal</li>
                                        <li>Pengusaha dan manajer bisnis</li>
                                        <li>Diplomat dan hubungan internasional</li>
                                        <li>Psikolog dan pekerja sosial</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
include '../includes/footer.php';
?>