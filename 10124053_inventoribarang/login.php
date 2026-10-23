
<?php
session_start();

require 'config/database.php';
require 'config/lang.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = lang('login_required');
    } else {
        $stmt = $pdo->prepare(
            "SELECT id, username, password, nama
             FROM users
             WHERE username = :username
             LIMIT 1"
        );

        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama'] = $user['nama'];

            if (!isset($_SESSION['bahasa'])) {
                $_SESSION['bahasa'] = 'id';
            }

            header('Location: dashboard.php');
            exit;
        }

        $error = lang('invalid_login');
    }
}

$bahasa = $_SESSION['bahasa'] ?? 'id';
$judulHalaman = lang('login');

include 'includes/header.php';
?>

<main class="login-container">
    <div class="login-card">

        <h1>
            <?= htmlspecialchars(lang('app_name'), ENT_QUOTES, 'UTF-8') ?>
        </h1>

        <p>
            <?= htmlspecialchars(lang('login_message'), ENT_QUOTES, 'UTF-8') ?>
        </p>

        <div class="login-language">
            <a href="bahasa.php?set=id&amp;tujuan=login.php"
               class="<?= $bahasa === 'id' ? 'active' : '' ?>">ID</a>
            <span>|</span>
            <a href="bahasa.php?set=en&amp;tujuan=login.php"
               class="<?= $bahasa === 'en' ? 'active' : '' ?>">EN</a>
        </div>

        <?php if ($error !== ''): ?>
            <div class="alert-error">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="username">
                    <?= htmlspecialchars(lang('username'), ENT_QUOTES, 'UTF-8') ?>
                </label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    autocomplete="username"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password">
                    <?= htmlspecialchars(lang('password'), ENT_QUOTES, 'UTF-8') ?>
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    autocomplete="current-password"
                    required
                >
            </div>

            <button type="submit" class="btn-primary login-button">
                <?= htmlspecialchars(lang('login'), ENT_QUOTES, 'UTF-8') ?>
            </button>
        </form>

    </div>
</main>

<?php include 'includes/footer.php'; ?>
