<?php
/**
 * API interne — liste des animaux, filtrable.
 * GET api/get_animaux.php?espece=chien&sexe=feminin&age_max=5&vaccine=1
 * Réponse : { "succes": true, "total": n, "animaux": [ ... ] }
 */
header('Content-Type: application/json; charset=utf-8');

require_once(__DIR__ . '/../scripts/connection.php');
require_once(__DIR__ . '/../classes/animal.php');

try {
    $animalManager = new AnimalManager($connection);

    $animaux = $animalManager->getFiltered([
        'espece'    => $_GET['espece']    ?? 'tous',
        'sexe'      => $_GET['sexe']      ?? 'tous',
        'age_max'   => $_GET['age_max']   ?? null,
        'vaccine'   => !empty($_GET['vaccine']),
        'sterilise' => !empty($_GET['sterilise']),
        'identifie' => !empty($_GET['identifie']),
    ]);

    echo json_encode([
        'succes'  => true,
        'total'   => count($animaux),
        'animaux' => $animaux,
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['succes' => false, 'erreur' => "Impossible de charger les animaux."]);
}
