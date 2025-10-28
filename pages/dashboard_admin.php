<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}
require_once __DIR__ . '/../config/db.php';

// ---- Hapus Pesanan ----
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $stmt_del = $mysqli->prepare("DELETE FROM orders WHERE id = ?");
    $stmt_del->bind_param("i", $delete_id);
    $stmt_del->execute();
    $stmt_del->close();
    header("Location: dashboard_admin.php"); // refresh halaman
    exit;
}

// ---- Ambil Semua Pesanan ----
$stmt = $mysqli->prepare("SELECT o.id, o.nama_pelanggan, o.jenis_kopi, o.ukuran, o.jumlah, o.catatan, o.tanggal_pesan, u.username as ordered_by
FROM orders o
JOIN users u ON o.user_id = u.id
ORDER BY o.tanggal_pesan DESC");
$stmt->execute();
$res = $stmt->get_result();
$orders = $res->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Dashboard Admin - Coffee Shop</title>
  <style>
    body {
      font-family: "Poppins", sans-serif;
      background: #f3f4f6;
      margin: 0;
      padding: 0;
    }

    .container {
      width: 90%;
      max-width: 1100px;
      margin: 40px auto;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      color: #4b2e05;
      margin-bottom: 10px;
    }

    p {
      text-align: center;
      font-size: 15px;
      color: #555;
    }

    a {
      color: #7b3f00;
      text-decoration: none;
      font-weight: 500;
    }

    a:hover {
      text-decoration: underline;
    }

    h3 {
      margin-top: 40px;
      color: #4b2e05;
      border-bottom: 2px solid #d1b38e;
      padding-bottom: 6px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      border-radius: 10px;
      overflow: hidden;
    }

    th, td {
      padding: 12px 15px;
      text-align: left;
    }

    thead {
      background-color: #7b3f00;
      color: #fff;
    }

    tbody tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    tbody tr:hover {
      background-color: #fff5e6;
      transition: 0.3s;
    }

    td {
      color: #333;
      font-size: 14px;
    }

    .empty {
      text-align: center;
      color: #777;
      padding: 20px;
      font-style: italic;
    }

    .logout-btn {
      background: #7b3f00;
      color: #fff;
      padding: 8px 14px;
      border-radius: 6px;
      font-size: 14px;
      transition: 0.3s;
    }

    .logout-btn:hover {
      background: #532800;
    }

    .delete-btn {
      background: #c0392b;
      color: white;
      padding: 6px 10px;
      border-radius: 5px;
      text-decoration: none;
      font-size: 13px;
      transition: 0.3s;
    }

    .delete-btn:hover {
      background: #a93226;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>☕ Dashboard Admin</h2>
    <p>Halo, <strong><?= htmlspecialchars($_SESSION['user']['username']) ?></strong> —
      <a class="logout-btn" href="../logout.php">Logout</a>
    </p>

    <h3>📋 Daftar Semua Pesanan</h3>

    <?php if (empty($orders)): ?>
      <div class="empty">Belum ada pesanan.</div>
    <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Nama Pelanggan</th>
            <th>Jenis Kopi</th>
            <th>Ukuran</th>
            <th>Jumlah</th>
            <th>Catatan</th>
            <th>Pemesan</th>
            <th>Tanggal Pesan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($orders as $o): ?>
            <tr>
              <td><?= $o['id'] ?></td>
              <td><?= htmlspecialchars($o['nama_pelanggan']) ?></td>
              <td><?= htmlspecialchars($o['jenis_kopi']) ?></td>
              <td><?= htmlspecialchars($o['ukuran']) ?></td>
              <td><?= htmlspecialchars($o['jumlah']) ?></td>
              <td><?= htmlspecialchars($o['catatan']) ?></td>
              <td><?= htmlspecialchars($o['ordered_by']) ?></td>
              <td><?= htmlspecialchars($o['tanggal_pesan']) ?></td>
              <td>
                <a class="delete-btn" href="?delete=<?= $o['id'] ?>" onclick="return confirm('Yakin ingin menghapus pesanan ini?')">🗑️ Hapus</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</body>
</html>
