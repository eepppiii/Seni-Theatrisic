<?php
// api/auth/register.php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../includes/init.php';

if (isset($_SESSION['user_id'])) {
    header("Location: " . appUrl('index.php'));
    exit;
}

$error = '';
$error_type = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $nama_lengkap = trim($_POST['nama_lengkap'] ?? '');

    if (empty($username) || empty($password) || empty($confirm_password) || empty($nama_lengkap)) {
        $error = "Semua field wajib diisi!";
        $error_type = "warning";
    } elseif ($password !== $confirm_password) {
        $error = "Password dan Konfirmasi Password tidak cocok!";
        $error_type = "error";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter!";
        $error_type = "warning";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);

        if ($stmt->fetch()) {
            $error = "Username sudah digunakan! Silakan pilih username lain.";
            $error_type = "error";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password, nama_lengkap) VALUES (:username, :password, :nama)");

            try {
                $stmt->execute(['username' => $username, 'password' => $hashed, 'nama' => $nama_lengkap]);
                $success = "Registrasi berhasil! Silakan login.";
            } catch (PDOException $e) {
                $error = "Gagal mendaftar: " . $e->getMessage();
                $error_type = "error";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Seni Theatrisic</title>
    <link rel="stylesheet" href="<?= baseUrl() ?>assets/css/style.css?v=<?= time() ?>">
    <style>
        body { display: flex; justify-content: center; align-items: center; min-height: 100vh; background: #f4f7fb; font-family: "Inter", sans-serif; margin: 0; }
        .auth-container { background: white; padding: 2rem; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); width: 100%; max-width: 400px; border: 1px solid #e2e8f0; }
        .auth-container h2 { text-align: center; margin-bottom: 1.5rem; color: #0f172a; font-weight: 800; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; color: #64748b; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; }
        .form-group input { width: 100%; padding: 0.85rem 1rem; border: 1px solid #cbd5e1; border-radius: 8px; box-sizing: border-box; font-family: "Inter", sans-serif; background: #f8fafc; }
        .form-group input:focus { outline: none; border-color: #6366f1; background: #fff; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
        .btn-auth { width: 100%; padding: 0.9rem; background: #1e293b; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 1rem; font-weight: 700; transition: all 0.3s; }
        .btn-auth:hover { background: #4f46e5; transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(79,70,229,0.3); }
        .auth-footer { text-align: center; margin-top: 1.5rem; font-size: 0.9rem; color: #64748b; }
        .auth-footer a { color: #6366f1; text-decoration: none; font-weight: 700; }
        .auth-footer a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="auth-container">
        <h2>📝 Daftar Akun Baru</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" placeholder="Masukkan nama lengkap" required autofocus value="<?= htmlspecialchars($_POST['nama_lengkap'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Pilih username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Minimal 6 karakter" required>
            </div>
            <div class="form-group">
                <label>Konfirmasi Password</label>
                <input type="password" name="confirm_password" placeholder="Ulangi password" required>
            </div>
            <button type="submit" class="btn-auth">Daftar Sekarang</button>
        </form>
        <div class="auth-footer">
            Sudah punya akun? <a href="<?= appUrl('auth/login.php') ?>">Login di sini</a>
        </div>
    </div>

    <?php if ($error): ?>
    <script>alert("<?= htmlspecialchars($error, ENT_QUOTES) ?>");</script>
    <?php endif; ?>

    <?php if ($success): ?>
    <script>
        alert("<?= htmlspecialchars($success, ENT_QUOTES) ?>");
        window.location.href = "<?= appUrl('auth/login.php') ?>";
    </script>
    <?php endif; ?>
</body>
</html>