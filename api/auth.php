<?php

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    setcookie('auth_token', '', time() - 3600, '/');
    setcookie('user_name', '', time() - 3600, '/');
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($email === 'admin@elite.com' && $password === 'admin') {
        setcookie('auth_token', md5($email), time() + 7200, '/');
        setcookie('user_name', 'Admin Elite', time() + 7200, '/');
        
        header('Location: dashboard.php');
        exit;
    } else {
        header('Location: login.php?error=1');
        exit;
    }
}
?>