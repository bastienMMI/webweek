<?php
// Une session peut déjà avoir été ouverte par la page appelante :
// on ne la redémarre pas (cela déclencherait un avertissement PHP).
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Titre et description propres à chaque page (référencement).
// Chaque page peut définir $page_titre / $page_description avant cet include.
$titre = $page_titre ?? "SPA de la Haute-Loire — Refuge et adoption d'animaux au Puy-en-Velay";
$description = $page_description ?? "Refuge de la SPA de la Haute-Loire à Polignac : découvrez les chiens et chats à l'adoption, faites un don ou devenez bénévole.";
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($titre) ?></title>
    <meta name="description" content="<?= htmlspecialchars($description) ?>">

    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" defer></script>
    <script src="js/script.js" defer></script>
    <script src="js/index.js" defer></script>
    <script src="js/aider.js" defer></script>
    <script src="js/carte.js" defer></script>

    <link rel="icon" href="images/Favicon.png" type="image/png">
</head>
