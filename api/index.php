<?php

ini_set('session.save_handler', 'files');
ini_set('session.save_path', '/tmp');
session_start();

session_start();

if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
} else {
    header('Location: login.php');
}
exit;
?>