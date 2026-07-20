<?php
/**
 * API interne — catalogue de la boutique.
 * GET api/get_produits.php
 * Réponse : { "succes": true, "produits": [ ... ] }
 */
header('Content-Type: application/json; charset=utf-8');

require_once(__DIR__ . '/../scripts/connection.php');
require_once(__DIR__ . '/../classes/produit.php');

try {
    $produitManager = new ProduitManager($connection);
    echo json_encode([
        'succes'   => true,
        'produits' => $produitManager->getActifs(),
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['succes' => false, 'erreur' => "Le catalogue est momentanément indisponible."], JSON_UNESCAPED_UNICODE);
}
