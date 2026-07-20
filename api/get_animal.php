<?php
/**
 * API interne — détail d'un animal.
 * GET api/get_animal.php?id=12
 * Réponse : { "succes": true, "animal": { ... }, "peut_reserver": bool }
 */
header('Content-Type: application/json; charset=utf-8');

require_once(__DIR__ . '/../scripts/connection.php');
require_once(__DIR__ . '/../classes/animal.php');
require_once(__DIR__ . '/../classes/reservation.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['succes' => false, 'erreur' => "Identifiant manquant."]);
    exit();
}

try {
    $animalManager = new AnimalManager($connection);
    $animal = $animalManager->getById($id);

    if (!$animal) {
        http_response_code(404);
        echo json_encode(['succes' => false, 'erreur' => "Cet animal n'existe pas."]);
        exit();
    }

    // L'utilisateur peut-il réserver cet animal ?
    $connecte = isset($_SESSION['user_id']);
    $deja_demande = false;

    if ($connecte) {
        $reservationManager = new ReservationManager($connection);
        $deja_demande = $reservationManager->existeDemandeEnCours($_SESSION['user_id'], $id);
    }

    echo json_encode([
        'succes'        => true,
        'animal'        => $animal,
        'connecte'      => $connecte,
        'deja_demande'  => $deja_demande,
        'peut_reserver' => $connecte && !$deja_demande && $animal['statut'] === 'disponible',
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['succes' => false, 'erreur' => "Impossible de charger cet animal."]);
}
