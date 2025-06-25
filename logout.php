<?php
/**
 * Logout
 * 
 * Halaman untuk keluar dari sistem
 * 
 * @author M Ferry Dharmawan
 */

// Mulai session
session_start();

// Hapus semua data session
session_unset();

// Hancurkan session
session_destroy();

// Redirect ke halaman login
header("Location: login.php");
exit();