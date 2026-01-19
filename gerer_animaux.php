<?php
// racine/modifier_animal.php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { 
    header("Location: connexion.html"); 
    exit(); 
}

include('config/configuration.php');
include('scripts/connection.php');

// Vérification de la présence de l'ID
if (!isset($_GET['id'])) {
    header("Location: admin.php");
    exit();
}

$id = $_GET['id'];
$stmt = $connection->prepare("SELECT * FROM animal WHERE id_animal = ?");
$stmt->execute([$id]);
$animal = $stmt->fetch();

// Si l'animal n'existe pas en BDD
if (!$animal) {
    die("Animal introuvable.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<?php include('header_footer/head.php'); ?>
<body>
<?php include('header_footer/header.php'); ?>

<main class="form-container">
    <h1>Modifier la fiche de : <?= htmlspecialchars($animal['nom']) ?></h1>
    
    <form action="scripts/update_animal.php" method="POST" class="admin-form">
        <input type="hidden" name="id" value="<?= $animal['id_animal'] ?>">
        
        <div class="input-group">
            <label for="nom">Nom de l'animal</label>
            <input type="text" name="nom" id="nom" value="<?= htmlspecialchars($animal['nom']) ?>" required>
        </div>

        <div class="input-group">
            <label for="statut">Statut actuel</label>
            <select name="statut" id="statut">
                <option value="disponible" <?= $animal['statut'] == 'disponible' ? 'selected' : '' ?>>Disponible</option>
                <option value="indisponible" <?= $animal['statut'] == 'indisponible' ? 'selected' : '' ?>>Indisponible (adopté/soins)</option>
            </select>
        </div>

        <div class="button-group">
            <button type="submit" class="btn-submit">Enregistrer les modifications</button>
            <a href="admin.php" class="btn-cancel">Annuler</a>
        </div>
    </form>
</main>

<?php include('header_footer/footer.php'); ?>
</body>
</html>