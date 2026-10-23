
<?php
session_start();

require 'config/database.php';
require 'config/lang.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$kategoriDiizinkan = [
    'ATK',
    'Elektronik',
    'Perlengkapan Kebersihan',
    'Perabot',
    'Lainnya'
];

$error = '';
$kode = trim($_GET['kode_barang'] ?? $_POST['kode_barang'] ?? '');

if ($kode === '') {
    header('Location: barang.php');
    exit;
}

$stmt = $pdo->prepare(
    "SELECT kode_barang, nama_barang, kategori, total_stok, rak_penyimpanan
     FROM barang
     WHERE kode_barang = :kode"
);
$stmt->execute(['kode' => $kode]);
$barang = $stmt->fetch();

if (!$barang) {
    header('Location: barang.php');
    exit;
}

$namaBarang = $barang['nama_barang'];
$kategori = $barang['kategori'];
$stok = (string) $barang['total_stok'];
$rak = $barang['rak_penyimpanan'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';

    if (!is_string($token) || !hash_equals($_SESSION['csrf_token'], $token)) {
        $error = lang('invalid_request');
    } else {
        $namaBarang = trim($_POST['nama_barang'] ?? '');
        $kategori = $_POST['kategori'] ?? '';
        $stok = trim($_POST['total_stok'] ?? '');
        $rak = trim($_POST['rak_penyimpanan'] ?? '');

        if (
            $namaBarang === '' ||
            $kategori === '' ||
            $stok === '' ||
            $rak === ''
        ) {
            $error = lang('required_fields');
        } elseif (!in_array($kategori, $kategoriDiizinkan, true)) {
            $error = lang('invalid_category');
        } elseif (
            !ctype_digit($stok) ||
            filter_var($stok, FILTER_VALIDATE_INT) === false
        ) {
            $error = lang('invalid_stock');
        } else {
            try {
                $update = $pdo->prepare(
                    "UPDATE barang
                        SET nama_barang = :nama,
                            kategori = :kategori,
                            total_stok = :stok,
                            rak_penyimpanan = :rak
                        WHERE kode_barang = :kode"
                );

                $update->execute([
                    'nama' => $namaBarang,
                    'kategori' => $kategori,
                    'stok' => (int) $stok,
                    'rak' => $rak,
                    'kode' => $kode
                ]);

                header('Location: barang.php');
                exit;
            } catch (PDOException $e) {
                $error = lang('save_error');
            }
        }
    }
}

$judulHalaman = lang('edit_item');

include 'includes/header.php';
?>

<main class="container">
    <div class="page-heading">
        <div>
            <h1><?= htmlspecialchars(lang('edit_item'), ENT_QUOTES, 'UTF-8') ?></h1>
            <p><?= htmlspecialchars(lang('edit_item_description'), ENT_QUOTES, 'UTF-8') ?></p>
        </div>
    </div>

    <section class="content-card">
        <?php if ($error !== ''): ?>
            <div class="alert-error">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="edit.php">
            <input
                type="hidden"
                name="csrf_token"
                value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>"
            >

            <input
                type="hidden"
                name="kode_barang"
                value="<?= htmlspecialchars($kode, ENT_QUOTES, 'UTF-8') ?>"
            >

            <div class="form-group">
                <label for="kode_barang"><?= htmlspecialchars(lang('item_code'), ENT_QUOTES, 'UTF-8') ?></label>
                <input
                    type="text"
                    id="kode_barang"
                    value="<?= htmlspecialchars($kode, ENT_QUOTES, 'UTF-8') ?>"
                    readonly
                >
            </div>

            <div class="form-group">
                <label for="nama_barang"><?= htmlspecialchars(lang('item_name'), ENT_QUOTES, 'UTF-8') ?></label>
                <input
                    type="text"
                    id="nama_barang"
                    name="nama_barang"
                    maxlength="100"
                    value="<?= htmlspecialchars($namaBarang, ENT_QUOTES, 'UTF-8') ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="kategori"><?= htmlspecialchars(lang('category'), ENT_QUOTES, 'UTF-8') ?></label>
                <select id="kategori" name="kategori" required>
                    <option value=""><?= htmlspecialchars(lang('select_category'), ENT_QUOTES, 'UTF-8') ?></option>
                    <?php foreach ($kategoriDiizinkan as $opsi): ?>
                        <?php
                        $key = [
                            'ATK' => 'stationery',
                            'Elektronik' => 'electronics',
                            'Perlengkapan Kebersihan' => 'cleaning',
                            'Perabot' => 'furniture',
                            'Lainnya' => 'other'
                        ][$opsi];
                        ?>
                        <option
                            value="<?= htmlspecialchars($opsi, ENT_QUOTES, 'UTF-8') ?>"
                            <?= $kategori === $opsi ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars(lang($key), ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="total_stok"><?= htmlspecialchars(lang('stock'), ENT_QUOTES, 'UTF-8') ?></label>
                <input
                    type="number"
                    id="total_stok"
                    name="total_stok"
                    min="0"
                    step="1"
                    value="<?= htmlspecialchars($stok, ENT_QUOTES, 'UTF-8') ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="rak_penyimpanan"><?= htmlspecialchars(lang('storage_rack'), ENT_QUOTES, 'UTF-8') ?></label>
                <input
                    type="text"
                    id="rak_penyimpanan"
                    name="rak_penyimpanan"
                    maxlength="50"
                    value="<?= htmlspecialchars($rak, ENT_QUOTES, 'UTF-8') ?>"
                    required
                >
            </div>

            <div class="action-buttons">
                <button type="submit" class="btn-primary">
                    <?= htmlspecialchars(lang('save_changes'), ENT_QUOTES, 'UTF-8') ?>
                </button>

                <a href="barang.php" class="btn-secondary">
                    <?= htmlspecialchars(lang('cancel'), ENT_QUOTES, 'UTF-8') ?>
                </a>
            </div>
        </form>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
