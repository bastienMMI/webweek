
<?php
include('config/configuration.php');
include('scripts/connection.php');

// 1. Récupération des filtres depuis l'URL (valeurs par défaut conformes au SQL)
$espece_filtre = isset($_GET['espece']) ? $_GET['espece'] : 'tous';
$sexe_filtre = isset($_GET['sexe']) ? $_GET['sexe'] : 'tous';
$age_max = (isset($_GET['age_max']) && $_GET['age_max'] !== '') ? (int)$_GET['age_max'] : null;
$tri = isset($_GET['tri']) ? $_GET['tri'] : 'recent';

// 2. Construction de la requête SQL dynamique
$sql = "SELECT * FROM animal WHERE 1=1"; 
$params = [];

// Filtre par espèce (doit correspondre aux enums du SQL : chat, chien, autre)
if ($espece_filtre !== 'tous') {
    $sql .= " AND espece = ?";
    $params[] = $espece_filtre;
}

// Filtre par sexe (doit correspondre aux enums du SQL : masculin, feminin)
if ($sexe_filtre !== 'tous') {
    $sql .= " AND sexe = ?";
    $params[] = $sexe_filtre;
}

// Filtre par âge max
if ($age_max !== null) {
    $sql .= " AND age <= ?";
    $params[] = $age_max;
}

// 3. Exécution
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

<section class="filter-section">
    <form method="GET" action="adopter.php" class="filter-form">
        <div class="filter-group">
            <label>Espèce</label>
            <select name="espece">
                <option value="tous">Toutes</option>
                <option value="chien" <?= $espece_filtre == 'chien' ? 'selected' : '' ?>>Chiens</option>
                <option value="chat" <?= $espece_filtre == 'chat' ? 'selected' : '' ?>>Chats</option>
            </select>
        </div>

        <div class="filter-group">
            <label>Sexe</label>
            <select name="sexe">
                <option value="tous">Tous</option>
                <option value="masculin" <?= $sexe_filtre == 'masculin' ? 'selected' : '' ?>>Mâle</option>
                <option value="feminin" <?= $sexe_filtre == 'feminin' ? 'selected' : '' ?>>Femelle</option>
            </select>
        </div>

        <div class="filter-group">
            <label>Âge max</label>
            <input type="number" name="age_max" value="<?= htmlspecialchars($age_max ?? '') ?>" placeholder="Ex: 5">
        </div>

        <button type="submit" class="btn-filter-submit">Filtrer</button>
        <a href="adopter.php" class="inscription-link">Réinitialiser</a>
    </form>
</section>
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
