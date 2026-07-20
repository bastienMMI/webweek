<?php
/**
 * API interne — réservation gratuite d'un produit de la boutique.
 * POST api/reserver_produit.php  (id_produit, quantite)
 * Réponse : { "succes": true, "message": "...", "stock_restant": n }
 */
header('Content-Type: application/json; charset=utf-8');

require_once(__DIR__ . '/../scripts/connection.php');
require_once(__DIR__ . '/../classes/produit.php');
require_once(__DIR__ . '/../classes/reservation_produit.php');

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
        'erreur'   => "Veuillez vous connecter pour réserver un article.",
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

$id_produit = (int)($_POST['id_produit'] ?? 0);
$quantite   = (int)($_POST['quantite'] ?? 1);

if ($id_produit <= 0) {
    http_response_code(400);
    echo json_encode(['succes' => false, 'erreur' => "Article non précisé."], JSON_UNESCAPED_UNICODE);
    exit();
}

try {
    $reservationManager = new ReservationProduitManager($connection);
    $resultat = $reservationManager->creer($_SESSION['user_id'], $id_produit, $quantite);

    if ($resultat === 'introuvable') {
        http_response_code(404);
        echo json_encode(['succes' => false, 'erreur' => "Cet article n'existe plus."], JSON_UNESCAPED_UNICODE);
        exit();
    }
    if ($resultat === 'stock') {
        http_response_code(409);
        echo json_encode([
            'succes' => false,
            'erreur' => "Le stock est insuffisant pour cette quantité.",
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    $produitManager = new ProduitManager($connection);
    $produit = $produitManager->getById($id_produit);

    echo json_encode([
        'succes'        => true,
        'message'       => "Votre réservation est enregistrée. L'article vous attend au refuge — le paiement se fait sur place.",
        'stock_restant' => $produit ? (int)$produit['stock'] : 0,
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['succes' => false, 'erreur' => "La réservation a échoué. Merci de réessayer."], JSON_UNESCAPED_UNICODE);
}
