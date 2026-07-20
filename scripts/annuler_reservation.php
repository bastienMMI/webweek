<?php
session_start();
include(__DIR__ . '/connection.php');
include(__DIR__ . '/../classes/reservation.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../connexion.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id_reservation'])) {
    header("Location: ../mon-compte.php");
    exit();
}

$reservationManager = new ReservationManager($connection);
$ok = $reservationManager->annulerParUtilisateur(
    (int)$_POST['id_reservation'],
    (int)$_SESSION['user_id']
);

header("Location: ../mon-compte.php?annulation=" . ($ok ? 'ok' : 'erreur'));
exit();
