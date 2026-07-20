<?php
session_start();
include('config/configuration.php');
include('scripts/connection.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: connexion.php");
    exit();
}

$erreur = $_GET['erreur'] ?? null;

$page_titre = "Ajouter un produit — Administration SPA Haute-Loire";
$page_description = "Ajout d'un article à la boutique solidaire du refuge.";
?>
<!DOCTYPE html>
<html lang="fr">
<?php include('header et footer/head.php'); ?>
<body>
<?php include('header et footer/header.php'); ?>

<main class="admin-main">
    <div class="admin-edit-container">
        <div class="title-section">
            <h1>Ajouter un produit</h1>
            <p class="admin-subtitle">L'article sera proposé en réservation gratuite, retrait au refuge.</p>
        </div>

        <?php if ($erreur === 'champs'): ?>
            <p class="message-erreur" role="alert">Merci de renseigner au moins un nom et un prix valide.</p>
        <?php endif; ?>

        <form action="scripts/ajout_produit.php" method="POST" enctype="multipart/form-data" class="form-section">
            <div class="edit-grid">
                <div class="edit-group">
                    <label for="nom">Nom du produit</label>
                    <input type="text" id="nom" name="nom" required>
                </div>

                <div class="edit-group">
                    <label for="prix">Prix indicatif (€) — payé au refuge</label>
                    <input type="number" id="prix" name="prix" step="0.01" min="0.01" required>
                </div>

                <div class="edit-group">
                    <label for="stock">Stock initial</label>
                    <input type="number" id="stock" name="stock" min="0" value="0">
                </div>

                <div class="edit-group">
                    <label for="photo">Photo (jpg, png ou webp)</label>
                    <input type="file" id="photo" name="photo" accept=".jpg,.jpeg,.png,.webp">
                </div>

                <div class="edit-group full-width">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="3"></textarea>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">Enregistrer le produit</button>
                <a href="admin.php" class="lien-retour">Annuler</a>
            </div>
        </form>
    </div>
</main>

<?php include('header et footer/footer.php'); ?>
</body>
</html>
