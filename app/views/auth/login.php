<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Login — <?= defined('APP_NAME') ? htmlspecialchars(APP_NAME) : 'RUANGMP' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASEURL ?>/../css/admin.css">
    <link rel="stylesheet" href="<?= BASEURL ?>/../css/login.css">
</head>
<body>
    <div class="login-wrap">
        <div class="login-box">
            <div class="login-brand">
                <div class="login-logo">📖</div>
                <div>
                    <b><?= defined('APP_NAME') ? htmlspecialchars(APP_NAME) : 'RUANGMP' ?></b>
                    <small>RUANGMP • Perpustakaan Digital</small>
                </div>
            </div>

            <h1 class="login-title">Login ke Akun Anda</h1>
            <p class="login-sub">Masuk menggunakan akun Admin atau Anggota.</p>

            <?php
            $flashMsg = $flash ?? ($_SESSION['flash'] ?? null);
            if (isset($_SESSION['flash'])) {
                unset($_SESSION['flash']);
            }
            if ($flashMsg) :
            ?>
                <div class="alert-box alert-<?= htmlspecialchars($flashMsg['type'] ?? 'error') ?>">
                    <?= htmlspecialchars($flashMsg['message'] ?? '') ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= BASEURL ?>/auth/authenticate">
                <?= Security::csrfField() ?>

                <div class="field">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Masukkan username" required autofocus autocomplete="username">
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <div class="pass-wrap">
                        <input type="password" id="password" name="password" placeholder="Masukkan password" required autocomplete="current-password">
                        <button type="button" class="pass-toggle" id="togglePass" aria-label="Tampilkan password">👁</button>
                    </div>
                </div>

                <button type="submit" class="btn btn-login">Masuk →</button>
            </form>

            <div class="login-foot">
                © 2026 RUANGMP • Sistem Informasi Perpustakaan
            </div>
        </div>
    </div>

    <script>
        const togglePass = document.getElementById('togglePass');
        const passInput = document.getElementById('password');
        if (togglePass && passInput) {
            togglePass.addEventListener('click', () => {
                const isHidden = passInput.type === 'password';
                passInput.type = isHidden ? 'text' : 'password';
                togglePass.textContent = isHidden ? '🙈' : '👁';
            });
        }
    </script>
</body>
</html>