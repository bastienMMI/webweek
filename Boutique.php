<?php
session_start(); // Initialise la session pour stocker le panier
include('config/configuration.php');
include('scripts/connection.php');

// Logique d'ajout au panier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $id = $_POST['product_id'];
    
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }

    // Si le produit existe déjà, on augmente la quantité, sinon on l'ajoute
    if (isset($_SESSION['panier'][$id])) {
        $_SESSION['panier'][$id]++;
    } else {
        $_SESSION['panier'][$id] = 1;
    }
    
    // Optionnel : Rediriger pour éviter de renvoyer le formulaire en actualisant
    header("Location: Boutique.php");
    exit();
}

// Récupération des produits
$query = "SELECT * FROM produit ORDER BY id_produit DESC";
$results = $connection->query($query);
$produits = $results->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="fr">
<?php include('header et footer/head.php'); ?>
<body>
    <?php include('header et footer/header.php'); ?>

    <main>
      <section class="product-gallery">
            <div style="display: flex; justify-content: space-between; align-items: center;">
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