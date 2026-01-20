<?php
session_start();
include('config/configuration.php');
include('scripts/connection.php');
include('classes/produit.php');

$produitManager = new ProduitManager($connection);

// Logique d'ajout au panier
if (isset($_POST['add_to_cart'])) {
    $id_p = $_POST['product_id'];
    if (!isset($_SESSION['panier'])) { $_SESSION['panier'] = []; }
    
    if (isset($_SESSION['panier'][$id_p])) {
        $_SESSION['panier'][$id_p]++;
    } else {
        $_SESSION['panier'][$id_p] = 1;
    }
    header("Location: Boutique.php");
    exit();
}

$produits = $produitManager->getAllProduits();
?>
<!doctype html>
<html lang="fr">
<?php include('header et footer/head.php'); ?>
<body>
    <?php include('header et footer/header.php'); ?>

    <main>
      <section class="product-gallery">
        <div class="boutique-top">
                <h1>Notre Boutique</h1>
                <a href="panier.php" class="btn-panier">
                    🛒 Panier (<?= isset($_SESSION['panier']) ? array_sum($_SESSION['panier']) : 0 ?>)
                </a>
            </div>

            <div class="gallery">
                <?php foreach ($produits as $p): ?>
                <article class="product-card">
                    <img src="images/<?= htmlspecialchars($p['photo']) ?>" alt="<?= htmlspecialchars($p['nom_produit']) ?>" loading="lazy" />
                    <div class="info">
                        <h3><?= htmlspecialchars($p['nom_produit']) ?></h3>
                        <div class="price"><?= number_format($p['prix'], 2) ?> €</div>
                        <p class="desc"><?= htmlspecialchars($p['description']) ?></p>
                        
                        <?php if($p['stock'] > 0): ?>
                            <form method="POST">
                                <input type="hidden" name="product_id" value="<?= $p['id_produit'] ?>">
                                <button type="submit" name="add_to_cart" class="btn-buy">Ajouter au panier</button>
                            </form>
                        <?php else: ?>
                            <span class="out-of-stock">Rupture de stock</span>
                        <?php endif; ?>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <?php include('header et footer/footer.php'); ?>
</body>
</html>