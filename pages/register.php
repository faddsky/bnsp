<?php
session_start();
if (isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit;
}
require_once __DIR__ . '/../config/db.php';

$err = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if ($username === '' || $password === '') {
        $err = "Isi semua field.";
    } elseif ($password !== $password2) {
        $err = "Password tidak cocok.";
    } else {
        $stmt = $mysqli->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $err = "Username sudah dipakai.";
            $stmt->close();
        } else {
            $stmt->close();
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins = $mysqli->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')");
            $ins->bind_param('ss', $username, $hash);
            if ($ins->execute()) {
                $success = "Registrasi berhasil. Silakan <a href='login.php'>login</a>.";
            } else {
                $err = "Gagal registrasi: " . $ins->error;
            }
            $ins->close();
        }
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Register - Coffee Shop</title>
  <style>
    * {
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }
    body {
      margin: 0;
      height: 100vh;
      background: linear-gradient(135deg, #4b2e05 0%, #2c1b07 100%);
      display: flex;
      justify-content: center;
      align-items: center;
      color: #333;
    }
    .container {
      background: #fffaf3;
      padding: 40px 50px;
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.2);
      width: 100%;
      max-width: 420px;
      text-align: center;
      animation: fadeIn 0.8s ease-in-out;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    h2 {
      color: #4b2e05;
      margin-bottom: 20px;
    }
    label {
      display: block;
      text-align: left;
      margin: 8px 0 4px;
      font-weight: 600;
      color: #4b2e05;
    }
    input[type="text"], input[type="password"] {
      width: 100%;
      padding: 12px;
      border: 1px solid #c9a66b;
      border-radius: 8px;
      font-size: 14px;
      background-color: #fff;
      transition: all 0.2s ease-in-out;
    }
    input:focus {
      border-color: #8b5e34;
      outline: none;
      box-shadow: 0 0 5px rgba(139,94,52,0.5);
    }
    button {
      background-color: #8b5e34;
      color: white;
      border: none;
      padding: 12px;
      border-radius: 8px;
      width: 100%;
      font-size: 15px;
      cursor: pointer;
      transition: 0.3s;
      margin-top: 10px;
    }
    button:hover {
      background-color: #704727;
    }
    .error {
      background-color: #ffe0e0;
      border-left: 4px solid #ff5c5c;
      color: #a00000;
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 5px;
      font-size: 14px;
    }
    .success {
      background-color: #e6ffe8;
      border-left: 4px solid #1ca73a;
      color: #0a7c1c;
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 5px;
      font-size: 14px;
    }
    p {
      margin-top: 20px;
      color: #4b2e05;
    }
    a {
      color: #8b5e34;
      text-decoration: none;
      font-weight: 600;
    }
    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>☕ Register Coffee Shop</h2>
    <?php if($err): ?><div class="error"><?=htmlspecialchars($err)?></div><?php endif; ?>
    <?php if($success): ?><div class="success"><?= $success ?></div><?php endif; ?>
    <form method="post">
      <label>Username</label>
      <input type="text" name="username" required>

      <label>Password</label>
      <input type="password" name="password" required>

      <label>Confirm Password</label>
      <input type="password" name="password2" required>

      <button type="submit">Daftar</button>
    </form>
    <p>Sudah punya akun? <a href="login.php">Login</a></p>
  </div>
</body>
</html>
