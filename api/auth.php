<?php

ini_set('session.save_handler', 'files');
ini_set('session.save_path', '/tmp');
session_start();

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($email === 'admin@elite.com' && $password === 'admin') {
        $_SESSION['user'] = [
            'name' => 'Admin Elite',
            'email' => $email,
            'role' => 'Manager'
        ];
        header('Location: dashboard.php');
        exit;
    } else {
        header('Location: login.php?error=1');
        exit;
    }
}
?>