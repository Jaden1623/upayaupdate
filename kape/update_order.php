<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order'])) {
    $_SESSION['order'] = json_decode($_POST['order'], true);
    echo 'success';
    exit;
}
?>
