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
        $error_message = "Username atau NIM Salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Academic</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #f4f6f9; 
            margin: 0;
            display: flex;
            justify-content: center; 
            align-items: center;     
            height: 100vh;           
        }

        .login-card {
            background: #ffffff; 
            padding: 40px 35px; 
            border-radius: 10px; 
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); 
            border: 1px solid #e2e8f0; 
            width: 450px; 
            box-sizing: border-box;
            text-align: center;
        }

        .login-logo {
            width: 140px;
            margin-bottom: 15px;
            filter: drop-shadow(0px 4px 8px rgba(79, 70, 229, 0.2));
        }

        .login-title {
            color: #3b2a9f;
            margin-top: 0;
            margin-bottom: 25px;
            font-size: 22px;
            letter-spacing: 1px;
            font-weight: bold;
        }

        .input-group {
            margin-bottom: 20px;
        }
        
        .input-group input {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #cbd5e1; 
            border-radius: 6px;
            font-size: 15px;
            background-color: #ffffff; 
            color: #334155; 
            box-sizing: border-box;
            transition: all 0.3s ease;
        }
        
        .input-group input::placeholder {
            color: #94a3b8;
        }

        .input-group input:focus {
            border-color: #4f46e5; 
            outline: none;
            box-shadow: 0 0 5px rgba(79, 70, 229, 0.2);
        }

        .btn-masuk {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #4f46e5, #3b2a9f); 
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase; 
            cursor: pointer;
            margin-top: 10px;
            transition: opacity 0.2s, transform 0.1s;
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.2);
        }
        
        .btn-masuk:hover {
            opacity: 0.9; 
        }
        
        .btn-masuk:active {
            transform: scale(0.98); 
        }

        .error-msg {
            color: #dc2626;
            font-size: 14px;
            margin-bottom: 20px;
            background-color: #fee2e2;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #fca5a5;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="login-card">
    <img src="unidaclear.png" alt="Logo Universitas Djuanda" class="login-logo">
    <h2 class="login-title">LOGIN</h2>

    <?php if (!empty($error_message)) : ?>
        <div class="error-msg"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="input-group">
            <input type="text" name="username" placeholder="Username" required autocomplete="off">
        </div>
        
        <div class="input-group">
            <input type="text" name="nim" placeholder="NIM" required autocomplete="off">
        </div>
        
        <button type="submit" name="login" class="btn-masuk">MASUK</button>
    </form>
</div>

</body>
</html>
