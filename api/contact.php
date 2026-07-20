<?php
/**
 * API interne — envoi d'un message depuis le formulaire de contact.
 * POST api/contact.php  (nom, email, telephone, objet, message)
 * Réponse : { "succes": true, "message": "..." }
 */
header('Content-Type: application/json; charset=utf-8');

require_once(__DIR__ . '/../scripts/connection.php');
require_once(__DIR__ . '/../classes/contact.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['succes' => false, 'erreur' => "Méthode non autorisée."]);
    exit();
}

$donnees = [
    'nom'       => $_POST['nom']       ?? '',
    'email'     => $_POST['email']     ?? '',
    'telephone' => $_POST['telephone'] ?? '',
    'objet'     => $_POST['objet']     ?? '',
    'message'   => $_POST['message']   ?? '',
];

try {
    $messageManager = new MessageContactManager($connection);
    $erreurs = $messageManager->valider($donnees);

    if (!empty($erreurs)) {
        http_response_code(422);
        echo json_encode(['succes' => false, 'erreurs' => $erreurs], JSON_UNESCAPED_UNICODE);
        exit();
    }

    $messageManager->enregistrer($donnees);

    echo json_encode([
        'succes'  => true,
        'message' => "Votre demande a bien été envoyée. Nous vous répondrons rapidement.",
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['succes' => false, 'erreur' => "L'envoi a échoué. Merci de réessayer."], JSON_UNESCAPED_UNICODE);
}
