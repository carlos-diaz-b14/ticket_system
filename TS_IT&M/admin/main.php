<?php

include_once '../config.php';
include_once '../functions.php';

$pdo = pdo_connect_mysql();

if (!isset($_SESSION['account_loggedin']) || $_SESSION['account_role'] != 'Admin') {
    header('Location: ../index.php');
    exit;
}
?>
