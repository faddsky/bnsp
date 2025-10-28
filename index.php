<?php
session_start();
if (isset($_SESSION['user'])) {
    // arahkan berdasarkan role
    if ($_SESSION['user']['role'] === 'admin') header('Location: pages/dashboard_admin.php');
    else header('Location: pages/dashboard_user.php');
    exit;
}
header('Location: pages/login.php');
exit;
