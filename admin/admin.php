<?php
session_start();
include('../config/configuration.php');
include('../scripts/connection.php');

// Sécurité : Seul l'admin accède à cette page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../connexion.php");
    exit();
}

// Récupération de tous les animaux
$animaux = $connection->query("SELECT * FROM animal ORDER BY id_animal DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPA Haute-Loire</title>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css"> 
    <script src="../js/script.js"></script>
</head>
<body>    
        <header>
        <input type="checkbox" id="menu-toggle" class="menu-checkbox">

        <label for="menu-toggle" class="burger-menu">
            <span></span><span></span><span></span>
        </label>

        <div class="logo">
            <a href="index.php"><img src="../images/Logo-1.webp" alt="Logo SPA Haute-Loire"></a>
        </div>

        <nav>
            <ul>
                <li><a href="../adopter.php">Adopter</a></li>
                <li><a href="../aider.php">Nous aider</a></li>
                <li><a href="../index.php#contact">Contact</a></li>
                <li><a href="../boutique.php">Boutique</a></li>
            </ul>
        </nav>

        <div class="user-access">
            <a href="../connexion.php">
                <img src="../images/connexion.webp" alt="Icône utilisateur">
            </a>
        </div>
    </header>

    <main class="admin-container">
        <div class="admin-header">
            <h1>Gestion du Refuge</h1>
            <a href="ajouter_animal.php" class="btn-add">+ Ajouter un Animal</a>
            <a href="../index.php" class="btn-back">Retour au site</a>
        </div>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Nom</th>
                    <th>Espèce</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($animaux as $a): ?>
                <tr>
                    <td><img src="../images/animaux/<?= $a['photo'] ?>" style="width:50px; height:50px; object-fit:cover; border-radius:5px;"></td>
                    <td><?= htmlspecialchars($a['nom']) ?></td>
                    <td><?= $a['espece'] ?></td>
                    <td><span class="badge"><?= $a['statut'] ?></span></td>
                    <td>
                        <a href="gerer_animaux.php?id=<?= $a['id_animal'] ?>" class="btn-edit">Modifier</a>
                        <a href="scripts/suppression_animal.php?id=<?= $a['id_animal'] ?>" 
   onclick="return confirm('Es-tu sûr ?')">
   Supprimer
</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
    <footer>
        <div class="footer-container">
            <div class="footer-left">
                <a href="https://www.facebook.com/people/SPA-de-la-Haute-Loire/100064272296018/" target="_blank"
                    class="facebook-link">Facebook</a>
            </div>

            <div class="footer-right">
                <a href="mentions-legales.html" class="legal-link">Mentions Légales</a>
            </div>
        </div>
    </footer>
</body>
</html>