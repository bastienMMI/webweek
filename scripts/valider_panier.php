<?php
session_start();
include('../config/configuration.php');
include('../scripts/connection.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../connexion.php?error=not_connected");
    exit();
}

if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
    header("Location: ../Boutique.php");
    exit();
}

try {
    $connection->beginTransaction();

    $montant_total = 0;
    $details_panier = [];

    foreach ($_SESSION['panier'] as $id_produit => $quantite) {
        $stmt_prix = $connection->prepare("SELECT prix FROM produit WHERE id_produit = ?");
        $stmt_prix->execute([$id_produit]);
        $produit = $stmt_prix->fetch();
        
        if ($produit) {
            $prix_actuel = $produit['prix'];
            $montant_total += ($prix_actuel * $quantite);
            $details_panier[] = [
                'id' => $id_produit,
                'qte' => $quantite,
                'prix' => $prix_actuel
            ];
        }
    }

    // Insertion de la commande dans la bdd
    $stmt = $connection->prepare("INSERT INTO commande (id_utilisateur, date_commande, statut, montant_total) VALUES (?, NOW(), 'en_attente', ?)");
    $stmt->execute([$_SESSION['user_id'], $montant_total]);
    $id_commande = $connection->lastInsertId();

    foreach ($details_panier as $item) {
        $stmt_item = $connection->prepare("INSERT INTO commande_produit (id_commande, id_produit, quantite, prix_unitaire) VALUES (?, ?, ?, ?)");
        $stmt_item->execute([
            $id_commande, 
            $item['id'], 
            $item['qte'], 
            $item['prix']
        ]);
    }

    $connection->commit();
    unset($_SESSION['panier']);

    header("Location: ../mon-compte.php?success=1");
    exit();

} catch (Exception $e) {
    $connection->rollBack();
    die("Erreur lors de la validation : " . $e->getMessage());
}