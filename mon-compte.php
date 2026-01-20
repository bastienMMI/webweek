<?php
session_start();
include('config/configuration.php');
include('scripts/connection.php');

// 1. Sécurité : si l'utilisateur n'est pas connecté, on le renvoie à la page de connexion
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$id_user = $_SESSION['user_id'];

// 2. Récupération des infos de l'utilisateur
$stmt_user = $connection->prepare("SELECT nom, prenom, email, telephone FROM utilisateur WHERE id_utilisateur = ?");
$stmt_user->execute([$id_user]);
$user = $stmt_user->fetch(PDO::FETCH_ASSOC);

// 3. Récupération de l'historique des commandes (achats fictifs)
// On fait une jointure pour avoir le détail des produits dans chaque commande
$query_commandes = "
    SELECT c.id_commande, c.date_commande, c.statut, 
           p.nom_produit, cp.quantite, p.prix, (p.prix * cp.quantite) as total_ligne
    FROM commande c
    JOIN commande_produit cp ON c.id_commande = cp.id_commande
    JOIN produit p ON cp.id_produit = p.id_produit
    WHERE c.id_utilisateur = ?
    ORDER BY c.date_commande DESC
";
$stmt_orders = $connection->prepare($query_commandes);
$stmt_orders->execute([$id_user]);
$resultats = $stmt_orders->fetchAll(PDO::FETCH_ASSOC);

// On regroupe les résultats par ID de commande pour l'affichage
$commandes = [];
foreach ($resultats as $ligne) {
    $commandes[$ligne['id_commande']]['infos'] = [
        'date' => $ligne['date_commande'],
        'statut' => $ligne['statut']
    ];
    $commandes[$ligne['id_commande']]['produits'][] = $ligne;
}
?>

<!DOCTYPE html>
<html lang="fr">
<?php include('header et footer/head.php'); ?>
<body>
    <?php include('header et footer/header.php'); ?>

    <main class="container account-container">
        <div class="account-header">
            <h1>Mon Espace Client</h1>
            <p>Bienvenue, <strong><?= htmlspecialchars($user['prenom'] . " " . $user['nom']) ?></strong></p>
        </div>

        <section class="info-utilisateur">
            <h3>Mes Informations</h3>
            <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Téléphone :</strong> <?= htmlspecialchars($user['telephone'] ?? 'Non renseigné') ?></p>
        </section>

        <section class="order-history">
            <h3>Historique de mes réservations (achats fictifs)</h3>
            
            <?php if (empty($commandes)): ?>
                <p class="empty-msg">Vous n'avez pas encore effectué de commande sur notre boutique.</p>
                <a href="Boutique.php" class="btn-submit btn-shop">Aller à la boutique</a>
            <?php else: ?>
                <?php foreach ($commandes as $id_cmd => $cmd): ?>
                    <div class="card-commande">
                        <div class="card-header">
                            <span><strong>Commande #<?= $id_cmd ?></strong> du <?= date('d/m/Y', strtotime($cmd['infos']['date'])) ?></span>
                            <span class="status-badge">
                                <?= ucfirst(htmlspecialchars($cmd['infos']['statut'])) ?>
                            </span>
                        </div>
                        
                        <table class="order-table">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Quantité</th>
                                    <th>Prix Unitaire</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $total_commande = 0;
                                foreach ($cmd['produits'] as $prod): 
                                    $total_commande += $prod['total_ligne'];
                                ?>
                                    <tr>
                                        <td><?= htmlspecialchars($prod['nom_produit']) ?></td>
                                        <td>x<?= $prod['quantite'] ?></td>
                                        <td><?= number_format($prod['prix'], 2) ?> €</td>
                                        <td><?= number_format($prod['total_ligne'], 2) ?> €</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="order-total">
                            Total de la réservation : <?= number_format($total_commande, 2) ?> € (Fictif)
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>

    <?php include('header et footer/footer.php'); ?>
</body>
</html>