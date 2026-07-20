<?php
session_start();
require_once(__DIR__ . '/connection.php');
require_once(__DIR__ . '/../classes/contact.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../connexion.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../admin.php");
    exit();
}

$messageManager = new MessageContactManager($connection);
$messageManager->marquerTraite(
    (int)($_POST['id_message'] ?? 0),
    ($_POST['traite'] ?? '0') === '1'
);

header("Location: ../admin.php?msg=msg_traite");
exit();
