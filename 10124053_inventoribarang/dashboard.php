<?php
session_start();

require 'config/database.php';
require 'config/lang.php';

// Proteksi halaman dashboard.
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Menghitung total barang.
$stmtBarang = $pdo->query(
    "SELECT COUNT(*) FROM barang"
);
$totalBarang = $stmtBarang->fetchColumn();

// Menghitung total stok.
$stmtStok = $pdo->query(
    "SELECT COALESCE(SUM(total_stok), 0) FROM barang"
);
$totalStok = $stmtStok->fetchColumn();

// Nama pengguna.
$nama = htmlspecialchars(
    $_SESSION['nama'] ?? 'Pengguna',
    ENT_QUOTES,
    'UTF-8'
);

// Pencarian barang.
$cari = trim($_GET['cari'] ?? '');

if ($cari !== '') {
    $stmtDaftar = $pdo->prepare(
        "SELECT kode_barang, nama_barang, kategori,
                total_stok, rak_penyimpanan
         FROM barang
         WHERE kode_barang LIKE :kode
            OR nama_barang LIKE :nama
            OR kategori LIKE :kategori
         ORDER BY nama_barang ASC"
    );

    $kataCari = '%' . $cari . '%';

    $stmtDaftar->execute([
        'kode' => $kataCari,
        'nama' => $kataCari,
        'kategori' => $kataCari
    ]);
} else {
    $stmtDaftar = $pdo->query(
        "SELECT kode_barang, nama_barang, kategori,
                total_stok, rak_penyimpanan
         FROM barang
         ORDER BY nama_barang ASC"
    );
}

$daftarBarang = $stmtDaftar->fetchAll(PDO::FETCH_ASSOC);
$kategoriTerjemahan = [
    'ATK' => 'stationery',
    'Elektronik' => 'electronics',
    'Perlengkapan Kebersihan' => 'cleaning',
    'Perabot' => 'furniture',
    'Lainnya' => 'other'
];
?>

<?php include 'includes/header.php'; ?>

<main class="container">

    <!-- Judul dan sambutan -->
    <div class="page-heading">
        <div>
            <h1><?= lang('dashboard') ?></h1>
            <p><?= lang('welcome') ?>, <?= $nama ?>.</p>
        </div>
    </div>

    <!-- Ringkasan -->
    <section class="summary-grid">

        <div class="summary-card">
            <p><?= lang('total_items') ?></p>
            <h2>
                <?= number_format(
                    (int) $totalBarang,
                    0,
                    ',',
                    '.'
                ) ?>
            </h2>
        </div>

        <div class="summary-card">
            <p><?= lang('total_stock') ?></p>
            <h2>
                <?= number_format(
                    (int) $totalStok,
                    0,
                    ',',
                    '.'
                ) ?>
            </h2>
        </div>

    </section>

    <!-- Informasi dashboard -->
    <section class="content-card">
        <h2><?= lang('welcome_title') ?></h2>

        <p class="highlight-message">
            <?= lang('welcome_message') ?>
        </p>
    </section>

    <!-- Daftar barang -->
    <section class="content-card">

        <div class="page-heading">
            <div>
                <h2><?= htmlspecialchars(lang('item_list_title'), ENT_QUOTES, 'UTF-8') ?></h2>
                <p><?= htmlspecialchars(lang('item_list_description'), ENT_QUOTES, 'UTF-8') ?></p>
            </div>
        </div>

        <!-- Kotak pencarian -->
        <form
            method="GET"
            action="dashboard.php"
            class="search-form"
        >
            <input
                type="text"
                name="cari"
                placeholder="<?= htmlspecialchars(lang('search_placeholder'), ENT_QUOTES, 'UTF-8') ?>"
                value="<?= htmlspecialchars(
                    $cari,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >

            <button type="submit" class="btn-primary">
                <?= htmlspecialchars(lang('search'), ENT_QUOTES, 'UTF-8') ?>
            </button>

            <?php if ($cari !== ''): ?>
                <a
                    href="dashboard.php"
                    class="btn-secondary"
                >
                    <?= htmlspecialchars(lang('reset'), ENT_QUOTES, 'UTF-8') ?>
                </a>
            <?php endif; ?>
        </form>

        <!-- Tabel daftar barang -->
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th><?= htmlspecialchars(lang('number'), ENT_QUOTES, 'UTF-8') ?></th>
                        <th><?= htmlspecialchars(lang('item_code'), ENT_QUOTES, 'UTF-8') ?></th>
                        <th><?= htmlspecialchars(lang('item_name'), ENT_QUOTES, 'UTF-8') ?></th>
                        <th><?= htmlspecialchars(lang('category'), ENT_QUOTES, 'UTF-8') ?></th>
                        <th><?= htmlspecialchars(lang('stock'), ENT_QUOTES, 'UTF-8') ?></th>
                        <th><?= htmlspecialchars(lang('storage_rack'), ENT_QUOTES, 'UTF-8') ?></th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($daftarBarang)): ?>

                        <?php foreach ($daftarBarang as $i => $barang): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>

                                <td>
                                    <?= htmlspecialchars(
                                        $barang['kode_barang'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $barang['nama_barang'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </td>

                                <td>
                                    <?php
                                    $keyKategori = $kategoriTerjemahan[$barang['kategori']] ?? 'other';
                                    echo htmlspecialchars(lang($keyKategori), ENT_QUOTES, 'UTF-8');
                                    ?>
                                </td>

                                <td>
                                    <?= number_format(
                                        (int) $barang['total_stok'],
                                        0,
                                        ',',
                                        '.'
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $barang['rak_penyimpanan'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    <?php else: ?>
                        <tr>
                            <td
                                colspan="6"
                                style="text-align: center;"
                            >
                                <?= $cari !== ''
                                    ? lang('item_not_found')
                                    : lang('no_items') ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </section>

</main>

<?php include 'includes/footer.php'; ?>
