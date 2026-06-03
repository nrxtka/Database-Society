<?php
$host     = "localhost";
$db_user  = "root";      
$db_pass  = "";          
$db_name  = "basisdata2026";

$koneksi = mysqli_connect($host, $db_user, $db_pass, $db_name);

$error_message = "";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $nim      = mysqli_real_escape_string($koneksi, $_POST['nim']);

    $query  = "SELECT * FROM tbl_anggota WHERE Username = '$username' AND NIM = '$nim'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['status_login'] = true;
        $_SESSION['username']     = $username;
        header("Location: index.php");
        exit;
    } else {
        $error_message = "Username atau NIM tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Sistem Informasi Akademik</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0 }

        :root {
            --bg: #f0f4ff;
            --surface: #fff;
            --primary: #2563eb;
            --primary-d: #1d4ed8;
            --primary-l: #eff6ff;
            --danger: #ef4444;
            --text-1: #0f172a;
            --text-2: #475569;
            --text-3: #94a3b8;
            --border: #e2e8f0;
            --shadow-md: 0 4px 24px rgba(37,99,235,.14), 0 2px 8px rgba(0,0,0,.06);
            --shadow-lg: 0 12px 40px rgba(37,99,235,.18), 0 4px 16px rgba(0,0,0,.08);
            --radius: 14px;
            --radius-sm: 8px;
            --font: 'Plus Jakarta Sans', sans-serif;
        }

        html { scroll-behavior: smooth }

        body {
            font-family: var(--font);
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: fixed; inset: 0;
            pointer-events: none;
            background:
                radial-gradient(ellipse 80% 60% at 10% 10%, rgba(37,99,235,.12) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 90% 85%, rgba(6,182,212,.09) 0%, transparent 60%);
        }

        .login-wrap {
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 1;
        }

        .login-card {
            background: var(--surface);
            border-radius: 20px;
            padding: 44px 40px;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border);
            text-align: center;
        }

        .login-logo {
            width: 120px;
            margin-bottom: 22px;
            filter: drop-shadow(0 6px 16px rgba(37,99,235,.22));
        }

        .login-badge {
            display: inline-block;
            background: var(--primary-l);
            color: var(--primary);
            border-radius: 20px;
            padding: 4px 14px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .login-card h2 {
            font-size: 22px;
            font-weight: 800;
            color: var(--text-1);
            margin-bottom: 6px;
            letter-spacing: -.4px;
        }

        .login-card .sub {
            font-size: 13px;
            color: var(--text-3);
            margin-bottom: 28px;
        }

        .error-msg {
            background: #fef2f2;
            color: var(--danger);
            border: 1px solid #fecaca;
            border-radius: var(--radius-sm);
            padding: 11px 16px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            text-align: left;
        }

        .form-group {
            margin-bottom: 16px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: var(--text-2);
            letter-spacing: .05em;
            text-transform: uppercase;
            margin-bottom: 7px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-family: var(--font);
            color: var(--text-1);
            background: #fafbff;
            outline: none;
            transition: border-color .18s, box-shadow .18s, background .18s;
        }

        .form-group input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37,99,235,.12);
            background: #fff;
        }

        .form-group input::placeholder { color: var(--text-3) }

        .btn-masuk {
            width: 100%;
            padding: 13px;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-weight: 700;
            font-family: var(--font);
            letter-spacing: .5px;
            cursor: pointer;
            margin-top: 8px;
            box-shadow: 0 4px 14px rgba(37,99,235,.35);
            transition: all .2s;
        }

        .btn-masuk:hover {
            background: var(--primary-d);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(37,99,235,.45);
        }

        .btn-masuk:active { transform: scale(.98) }

        .login-footer {
            margin-top: 22px;
            font-size: 12px;
            color: var(--text-3);
        }

        ::-webkit-scrollbar { width: 5px }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 10px }
    </style>
</head>
<body>

<div class="login-wrap">
    <div class="login-card">
        <img src="unidaclear.png" alt="Logo Universitas Djuanda" class="login-logo">
        <div class="login-badge">🎓 Basis Data 2026</div>
        <h2>Masuk ke Sistem</h2>
        <p class="sub">Sistem Informasi Akademik — Universitas Djuanda</p>

        <?php if (!empty($error_message)): ?>
            <div class="error-msg">⚠️ <?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Masukkan username" required autocomplete="off">
            </div>
            <div class="form-group">
                <label>NIM</label>
                <input type="text" name="nim" placeholder="Masukkan NIM" required autocomplete="off">
            </div>
            <button type="submit" name="login" class="btn-masuk">MASUK →</button>
        </form>

        <div class="login-footer">
            © 2026 Universitas Djuanda · Sistem Informasi Akademik
        </div>
    </div>
</div>

</body>
</html>
