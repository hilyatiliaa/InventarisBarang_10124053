
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$bahasa = $_SESSION['bahasa'] ?? 'id';
$halamanAktif = basename($_SERVER['PHP_SELF']);
$halamanLogin = ($halamanAktif === 'login.php');
$queryString = $_SERVER['QUERY_STRING'] ?? '';

$namaAplikasi = lang('app_name');
$judulHalaman = $judulHalaman ?? $namaAplikasi;
?>

<!DOCTYPE html>
<html lang="<?= $bahasa === 'en' ? 'en' : 'id' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= htmlspecialchars($judulHalaman, ENT_QUOTES, 'UTF-8') ?>
        - <?= htmlspecialchars($namaAplikasi, ENT_QUOTES, 'UTF-8') ?>
    </title>

    <link rel="stylesheet" href="assets/style.css">
</head>

<body class="<?= $halamanLogin ? 'login-page' : 'app-page' ?>">

<?php if (!$halamanLogin): ?>
    <header class="topbar">
        <div class="topbar-inner">

            <h2>
                <?= htmlspecialchars($namaAplikasi, ENT_QUOTES, 'UTF-8') ?>
            </h2>

            <nav>
                <a
                    href="dashboard.php"
                    class="<?= $halamanAktif === 'dashboard.php' ? 'active' : '' ?>"
                ><?= htmlspecialchars(lang('dashboard'), ENT_QUOTES, 'UTF-8') ?></a>

                <a
                    href="barang.php"
                    class="<?= in_array($halamanAktif, ['barang.php', 'tambah.php', 'edit.php'], true) ? 'active' : '' ?>"
                ><?= htmlspecialchars(lang('items'), ENT_QUOTES, 'UTF-8') ?></a>

                <span class="language-switch">
                    <a
                        href="bahasa.php?set=id&amp;tujuan=<?= urlencode($halamanAktif) ?>&amp;query=<?= urlencode($queryString) ?>"
                        class="<?= $bahasa === 'id' ? 'active' : '' ?>"
                    >ID</a>

                    <span>|</span>

                    <a
                        href="bahasa.php?set=en&amp;tujuan=<?= urlencode($halamanAktif) ?>&amp;query=<?= urlencode($queryString) ?>"
                        class="<?= $bahasa === 'en' ? 'active' : '' ?>"
                    >EN</a>
                </span>

                <a href="logout.php">
                    <?= htmlspecialchars(lang('logout'), ENT_QUOTES, 'UTF-8') ?>
                </a>
            </nav>

        </div>
    </header>
<?php endif; ?>
