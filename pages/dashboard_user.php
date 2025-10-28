<?php
// pages/dashboard_user.php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../config/db.php';
$user_id = $_SESSION['user']['id'];

// Ambil pesanan user
$stmt = $mysqli->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY tanggal_pesan DESC");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard - NSB Coffee</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
* { box-sizing: border-box; margin:0; padding:0; }
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #f6e5d3, #d1a77b);
    min-height: 100vh;
}

/* Navbar */
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 40px;
    background-color: #4b2e05;
    color: #fff;
}
.navbar .logo { font-size: 24px; font-weight: 700; font-style: italic; }
.navbar ul { list-style: none; display: flex; gap: 20px; }
.navbar ul li a { text-decoration: none; color: #fff; font-weight: 500; }
.navbar ul li a:hover { color: #c9a66b; }
.dropdown { position: relative; }
.dropdown-content {
    display: none;
    position: absolute;
    background: #fff;
    min-width: 140px;
    right: 0;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    overflow: hidden;
}
.dropdown-content a { display: block; padding: 10px 15px; color: #3e2c20; text-decoration: none; }
.dropdown-content a:hover { background: #f3f0ed; }
.dropdown:hover .dropdown-content { display: block; }

/* Container */
.container {
    max-width: 900px;
    margin: 60px auto;
    background: #fff;
    padding: 40px;
    border-radius: 16px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    animation: fadeIn 0.6s ease-in-out;
}
@keyframes fadeIn { from {opacity:0; transform: translateY(20px);} to {opacity:1; transform: translateY(0);} }

h2 { color: #6f4e37; margin-bottom: 20px; }
p { color: #3e2c20; line-height: 1.6; margin-bottom: 20px; }

/* Cards */
.cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px,1fr)); gap: 20px; }
.card {
    background: #fdf3e7;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    text-align: center;
    transition: 0.3s;
}
.card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
.card h3 { color: #6f4e37; margin-bottom: 10px; font-size: 18px; }
.card p { font-size: 16px; font-weight: 500; color: #8b5e34; }
.card .status { margin-top: 10px; font-size: 14px; font-weight: 600; }

.button {
    display: inline-block;
    padding: 12px 25px;
    background: #6f4e37;
    color: #fff;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: 0.3s;
}
.button:hover { background: #8b5e34; }

@media(max-width: 500px){
    .navbar { flex-direction: column; gap: 10px; text-align: center; }
    .navbar ul { flex-direction: column; gap: 10px; }
    .container { margin: 40px 20px; padding: 30px; }
}
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
    <h2>Selamat Datang, <?= htmlspecialchars($_SESSION['user']['username']) ?>!</h2>
    <p>
      PT. Nawesena Sukses Bersama merupakan suatu Perusahaan Perorangan yang
        berdiri pada tanggal 27 Agustus 2024. Perusahaan ini bergerak di bidang
        supplier biji kopi jenis Arabika dan Robusta yang tersebar di seluruh Indonesia.
    </p>

    <?php if(count($orders) === 0): ?>
        <p>Apakah ingin mulai memesan kopi favoritmu?</p>
        <a href="order_form.php" class="button">Buat Pesanan</a>
    <?php else: ?>
        <h3>Pesanan Anda</h3>
        <div class="cards">
            <?php foreach($orders as $order): ?>
                <div class="card">
                    <h3><?= htmlspecialchars($order['jenis_kopi']) ?> (<?= $order['ukuran'] ?>)</h3>
                    <p>Jumlah: <?= $order['jumlah'] ?></p>
                    <?php if($order['catatan']): ?>
                        <p>Catatan: <?= htmlspecialchars($order['catatan']) ?></p>
                    <?php endif; ?>
                    <p class="status">Status: <?= htmlspecialchars($order['status'] ?? 'Menunggu') ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
