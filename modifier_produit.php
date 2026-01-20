<?php 
session_start();
include('config/configuration.php');
include('scripts/connection.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location:connexion.php");
    exit();
}

$id = $_GET['id'];
$stmt = $connection->prepare("SELECT * FROM produit WHERE id_produit = ?");
$stmt->execute([$id]);
$produit = $stmt->fetch();

include('header et footer/head.php'); 
?>
<body>
<?php include('header et footer/header.php'); ?>

<main class="section-contact">
<div class="admin-edit-container">
    <form action="scripts/update_produit.php" method="POST">
        <input type="hidden" name="id" value="<?= $produit['id_produit'] ?>">
        
        <div class="edit-grid">
            <div class="edit-group">
                <label>Nom du produit</label>
                <input type="text" name="nom" value="<?= htmlspecialchars($produit['nom_produit']) ?>" required>
            </div>

            <div class="edit-group">
                <label>Prix (€)</label>
                <input type="number" step="0.01" name="prix" value="<?= $produit['prix'] ?>" required>
            </div>

            <div class="edit-group full-width">
                <label>Description technique</label>
                <textarea name="description" rows="3"><?= htmlspecialchars($produit['description']) ?></textarea>
            </div>
        </div>

        <div style="margin-top: 30px; display: flex; gap: 20px;">
            <button type="submit" class="login-button" style="border:none; width: 200px;">Mettre à jour</button>
            <a href="admin.php" style="padding-top:10px; color: var(--brown-text);">Annuler</a>
        </div>
    </form>
</div>
</main>

<?php include('header et footer/footer.php'); ?>
</body>