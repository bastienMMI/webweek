<?php
/**
 * Back-office — changement de statut d'une réservation boutique.
 * POST : id_reservation, statut (en_attente | prete | retiree | annulee)
 */
session_start();
require_once(__DIR__ . '/connection.php');
require_once(__DIR__ . '/../classes/reservation_produit.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../connexion.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../admin.php");
    exit();
}

$id_reservation = (int)($_POST['id_reservation'] ?? 0);
$statut         = $_POST['statut'] ?? '';

$reservationManager = new ReservationProduitManager($connection);
$reservationManager->changerStatut($id_reservation, $statut);

header("Location: ../admin.php?msg=resa_boutique");
exit();
