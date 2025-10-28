<?php
// pages/profile.php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header('Location: login.php');
    exit;
}
require_once __DIR__ . '/../config/db.php';

$user_id = $_SESSION['user']['id'];
$err = '';
$success = '';

// Ambil data profil dari database (hanya username)
$stmt = $mysqli->prepare("SELECT username FROM users WHERE id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Update profil jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '') {
        $err = "Username wajib diisi.";
    } else {
        if ($password !== '') {
            // Update username dan password
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $upd = $mysqli->prepare("UPDATE users SET username=?, password=? WHERE id=?");
            $upd->bind_param('ssi', $username, $hashed, $user_id);
        } else {
            // Update username saja
            $upd = $mysqli->prepare("UPDATE users SET username=? WHERE id=?");
            $upd->bind_param('si', $username, $user_id);
        }

        if ($upd->execute()) {
            $success = "Profil berhasil diperbarui ✅";
            $_SESSION['user']['username'] = $username; // update session
        } else {
            $err = "Gagal memperbarui profil: " . htmlspecialchars($upd->error);
        }
        $upd->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Profil - NSB Coffee</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
* { box-sizing: border-box; margin:0; padding:0; }
body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #f6e5d3, #d1a77b); min-height:100vh; }

/* Navbar */
.navbar {
    background: #4b2e05; color:#fff;
    display:flex; justify-content:space-between; align-items:center;
    padding:12px 30px; box-shadow:0 3px 8px rgba(0,0,0,0.15);
}
.navbar .logo { font-weight:600; font-size:18px; }
.navbar ul { list-style:none; display:flex; gap:15px; margin:0; padding:0; }
.navbar ul li { position:relative; }
.navbar ul li a { color:#fff; text-decoration:none; font-weight:500; transition:0.3s; }
.navbar ul li a:hover { color:#f2d2b6; }

/* Dropdown */
.dropdown-content { display:none; position:absolute; background:#fff; min-width:150px; right:0; box-shadow:0 4px 10px rgba(0,0,0,0.2); border-radius:8px; z-index:1; }
.dropdown-content a { display:block; padding:10px 15px; color:#3e2c20; text-decoration:none; }
.dropdown-content a:hover { background:#f3f0ed; }
.dropdown:hover .dropdown-content { display:block; }

/* Container */
.container {
    background:#fff; padding:40px 30px; border-radius:16px;
    box-shadow:0 8px 20px rgba(0,0,0,0.15); max-width:450px; margin:60px auto;
    animation:fadeIn 0.5s ease-in-out;
}
@keyframes fadeIn { from{opacity:0; transform:translateY(20px);} to{opacity:1; transform:translateY(0);} }

h2 { color:#6f4e37; margin-bottom:20px; text-align:center; }
label { display:block; margin:10px 0 5px; font-weight:600; color:#3e2c20; text-align:left; }
input { width:100%; padding:10px; border:1px solid #ddd; border-radius:8px; font-size:14px; margin-bottom:10px; }

button {
    width:100%; padding:12px 0; background:#6f4e37; color:#fff; border:none;
    border-radius:8px; font-weight:600; font-size:16px; cursor:pointer; margin-top:10px; transition:0.3s;
}
button:hover { background:#8b5e34; }

/* Notifications */
.error, .success { padding:10px; border-radius:8px; margin-bottom:15px; font-size:14px; }
.error { background:#ffe5e5; color:#a00; border:1px solid #e99; }
.success { background:#e5ffe5; color:#060; border:1px solid #9e9; }

@media(max-width:500px){ .container { margin:40px 15px; padding:30px; } }
</style>
</head>
<body>

<nav class="navbar">
    <div class="logo">☕ NSB Coffee</div>
    <ul>
      <li><a href="dashboard_user.php">Dashboard</a></li>
      <li><a href="order.php">Pesanan</a></li>
      <li><a href="order_form.php">Buat Pesanan</a></li>
      <li><a href="menu.php">Menu</a></li>
      <li class="dropdown">
        <a href="#">Akun ▾</a>
        <div class="dropdown-content">
          <a href="profile.php">Profil</a>
          <a href="../logout.php" style="color:#a33;">Logout</a>
        </div>
      </li>
    </ul>
</nav>

<div class="container">
    <h2>Profil Pengguna</h2>

    <?php if($err): ?><div class="error"><?= htmlspecialchars($err) ?></div><?php endif; ?>
    <?php if($success): ?><div class="success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

    <form method="post">
        <label>Username</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username'] ?? '') ?>" required>

        <label>Password Baru (opsional)</label>
        <input type="password" name="password" placeholder="Biarkan kosong jika tidak ingin mengubah">

        <button type="submit">💾 Simpan Perubahan</button>
    </form>
</div>

</body>
</html>
