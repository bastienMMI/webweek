<?php
session_start();
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST'
    || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../connexion.php");
    exit();
}

// Valeurs autorisées, alignées sur les ENUM de la base
$especes_valides = ['chien', 'chat', 'nac', 'autre'];
$statuts_valides = ['disponible', 'reserve', 'adopte'];
$sexes_valides   = ['masculin', 'feminin'];

$id     = (int)($_POST['id'] ?? 0);
$nom    = trim($_POST['nom'] ?? '');
$espece = strtolower(trim($_POST['espece'] ?? ''));
$statut = $_POST['statut'] ?? '';
$sexe   = $_POST['sexe'] ?? '';
$description = trim($_POST['description'] ?? '');

// Date de naissance : facultative, doit être valide et non future
$date_naissance = $_POST['date_naissance'] ?? '';
if ($date_naissance === '' || !strtotime($date_naissance) || strtotime($date_naissance) > time()) {
    $date_naissance = null;
}

// Cases à cocher : présentes = 1, absentes = 0
$vaccine   = isset($_POST['vaccine'])   ? 1 : 0;
$sterilise = isset($_POST['sterilise']) ? 1 : 0;
$identifie = isset($_POST['identifie']) ? 1 : 0;

if ($id <= 0 || $nom === ''
    || !in_array($espece, $especes_valides, true)
    || !in_array($statut, $statuts_valides, true)
    || !in_array($sexe, $sexes_valides, true)) {
    header("Location: ../modifier_animaux.php?id=$id&erreur=donnees");
    exit();
}

$sql = "UPDATE animal
        SET nom = ?, espece = ?, sexe = ?, date_naissance = ?, description = ?,
            vaccine = ?, sterilise = ?, identifie = ?, statut = ?
        WHERE id_animal = ?";

$stmt = $connection->prepare($sql);
$stmt->execute([
    $nom, $espece, $sexe, $date_naissance, $description,
    $vaccine, $sterilise, $identifie, $statut, $id
]);

header("Location: ../admin.php?msg=modifie");
exit();
