
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

$stmt = $pdo->query(
    "SELECT kode_barang, nama_barang, kategori, total_stok, rak_penyimpanan
     FROM barang
     ORDER BY nama_barang ASC"
);
$barang = $stmt->fetchAll();

$judulHalaman = lang('items');

$kategoriTerjemahan = [
    'ATK' => 'stationery',
    'Elektronik' => 'electronics',
    'Perlengkapan Kebersihan' => 'cleaning',
    'Perabot' => 'furniture',
    'Lainnya' => 'other'
];

include 'includes/header.php';
?>

<main class="container">
    <div class="page-heading">
        <div>
            <h1><?= htmlspecialchars(lang('items'), ENT_QUOTES, 'UTF-8') ?></h1>
        </div>

        <a href="tambah.php" class="btn-primary">
            + <?= htmlspecialchars(lang('add_item'), ENT_QUOTES, 'UTF-8') ?>
        </a>
    </div>

    <section class="content-card">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th><?= htmlspecialchars(lang('item_code'), ENT_QUOTES, 'UTF-8') ?></th>
                        <th><?= htmlspecialchars(lang('item_name'), ENT_QUOTES, 'UTF-8') ?></th>
                        <th><?= htmlspecialchars(lang('category'), ENT_QUOTES, 'UTF-8') ?></th>
                        <th><?= htmlspecialchars(lang('stock'), ENT_QUOTES, 'UTF-8') ?></th>
                        <th><?= htmlspecialchars(lang('storage_rack'), ENT_QUOTES, 'UTF-8') ?></th>
                        <th><?= htmlspecialchars(lang('action'), ENT_QUOTES, 'UTF-8') ?></th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!$barang): ?>
                        <tr>
                            <td colspan="6">
                                <?= htmlspecialchars(lang('no_items'), ENT_QUOTES, 'UTF-8') ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($barang as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['kode_barang'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($item['nama_barang'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <?php
                                    $key = $kategoriTerjemahan[$item['kategori']] ?? 'other';
                                    echo htmlspecialchars(lang($key), ENT_QUOTES, 'UTF-8');
                                    ?>
                                </td>
                                <td><?= number_format((int) $item['total_stok'], 0, ',', '.') ?></td>
                                <td><?= htmlspecialchars($item['rak_penyimpanan'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a
                                            class="btn-secondary"
                                            href="edit.php?kode_barang=<?= urlencode($item['kode_barang']) ?>"
                                        >
                                            <?= htmlspecialchars(lang('edit'), ENT_QUOTES, 'UTF-8') ?>
                                        </a>

                                        <form
                                            method="POST"
                                            action="hapus.php"
                                            onsubmit="return confirm(<?= htmlspecialchars(json_encode(lang('confirm_delete')), ENT_QUOTES, 'UTF-8') ?>);"
                                        >
                                            <input
                                                type="hidden"
                                                name="csrf_token"
                                                value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>"
                                            >
                                            <input
                                                type="hidden"
                                                name="kode_barang"
                                                value="<?= htmlspecialchars($item['kode_barang'], ENT_QUOTES, 'UTF-8') ?>"
                                            >
                                            <button type="submit" class="btn-danger">
                                                <?= htmlspecialchars(lang('delete'), ENT_QUOTES, 'UTF-8') ?>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
