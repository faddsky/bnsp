<?php
// pages/order_form.php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../config/db.php';

$err = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user']['id'];
    $nama = trim($_POST['nama_pelanggan'] ?? '');
    $jenis = trim($_POST['jenis_kopi'] ?? '');
    $ukuran = trim($_POST['ukuran'] ?? 'M');
    $jumlah = (int)($_POST['jumlah'] ?? 1);
    $catatan = trim($_POST['catatan'] ?? '');

    if ($nama === '' || $jenis === '') {
        $err = "Nama dan jenis kopi wajib diisi.";
    } else {
        $ins = $mysqli->prepare("
            INSERT INTO orders (user_id, nama_pelanggan, jenis_kopi, ukuran, jumlah, catatan)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $ins->bind_param('isssis', $user_id, $nama, $jenis, $ukuran, $jumlah, $catatan);
        if ($ins->execute()) {
            $success = "Pesanan berhasil disimpan ☕";
        } else {
            $err = "Gagal menyimpan pesanan: " . htmlspecialchars($ins->error);
        }
        $ins->close();
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Pesan Kopi - Nawasena Coffee</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
    }
    body {
      background: linear-gradient(135deg, #cbb89d, #8b5e34);
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
    }
    /* Navbar */
    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 40px;
      background: #4b2e05;
      color: white;
    }
    .navbar .logo {
      font-size: 24px;
      font-weight: 700;
      font-style: italic;
      letter-spacing: 1px;
    }
    .navbar ul {
      list-style: none;
      margin: 0;
      padding: 0;
      display: flex;
      gap: 25px;
      align-items: center;
    }
    .navbar ul li a {
      text-decoration: none;
      color: #fff;
      font-weight: 500;
      transition: color 0.3s;
    }
    .navbar ul li a:hover {
      color: #c9a66b;
    }
    .dropdown-content {
      display: none;
      position: absolute;
      background: #fff;
      min-width: 150px;
      right: 0;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
      border-radius: 8px;
      z-index: 1;
    }
    .dropdown-content a {
      color: #3e2c20;
      padding: 10px 15px;
      text-decoration: none;
      display: block;
    }
    .dropdown-content a:hover {
      background: #f3f0ed;
    }
    .dropdown:hover .dropdown-content {
      display: block;
    }

    /* Container */
    .container {
      background: #fff;
      padding: 40px 50px;
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
      width: 450px;
      margin: 60px auto;
      text-align: center;
      animation: fadeIn 0.5s ease-in-out;
    }
    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(20px);}
      to {opacity: 1; transform: translateY(0);}
    }
    h2 {
      color: #6f4e37;
      margin-bottom: 15px;
    }
    p {
      font-size: 14px;
      color: #3e2c20;
      margin-bottom: 20px;
    }
    a {
      color: #6f4e37;
      font-weight: 600;
      text-decoration: none;
    }
    a:hover {
      text-decoration: underline;
    }
    label {
      display: block;
      text-align: left;
      margin: 10px 0 5px;
      font-weight: 600;
      color: #3e2c20;
    }
    input, select, textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 14px;
      resize: none;
    }
    textarea {
      height: 70px;
    }
    button {
      background: #6f4e37;
      color: white;
      border: none;
      padding: 12px 0;
      border-radius: 8px;
      cursor: pointer;
      width: 100%;
      font-size: 16px;
      font-weight: 600;
      margin-top: 15px;
      transition: 0.3s;
    }
    button:hover {
      background: #8b5e34;
    }
    .error, .success {
      padding: 10px;
      border-radius: 8px;
      margin-bottom: 15px;
      font-size: 14px;
    }
    .error {
      background: #ffe5e5;
      color: #a00;
      border: 1px solid #e99;
    }
    .success {
      background: #e5ffe5;
      color: #060;
      border: 1px solid #9e9;
    }

    @media(max-width:500px){
      .container { width: 90%; padding: 30px; }
      .navbar { flex-direction: column; text-align: center; gap: 10px; }
      .navbar ul { flex-direction: column; gap: 8px; }
    }
  </style>
</head>
<body>

  <nav class="navbar">
    <div class="logo">☕ NSB Coffee</div>
    <ul>
      <li><a href="dashboard_user.php">Dashboard</a></li>
      <li><a href="order.php">Lihat Pesanan</a></li>
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
    <h2>NSB Coffee</h2>
    <p>Halo, <strong><?= htmlspecialchars($_SESSION['user']['username']) ?></strong></p>

    <?php if ($err): ?><div class="error"><?= htmlspecialchars($err) ?></div><?php endif; ?>
    <?php if ($success): ?><div class="success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

    <form method="post">
      <label>Nama Pelanggan</label>
      <input type="text" name="nama_pelanggan" placeholder="Masukkan nama anda" required>

      <label>Jenis Kopi</label>
      <select name="jenis_kopi" required>
        <option value="">-- Pilih Jenis Kopi --</option>
        <option>Espresso</option>
        <option>Americano</option>
        <option>Cappuccino</option>
        <option>Latte</option>
        <option>Mocha</option>
      </select>

      <label>Ukuran</label>
      <select name="ukuran">
        <option value="S">Small</option>
        <option value="M" selected>Medium</option>
        <option value="L">Large</option>
      </select>

      <label>Jumlah</label>
      <input type="number" name="jumlah" min="1" value="1">

      <label>Catatan</label>
      <textarea name="catatan" placeholder="Contoh: tanpa gula, extra shot..."></textarea>

      <button type="submit">☕ Pesan Sekarang</button>
    </form>
  </div>

</body>
</html>
