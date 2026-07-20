<?php
/**
 * API interne — pré-réservation gratuite d'un animal.
 * POST api/reserver.php  (id_animal, message)
 * Réponse : { "succes": true, "message": "..." }
 */
header('Content-Type: application/json; charset=utf-8');

require_once(__DIR__ . '/../scripts/connection.php');
require_once(__DIR__ . '/../classes/reservation.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['succes' => false, 'erreur' => "Méthode non autorisée."]);
    exit();
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        'succes'   => false,
        'connecte' => false,
        'erreur'   => "Veuillez vous connecter pour pré-réserver un animal.",
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

$id_animal = (int)($_POST['id_animal'] ?? 0);
$message   = trim($_POST['message'] ?? '');

if ($id_animal <= 0) {
    http_response_code(400);
    echo json_encode(['succes' => false, 'erreur' => "Animal non précisé."], JSON_UNESCAPED_UNICODE);
    exit();
}

try {
    $reservationManager = new ReservationManager($connection);

    if ($reservationManager->existeDemandeEnCours($_SESSION['user_id'], $id_animal)) {
        http_response_code(409);
        echo json_encode([
            'succes' => false,
            'erreur' => "Vous avez déjà une demande en cours pour cet animal.",
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    $id = $reservationManager->creer(
        $_SESSION['user_id'],
        $id_animal,
        $message !== '' ? $message : null
    );

    if ($id === false) {
        http_response_code(409);
        echo json_encode([
            'succes' => false,
            'erreur' => "Cet animal n'est plus disponible.",
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    echo json_encode([
        'succes'  => true,
        'message' => "Votre demande de pré-réservation a bien été envoyée. Le refuge vous recontactera.",
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['succes' => false, 'erreur' => "La demande a échoué. Merci de réessayer."], JSON_UNESCAPED_UNICODE);
}
