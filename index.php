<?php
/**
 * Halaman Utama
 * 
 * Halaman utama website yang mengarahkan ke halaman login
 * 
 * @author M Ferry Dharmawan
 */

// Mulai session
session_start();

// Cek apakah sudah login, redirect sesuai role
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

// Redirect ke halaman login
header("Location: login.php");
exit();