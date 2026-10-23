
<?php
session_start();
require 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: barang.php');
    exit;
}

$token = $_POST['csrf_token'] ?? '';
$kode = trim($_POST['kode_barang'] ?? '');

if (
    empty($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $token) ||
    $kode === ''
) {
    header('Location: barang.php');
    exit;
}

$stmt = $pdo->prepare(
    "DELETE FROM barang WHERE kode_barang = :kode"
);

$stmt->execute(['kode' => $kode]);

header('Location: barang.php');
exit;
