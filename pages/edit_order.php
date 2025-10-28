<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header('Location: login.php');
    exit;
}
require_once __DIR__ . '/../config/db.php';

if (!isset($_GET['id'])) {
    header('Location: dashboard_user.php');
    exit;
}

$id = $_GET['id'];
$user_id = $_SESSION['user']['id'];

// Ambil data pesanan
$stmt = $mysqli->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param('ii', $id, $user_id);
$stmt->execute();
$res = $stmt->get_result();
$order = $res->fetch_assoc();
$stmt->close();

if (!$order) {
    echo "Pesanan tidak ditemukan atau bukan milik Anda.";
    exit;
}

// Update pesanan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_pelanggan'];
    $jenis = $_POST['jenis_kopi'];
    $ukuran = $_POST['ukuran'];
    $jumlah = $_POST['jumlah'];
    $catatan = $_POST['catatan'];

    $stmt = $mysqli->prepare("UPDATE orders SET nama_pelanggan=?, jenis_kopi=?, ukuran=?, jumlah=?, catatan=? WHERE id=? AND user_id=?");
    $stmt->bind_param('sssdisi', $nama, $jenis, $ukuran, $jumlah, $catatan, $id, $user_id);
    $stmt->execute();
    $stmt->close();

    header('Location: dashboard_user.php');
    exit;
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Edit Pesanan</title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #f6efe6;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    form {
      background: #fff;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
      width: 400px;
    }
    h2 {
      text-align: center;
      color: #4b2e05;
      margin-bottom: 20px;
    }
    label {
      display: block;
      margin-top: 10px;
      font-weight: 600;
    }
    input, select, textarea {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 6px;
      margin-top: 5px;
    }
    button {
      margin-top: 20px;
      width: 100%;
      padding: 10px;
      background: #8b5e34;
      color: #fff;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: 0.3s;
    }
    button:hover {
      background: #704727;
    }
    a {
      display: block;
      text-align: center;
      margin-top: 10px;
      color: #704727;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <nav class="navbar">
    <div class="logo">☕ Nawasena Coffee</div>
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

  <form method="post">
    <h2>Edit Pesanan</h2>
    <label>Nama Pelanggan</label>
    <input type="text" name="nama_pelanggan" value="<?= htmlspecialchars($order['nama_pelanggan']) ?>" required>

    <label>Jenis Kopi</label>
    <input type="text" name="jenis_kopi" value="<?= htmlspecialchars($order['jenis_kopi']) ?>" required>

    <label>Ukuran</label>
    <select name="ukuran" required>
      <option value="Small" <?= $order['ukuran']=='Small'?'selected':'' ?>>Small</option>
      <option value="Medium" <?= $order['ukuran']=='Medium'?'selected':'' ?>>Medium</option>
      <option value="Large" <?= $order['ukuran']=='Large'?'selected':'' ?>>Large</option>
    </select>

    <label>Jumlah</label>
    <input type="number" name="jumlah" min="1" value="<?= htmlspecialchars($order['jumlah']) ?>" required>

    <label>Catatan</label>
    <textarea name="catatan"><?= htmlspecialchars($order['catatan']) ?></textarea>

    <button type="submit">Simpan Perubahan</button>
    <a href="dashboard_user.php">Kembali</a>
  </form>
</body>
</html>
