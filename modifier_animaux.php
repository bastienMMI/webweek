<?php 
session_start();
include('config/configuration.php');
include('scripts/connection.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location:connexion.php"); exit();
}

$id = $_GET['id'];
$stmt = $connection->prepare("SELECT * FROM animal WHERE id_animal = ?");
$stmt->execute([$id]);
$animal = $stmt->fetch();

include('header et footer/head.php'); 
?>
<body>
<?php include('header et footer/header.php'); ?>

<main class="section-contact">
    <div class="admin-edit-container">
        <h1 class="contact-main-title">Modifier Animal</h1>
        <p>Mise à jour de : <strong><?= htmlspecialchars($animal['nom']) ?></strong></p>

        <form action="scripts/update_animal.php" method="POST">
                <input type="hidden" name="id" value="<?= $animal['id_animal'] ?>">
            
                <div class="edit-grid">
                    <div class="edit-group">
                        <label>Nom de l'animal</label>
                        <input type="text" name="nom" value="<?= htmlspecialchars($animal['nom']) ?>" required>
                    </div>

                    <div class="edit-group">
                        <label>Statut de disponibilité</label>
                        <select name="statut">
                            <option value="disponible" <?= $animal['statut'] == 'disponible' ? 'selected' : '' ?>>Disponible</option>
                            <option value="indisponible" <?= $animal['statut'] == 'indisponible' ? 'selected' : '' ?>>Indisponible</option>
                        </select>
                    </div>

                    <div class="edit-group">
                        <label>Âge</label>
                        <input type="number" name="age" value="<?= $animal['age'] ?>">
                    </div>

                    <div class="edit-group">
                        <label>Sexe</label>
                        <select name="sexe">
                            <option value="masculin" <?= $animal['sexe'] == 'masculin' ? 'selected' : '' ?>>Masculin</option>
                            <option value="feminin" <?= $animal['sexe'] == 'feminin' ? 'selected' : '' ?>>Féminin</option>
                        </select>
                    </div>
                
                    <div class="edit-group full-width">
                        <label>Description / Histoire de l'animal</label>
                        <textarea name="description" rows="5"><?= htmlspecialchars($animal['description']) ?></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="login-button btn-save">Sauvegarder</button>
                    <a href="admin.php" class="btn-cancel">Annuler</a>
                </div>
        </form>
    </div>
</main>
<?php include('header et footer/footer.php'); ?>
</body>