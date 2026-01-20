<?php
include('config/configuration.php');
include('scripts/connection.php');
include('classes/animal.php');

$animalManager = new AnimalManager($connection);

// Chargement initial (PHP classique au premier affichage)
$espece_filtre = $_GET['espece'] ?? 'tous';
$sexe_filtre = $_GET['sexe'] ?? 'tous';
$age_max = (!empty($_GET['age_max'])) ? (int)$_GET['age_max'] : null;
$animaux = $animalManager->getFiltered($espece_filtre, $sexe_filtre, $age_max);
?>
<!DOCTYPE html>
<html lang="fr">
<?php include('header et footer/head.php'); ?>
<body>
    <?php include('header et footer/header.php'); ?>
    
    <main>
        <section class="filter-section">
            <form method="GET" action="adopter.php" class="filter-form" id="filter-form">
                <div class="filter-group">
                    <label>Espèce</label>
                    <select name="espece">
                        <option value="tous">Toutes</option>
                        <option value="chien">Chiens</option>
                        <option value="chat">Chats</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Sexe</label>
                    <select name="sexe">
                        <option value="tous">Tous</option>
                        <option value="masculin">Mâle</option>
                        <option value="feminin">Femelle</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Âge max</label>
                    <input type="number" name="age_max" placeholder="Ex: 5">
                </div>
            </form>
        </section>

        <section class="animal-grid" id="animal-grid">
            <?php foreach ($animaux as $animal): ?> 
                <article class="<?= ($animal['statut'] === 'disponible') ? 'animal-card' : 'animal-card-indisponible' ?>">
                    <img src="images/animaux/<?= htmlspecialchars($animal['photo']) ?>" alt="Photo">
                    <h3><?= htmlspecialchars($animal['nom']) ?> <?= ($animal['statut'] !== 'disponible') ? '(Indisponible)' : '' ?></h3>
                    <p>Âge : <?= $animal['age'] ?> ans | Sexe : <?= $animal['sexe'] ?></p>
                </article>
            <?php endforeach; ?>
        </section>
    </main>

    <?php include('header et footer/footer.php'); ?>
    <script src="js/filtre_ajax.js"></script>
</body>
</html>