<?php
// pages/menu.php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Menu Kopi - NSB Coffee</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
* { box-sizing: border-box; margin:0; padding:0; }
body {
    font-family: 'Poppins', sans-serif;
    background: #f5f5f5;
    min-height: 100vh;
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
.navbar .logo { font-size: 24px; font-weight: 700; font-style: italic; }
.navbar ul { list-style: none; display: flex; gap: 25px; }
.navbar ul li { position: relative; }
.navbar ul li a { text-decoration: none; color: #fff; font-weight: 500; transition: 0.3s; }
.navbar ul li a:hover { color: #c9a66b; }

/* Dropdown */
.dropdown-content {
    display: none;
    position: absolute;
    background: #fff;
    min-width: 140px;
    right: 0;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    overflow: hidden;
    z-index: 1;
}
.dropdown-content a { display: block; padding: 10px 15px; color: #3e2c20; text-decoration: none; }
.dropdown-content a:hover { background: #f3f0ed; }
.dropdown:hover .dropdown-content { display: block; }

/* Container */
.container {
    width: 90%;
    max-width: 1000px;
    margin: 40px auto;
    background: #fff;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

h2 { text-align: center; color: #4b2e05; margin-bottom: 30px; }

/* Menu Grid */
.menu-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
}

.menu-item {
    background: #fffaf3;
    border-radius: 12px;
    text-align: center;
    padding: 20px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    transition: 0.3s;
}
.menu-item:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.15); }

.menu-item img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 50%;
    margin-bottom: 10px;
}

.menu-item h4 { margin-top: 10px; color: #7b3f00; }
.menu-item p { color: #555; font-size: 14px; margin: 10px 0; }

.menu-item a {
    display: inline-block;
    margin-top: 10px;
    background: #7b3f00;
    color: white;
    padding: 8px 14px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 14px;
    transition: 0.3s;
}
.menu-item a:hover { background: #532800; }

@media(max-width: 500px){
    .navbar { flex-direction: column; gap: 10px; text-align: center; }
    .navbar ul { flex-direction: column; gap: 10px; }
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
    <h2>☕ Menu Kopi Kami</h2>
    <div class="menu-grid">
        <div class="menu-item">
            <h4>Espresso</h4>
            <p>Rasa kopi kuat dengan crema lembut di atasnya.</p>
            <a href="order_form.php?jenis=Espresso">Pesan Sekarang</a>
        </div>
        <div class="menu-item">
            <h4>Americano</h4>
            <p>Kopi hitam klasik dengan tambahan air panas.</p>
            <a href="order_form.php?jenis=Americano">Pesan Sekarang</a>
        </div>
        <div class="menu-item">
            <h4>Cappuccino</h4>
            <p>Campuran espresso, susu panas, dan buih lembut.</p>
            <a href="order_form.php?jenis=Cappuccino">Pesan Sekarang</a>
        </div>
        <div class="menu-item">
            <h4>Latte</h4>
            <p>Espresso dengan susu steamed dan lapisan busa tipis.</p>
            <a href="order_form.php?jenis=Latte">Pesan Sekarang</a>
        </div>
        <div class="menu-item">
            <h4>Mocha</h4>
            <p>Perpaduan kopi, cokelat, dan susu yang manis.</p>
            <a href="order_form.php?jenis=Mocha">Pesan Sekarang</a>
        </div>
    </div>
</div>

</body>
</html>
