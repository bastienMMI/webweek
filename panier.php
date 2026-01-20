<?php
session_start();
include('config/configuration.php');
include('scripts/connection.php');

// Supprimer un article
if (isset($_GET['remove'])) {
    unset($_SESSION['panier'][$_GET['remove']]);
    header('Location: panier.php');
}
?>
<!doctype html>
<html lang="fr">
<?php include('header et footer/head.php'); ?>
<body>
    <?php include('header et footer/header.php'); ?>
    <main class="container">
        <h1>Votre Panier</h1>
        <?php if (empty($_SESSION['panier'])): ?>
            <p>Votre panier est vide.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Prix</th>
                        <th>Quantité</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total_general = 0;
                    foreach ($_SESSION['panier'] as $id => $quantite): 
                        // Récupérer les détails en BDD pour chaque ID
                        $stmt = $connection->prepare("SELECT * FROM produit WHERE id_produit = ?");
                        $stmt->execute([$id]);
                        $p = $stmt->fetch();
                        $sous_total = $p['prix'] * $quantite;
                        $total_general += $sous_total;
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($p['nom_produit']) ?></td>
                        <td><?= number_format($p['prix'], 2) ?> €</td>
                        <td><?= $quantite ?></td>
                        <td><?= number_format($sous_total, 2) ?> €</td>
                        <td><a href="panier.php?remove=<?= $id ?>">Supprimer</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <h3>Total à régler : <?= number_format($total_general, 2) ?> €</h3>
            <button class="valider-btn">Passer la commande</button>
        <?php endif; ?>
    </main>
</body>
</html>