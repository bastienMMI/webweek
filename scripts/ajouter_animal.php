<?php
session_start();
require_once(__DIR__ . '/connection.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST'
    || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../connexion.php");
    exit();
}

// Valeurs autorisées, alignées sur les ENUM de la base
$especes_valides = ['chien', 'chat', 'nac', 'autre'];
$sexes_valides   = ['masculin', 'feminin'];

$nom    = trim($_POST['nom'] ?? '');
$espece = strtolower(trim($_POST['espece'] ?? ''));
$sexe   = $_POST['sexe'] ?? '';
$description = trim($_POST['description'] ?? '');

$date_naissance = $_POST['date_naissance'] ?? '';
if ($date_naissance === '' || !strtotime($date_naissance) || strtotime($date_naissance) > time()) {
    $date_naissance = null;
}

$vaccine   = isset($_POST['vaccine'])   ? 1 : 0;
$sterilise = isset($_POST['sterilise']) ? 1 : 0;
$identifie = isset($_POST['identifie']) ? 1 : 0;

$statut = 'disponible';
$date_arrivee = date('Y-m-d');

if ($nom === '' || !in_array($espece, $especes_valides, true) || !in_array($sexe, $sexes_valides, true)) {
    header("Location: ../ajouter_animal.php?erreur=donnees");
    exit();
}

// Contrôle du fichier envoyé : type réel et taille
if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
    header("Location: ../ajouter_animal.php?erreur=photo");
    exit();
}

$types_autorises = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
$type_reel = mime_content_type($_FILES['photo']['tmp_name']);

if (!isset($types_autorises[$type_reel]) || $_FILES['photo']['size'] > 3 * 1024 * 1024) {
    header("Location: ../ajouter_animal.php?erreur=photo_type");
    exit();
}

$dossier_images = dirname(__DIR__) . "/images/animaux/";
$nom_image = uniqid('animal_', true) . '.' . $types_autorises[$type_reel];

if (!move_uploaded_file($_FILES['photo']['tmp_name'], $dossier_images . $nom_image)) {
    header("Location: ../ajouter_animal.php?erreur=upload");
    exit();
}

$sql = "INSERT INTO animal
        (nom, espece, sexe, date_naissance, description, vaccine, sterilise, identifie, photo, statut, date_arrivee)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

try {
    $stmt = $connection->prepare($sql);
    $stmt->execute([
        $nom, $espece, $sexe, $date_naissance, $description,
        $vaccine, $sterilise, $identifie, $nom_image, $statut, $date_arrivee
    ]);
    header("Location: ../admin.php?msg=animal_ajoute");
    exit();

} catch (PDOException $e) {
    // On ne laisse pas de photo orpheline si l'insertion échoue
    @unlink($dossier_images . $nom_image);
    header("Location: ../ajouter_animal.php?erreur=sql");
    exit();
}
