<?php
include('config/configuration.php');
include('scripts/connection.php');

// 1. Récupération des filtres depuis l'URL (sécurisés)
$espece_filtre = isset($_GET['espece']) ? $_GET['espece'] : 'tous';
$tri = isset($_GET['tri']) ? $_GET['tri'] : 'recent';

// 2. Construction de la requête de base
$sql = "SELECT * FROM animal WHERE 1=1"; 
$params = [];

// Filtre par espèce
if ($espece_filtre !== 'tous') {
    $sql .= " AND espece = ?";
    $params[] = $espece_filtre;
}

// Gestion du tri
switch ($tri) {
    case 'age_asc':
        $sql .= " ORDER BY age ASC";
        break;
    case 'age_desc':
        $sql .= " ORDER BY age DESC";
        break;
    case 'ancien':
        $sql .= " ORDER BY date_arrivee ASC";
        break;
    default: // 'recent'
        $sql .= " ORDER BY date_arrivee DESC";
        break;
}

// 3. Exécution sécurisée
$stmt = $connection->prepare($sql);
$stmt->execute($params);
$animaux = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

    <?php 
  include('header et footer/head.php'); 
?>

<body>
    <?php 
     include('header et footer/header.php'); 
    ?>
    <main
        <section class="intro-section">
            <div class="dashed-box">
                <h1>Les animaux à l'adoption</h1>
                <p>
                    En adoptant un animal de compagnie dans  un des refuges indépendants de France, vous lui offrez une
                    nouvelle vie, une chance d’être enfin heureux.
                     
                    Il vous en sera toujours reconnaissant. Par cette bonne action, vous sauvez des vies et encouragez
                    les bénévoles et les salariés de votre région à poursuivre leurs missions de service public.
                    L’équipe que vous avez rencontrée  au refuge est compétente et vous a conseillé au mieux,  dans le
                    seul but de construire, avec votre nouveau compagnon, une relation harmonieuse au sein de votre
                    foyer. Si un problème d’adaptation, de comportement  ou de santé survenait, n’hésitez pas à
                    contacter le refuge.  Il vous renseignera ou vous dirigera vers des personnes compétentes. 
                </p>
            </div>
        </section>

        <section class="timeline-section">
            <article class="timeline-card">
                <h2>3 jours</h2>
                <p>Votre animal arrive à peine au sein de votre foyer...</p>
                <p class="warning">⚠️ Lors de cette première phase, votre animal peut ne pas manger ni boire.</p>
            </article>

            <article class="timeline-card">
                <h2>3 semaines</h2>
                <p>Les premières semaines de cohabitation vont permettre à votre animal d'apprendre à mieux vous
                    connaître...</p>
            </article>

            <article class="timeline-card">
                <h2>3 mois</h2>
                <p>Après environ trois mois, votre animal aura pris ses marques et développé une réelle complicité avec
                    vous...</p>
            </article>
        </section>
<section class="filters">
    <form method="GET" action="adopter.php">
        <select name="espece" onchange="this.form.submit()">
            <option value="tous" <?= $espece_filtre == 'tous' ? 'selected' : '' ?>>Toutes les espèces</option>
            <option value="Chien" <?= $espece_filtre == 'Chien' ? 'selected' : '' ?>>Chiens</option>
            <option value="Chat" <?= $espece_filtre == 'Chat' ? 'selected' : '' ?>>Chats</option>
        </select>

        <select name="tri" onchange="this.form.submit()">
            <option value="recent" <?= $tri == 'recent' ? 'selected' : '' ?>>Plus récents</option>
            <option value="ancien" <?= $tri == 'ancien' ? 'selected' : '' ?>>Plus anciens</option>
            <option value="age_asc" <?= $tri == 'age_asc' ? 'selected' : '' ?>>Âge (croissant)</option>
            <option value="age_desc" <?= $tri == 'age_desc' ? 'selected' : '' ?>>Âge (décroissant)</option>
        </select>
    </form>
</section>

            <details class="filter-item">
                <summary>Age <span class="arrow">▼</span></summary>
                <div class="filter-content">
                    <input type="number" min="0" max="50" placeholder="Entrez un âge">
                </div>
            </details>

            <details class="filter-item">
                <summary>Sexe <span class="arrow">▼</span></summary>
                <div class="filter-content">
                    <label><input type="checkbox"> Masculin</label>
                    <label><input type="checkbox"> Féminin</label>
                </div>
            </details>
        </section>

        <div class="trier-container">
            <button class="btn-trier">Trier</button>
        </div>

<section class="animal-grid">
    <?php foreach ($animaux as $animal): ?> 
        <?php if ($animal['statut'] === 'disponible'): ?>
            <article class="animal-card">
                <img src="images/animaux/<?= htmlspecialchars($animal['photo']) ?>" 
                     alt="Photo de <?= htmlspecialchars($animal['nom']) ?>">
                <h3><?= htmlspecialchars($animal['nom']) ?></h3>
                <p>Âge : <?= htmlspecialchars($animal['age']) ?> ans<br/>
                Sexe : <?= htmlspecialchars($animal['sexe']) ?><br/>
                description : <?= htmlspecialchars($animal['description']) ?></p>
            </article>

        <?php else: ?>
            <article class="animal-card-indisponible">
                <img src="images/animaux/<?= htmlspecialchars($animal['photo']) ?>" 
                     alt="Photo de <?= htmlspecialchars($animal['nom']) ?>">
                <h3><?= htmlspecialchars($animal['nom']) ?> (Indisponible)</h3>
                <p>Cet animal n'est plus à l'adoption pour le moment.</p>
            </article>
        <?php endif; ?>
    <?php endforeach; ?>
</section>
    </main>

    <?php 
        include('header et footer/footer.php'); 
    ?>
</body>

</html>