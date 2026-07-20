<?php
session_start();
include('config/configuration.php');
include('scripts/connection.php');
include('classes/produit.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: connexion.php");
    exit();
}

$id_produit = (int)($_GET['id'] ?? 0);
$produitManager = new ProduitManager($connection);
$produit = $produitManager->getById($id_produit);

if (!$produit) {
    header("Location: admin.php");
    exit();
}

$erreur = $_GET['erreur'] ?? null;

$page_titre = "Modifier « " . $produit['nom_produit'] . " » — Administration SPA Haute-Loire";
$page_description = "Modification d'un article de la boutique solidaire du refuge.";
?>
<!DOCTYPE html>
<html lang="fr">
<?php include('header et footer/head.php'); ?>
<body>
<?php include('header et footer/header.php'); ?>

<main class="admin-main">
    <div class="admin-edit-container">
        <div class="title-section">
            <h1>Modifier « <?= htmlspecialchars($produit['nom_produit']) ?> »</h1>
            <p class="admin-subtitle">Laissez le champ photo vide pour conserver l'image actuelle.</p>
        </div>

        <?php if ($erreur === 'champs'): ?>
            <p class="message-erreur" role="alert">Merci de renseigner au moins un nom et un prix valide.</p>
        <?php endif; ?>

        <form action="scripts/update_produit.php" method="POST" enctype="multipart/form-data" class="form-section">
            <input type="hidden" name="id_produit" value="<?= (int)$produit['id_produit'] ?>">

            <div class="edit-grid">
                <div class="edit-group">
                    <label for="nom">Nom du produit</label>
                    <input type="text" id="nom" name="nom"
                           value="<?= htmlspecialchars($produit['nom_produit']) ?>" required>
                </div>

                <div class="edit-group">
                    <label for="prix">Prix indicatif (€) — payé au refuge</label>
                    <input type="number" id="prix" name="prix" step="0.01" min="0.01"
                           value="<?= htmlspecialchars($produit['prix']) ?>" required>
                </div>

                <div class="edit-group">
                    <label for="stock">Stock</label>
                    <input type="number" id="stock" name="stock" min="0"
                           value="<?= (int)$produit['stock'] ?>">
                </div>

                <div class="edit-group">
                    <label for="photo">Nouvelle photo (facultatif)</label>
                    <input type="file" id="photo" name="photo" accept=".jpg,.jpeg,.png,.webp">
                    <?php if (!empty($produit['photo'])): ?>
                        <img src="images/<?= htmlspecialchars($produit['photo']) ?>"
                             alt="Photo actuelle : <?= htmlspecialchars($produit['nom_produit']) ?>"
                             class="apercu-photo" loading="lazy">
                    <?php endif; ?>
                </div>

                <div class="edit-group full-width">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="3"><?= htmlspecialchars($produit['description']) ?></textarea>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">Enregistrer les modifications</button>
                <a href="admin.php" class="lien-retour">Annuler</a>
            </div>
        </form>
    </div>
</main>

<?php include('header et footer/footer.php'); ?>
</body>
</html>
