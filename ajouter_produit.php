<?php 
session_start();
include('config/configuration.php');
include('scripts/connection.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location:connexion.php");
    exit();
}

include('header et footer/head.php'); 
?>
<body>
<?php include('header et footer/header.php'); ?>

<main class="section-contact">
    <div class="admin-edit-container">
        <h1 class="contact-main-title">Ajouter un Produit</h1>
        <p>Créez un nouvel article pour la boutique de la SPA.</p>

        <form action="scripts/ajout_produit.php" method="POST" enctype="multipart/form-data">
            <div class="edit-grid">
                <div class="edit-group">
                    <label>Nom du produit</label>
                    <input type="text" name="nom" required>
                </div>

                <div class="edit-group">
                    <label>Prix (€)</label>
                    <input type="number" step="0.01" name="prix" required>
                </div>

                <div class="edit-group">
                    <label>Stock initial</label>
                    <input type="number" name="stock" value="0">
                </div>

                <div class="edit-group">
                    <label>Image du produit</label>
                    <input type="file" name="photo" accept="image/*" required>
                </div>

                <div class="edit-group full-width">
                    <label>Description technique</label>
                    <textarea name="description" rows="3"></textarea>
                </div>
            </div>

            <div style="margin-top: 30px; display: flex; gap: 20px;">
                <button type="submit" class="login-button" style="border:none; width: 200px;">Enregistrer le produit</button>
                <a href="admin.php" style="padding-top:10px; color: var(--brown-text);">Annuler</a>
            </div>
        </form>
    </div>
</main>
<?php include('header et footer/footer.php'); ?>
</body>