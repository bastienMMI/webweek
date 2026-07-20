<?php
session_start();
require_once(__DIR__ . '/connection.php');
require_once(__DIR__ . '/../classes/reservation.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../connexion.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../admin.php");
    exit();
}

$id     = (int)($_POST['id_reservation'] ?? 0);
$statut = $_POST['statut'] ?? '';

$reservationManager = new ReservationManager($connection);
$reservationManager->changerStatut($id, $statut);

header("Location: ../admin.php?msg=resa_traitee");
exit();
