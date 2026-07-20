<?php
/**
 * Espace personnel — annulation d'une réservation boutique
 * par son propriétaire (le stock est restitué).
 * POST : id_reservation
 */
session_start();
require_once(__DIR__ . '/connection.php');
require_once(__DIR__ . '/../classes/reservation_produit.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../connexion.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../mon-compte.php");
    exit();
}

$id_reservation = (int)($_POST['id_reservation'] ?? 0);

$reservationManager = new ReservationProduitManager($connection);
$reservationManager->annulerParUtilisateur($id_reservation, $_SESSION['user_id']);

header("Location: ../mon-compte.php");
exit();
