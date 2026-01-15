<?php

if (isset($_COOKIE['auth_token'])) {
    header('Location: dashboard.php');
} else {
    header('Location: login.php');
}
exit;
?>