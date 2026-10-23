
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
$kode = '';
$namaBarang = '';
$kategori = '';
$stok = '';
$rak = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';

    if (!is_string($token) || !hash_equals($_SESSION['csrf_token'], $token)) {
        $error = lang('invalid_request');
    } else {
        $kode = trim($_POST['kode_barang'] ?? '');
        $namaBarang = trim($_POST['nama_barang'] ?? '');
        $kategori = $_POST['kategori'] ?? '';
        $stok = trim($_POST['total_stok'] ?? '');
        $rak = trim($_POST['rak_penyimpanan'] ?? '');

        if (
            $kode === '' ||
            $namaBarang === '' ||
            $kategori === '' ||
            $stok === '' ||
            $rak === ''
        ) {
            $error = lang('required_fields');
        } elseif (
            !in_array($kategori, $kategoriDiizinkan, true)
        ) {
            $error = lang('invalid_category');
        } elseif (
            !ctype_digit($stok) ||
            filter_var($stok, FILTER_VALIDATE_INT) === false
        ) {
            $error = lang('invalid_stock');
        } else {
            try {
                $stmt = $pdo->prepare(
                    "INSERT INTO barang
                    (kode_barang, nama_barang, kategori, total_stok, rak_penyimpanan)
                    VALUES
                    (:kode, :nama, :kategori, :stok, :rak)"
                );

                $stmt->execute([
                    'kode' => $kode,
                    'nama' => $namaBarang,
                    'kategori' => $kategori,
                    'stok' => (int) $stok,
                    'rak' => $rak
                ]);

                header('Location: barang.php');
                exit;
            } catch (PDOException $e) {
                if ($e->getCode() === '23000') {
                    $error = lang('duplicate_code');
                } else {
                    $error = lang('save_error');
                }
            }
        }
    }
}

$judulHalaman = lang('add_item_title');

include 'includes/header.php';
?>

<main class="container">
    <div class="page-heading">
        <div>
            <h1><?= htmlspecialchars(lang('add_item_title'), ENT_QUOTES, 'UTF-8') ?></h1>
            <p><?= htmlspecialchars(lang('add_item_description'), ENT_QUOTES, 'UTF-8') ?></p>
        </div>
    </div>

    <section class="content-card">
        <?php if ($error !== ''): ?>
            <div class="alert-error">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="tambah.php">
            <input
                type="hidden"
                name="csrf_token"
                value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>"
            >

            <div class="form-group">
                <label for="kode_barang"><?= htmlspecialchars(lang('item_code'), ENT_QUOTES, 'UTF-8') ?></label>
                <input
                    type="text"
                    id="kode_barang"
                    name="kode_barang"
                    maxlength="20"
                    value="<?= htmlspecialchars($kode, ENT_QUOTES, 'UTF-8') ?>"
                    required
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
                    <?= htmlspecialchars(lang('save'), ENT_QUOTES, 'UTF-8') ?>
                </button>

                <a href="barang.php" class="btn-secondary">
                    <?= htmlspecialchars(lang('cancel'), ENT_QUOTES, 'UTF-8') ?>
                </a>
            </div>
        </form>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
