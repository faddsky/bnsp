<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header('Location: login.php');
    exit;
}
require_once __DIR__ . '/../config/db.php';

$user_id = $_SESSION['user']['id'];
$stmt = $mysqli->prepare("SELECT id, nama_pelanggan, jenis_kopi, ukuran, jumlah, catatan, tanggal_pesan FROM orders WHERE user_id = ? ORDER BY tanggal_pesan DESC");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$res = $stmt->get_result();
$orders = $res->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pesanan Saya - Nawasena Coffee</title>
  <style>
    * {
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }
    body {
      margin: 0;
      background-color: #fdf7f0;
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
      font-size: 22px;
      font-weight: 700;
      font-style: italic;
    }
    .navbar ul {
      list-style: none;
      display: flex;
      gap: 25px;
      margin: 0;
      padding: 0;
    }
    .navbar ul li a {
      color: #fff;
      text-decoration: none;
      font-weight: 500;
      transition: color 0.3s;
    }
    .navbar ul li a:hover {
      color: #c9a66b;
    }
    .dropdown {
      position: relative;
    }
    .dropdown-content {
      display: none;
      position: absolute;
      background-color: #fff;
      min-width: 140px;
      box-shadow: 0 5px 10px rgba(0,0,0,0.1);
      right: 0;
      top: 30px;
      border-radius: 6px;
      overflow: hidden;
      z-index: 10;
    }
    .dropdown-content a {
      color: #4b2e05;
      padding: 10px 15px;
      text-decoration: none;
      display: block;
      font-size: 14px;
    }
    .dropdown-content a:hover {
      background-color: #f9f4ee;
    }
    .dropdown:hover .dropdown-content {
      display: block;
    }

    /* Container */
    .container {
      max-width: 950px;
      margin: 50px auto;
      background: #fff;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
      text-align: center;
    }

    h2 {
      color: #4b2e05;
      margin-bottom: 20px;
    }

    /* Table */
    table {
      width: 100%;
      border-collapse: collapse;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }

    thead {
      background-color: #8b5e34;
      color: white;
    }

    th, td {
      padding: 12px 10px;
      font-size: 14px;
      text-align: center;
    }

    tr:nth-child(even) {
      background-color: #fdf7f0;
    }

    .btn {
      display: inline-block;
      background-color: #8b5e34;
      color: white;
      padding: 6px 12px;
      border-radius: 6px;
      text-decoration: none;
      transition: background-color 0.3s;
    }

    .btn:hover {
      background-color: #704727;
    }

    .no-orders {
      margin-top: 20px;
      padding: 20px;
      background-color: #fff9f2;
      border-radius: 10px;
      font-style: italic;
      color: #704727;
      box-shadow: inset 0 0 5px rgba(0,0,0,0.05);
    }
  </style>
</head>
<body>

  <nav class="navbar">
    <div class="logo">☕ NSB Coffee</div>
    <ul>
      <li><a href="dashboard_user.php">Dashboard</a></li>
      <li><a href="order.php" style="color:#c9a66b; font-weight:600;">Lihat Pesanan</a></li>
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
    <h2>Pesanan Saya</h2>

    <?php if(empty($orders)): ?>
      <div class="no-orders">Belum ada pesanan.</div>
    <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Jenis Kopi</th>
            <th>Ukuran</th>
            <th>Jumlah</th>
            <th>Catatan</th>
            <th>Tanggal Pesan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($orders as $o): ?>
          <tr>
            <td><?= $o['id'] ?></td>
            <td><?= htmlspecialchars($o['nama_pelanggan']) ?></td>
            <td><?= htmlspecialchars($o['jenis_kopi']) ?></td>
            <td><?= htmlspecialchars($o['ukuran']) ?></td>
            <td><?= htmlspecialchars($o['jumlah']) ?></td>
            <td><?= htmlspecialchars($o['catatan']) ?></td>
            <td><?= htmlspecialchars($o['tanggal_pesan']) ?></td>
            <td>
              <a href="edit_order.php?id=<?= $o['id'] ?>" class="btn">Edit</a>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>

</body>
</html>
