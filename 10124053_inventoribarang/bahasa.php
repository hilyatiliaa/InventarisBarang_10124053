<?php
session_start();

// Halaman yang boleh dituju setelah bahasa diubah.
$halamanDiizinkan = [
    'login.php',
    'dashboard.php',
    'barang.php',
    'tambah.php',
    'edit.php'
];

// Ambil pilihan bahasa, halaman tujuan, dan parameter URL-nya.
$bahasaBaru = $_GET['set'] ?? 'id';
$tujuan = $_GET['tujuan'] ?? 'login.php';
$query = $_GET['query'] ?? '';

// Pastikan tujuan pengalihan aman.
if (!is_string($tujuan) || !in_array($tujuan, $halamanDiizinkan, true)) {
    $tujuan = 'login.php';
    $query = '';
}

// Simpan bahasa ke session.
if (in_array($bahasaBaru, ['id', 'en'], true)) {
    $_SESSION['bahasa'] = $bahasaBaru;
}

// Kembali ke halaman tujuan beserta parameternya (misalnya ?kode_barang=...).
if (is_string($query) && $query !== '') {
    $tujuan .= '?' . $query;
}

header('Location: ' . $tujuan);
exit;