<?php
session_start();
include('config/configuration.php');
include('scripts/connection.php');
include('classes/animal.php');

$animalManager = new AnimalManager($connection);

// Filtres lus depuis l'URL : la page reste fonctionnelle sans JavaScript.
$criteres = [
    'espece'    => $_GET['espece'] ?? 'tous',
    'sexe'      => $_GET['sexe']   ?? 'tous',
    'age_max'   => $_GET['age_max'] ?? null,
    'vaccine'   => !empty($_GET['vaccine']),
    'sterilise' => !empty($_GET['sterilise']),
    'identifie' => !empty($_GET['identifie']),
];
$animaux = $animalManager->getFiltered($criteres);

$page_titre = "Adopter un animal — SPA de la Haute-Loire";
$page_description = "Découvrez les chiens, chats et NAC à l'adoption au refuge de la SPA de la Haute-Loire. Filtrez par espèce, sexe et âge, et faites une demande de pré-réservation gratuite.";
?>
<!DOCTYPE html>
<html lang="fr">
<?php include('header et footer/head.php'); ?>
<body>
    <?php include('header et footer/header.php'); ?>

    <main id="contenu">
        <section class="adoption-intro">
            <h1>Nos animaux à l'adoption</h1>
            <p>
                Chacun de nos pensionnaires attend une nouvelle famille. Utilisez les filtres
                pour affiner votre recherche, puis consultez la fiche d'un animal pour en
                savoir plus et faire une demande de pré-réservation gratuite.
            </p>
        </section>

        <section class="filter-section" aria-labelledby="titre-filtres">
            <h2 id="titre-filtres" class="visually-hidden">Filtrer les animaux</h2>

            <form method="GET" action="adopter.php" class="filter-form" id="filter-form">
                <div class="filter-group">
                    <label for="f-espece">Espèce</label>
                    <select name="espece" id="f-espece">
                        <option value="tous">Toutes</option>
                        <?php foreach (['chien' => 'Chiens', 'chat' => 'Chats', 'nac' => 'NAC', 'autre' => 'Autres'] as $v => $l): ?>
                            <option value="<?= $v ?>" <?= $criteres['espece'] === $v ? 'selected' : '' ?>><?= $l ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="f-sexe">Sexe</label>
                    <select name="sexe" id="f-sexe">
                        <option value="tous">Tous</option>
                        <option value="masculin" <?= $criteres['sexe'] === 'masculin' ? 'selected' : '' ?>>Mâle</option>
                        <option value="feminin"  <?= $criteres['sexe'] === 'feminin'  ? 'selected' : '' ?>>Femelle</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="f-age">Âge maximum (ans)</label>
                    <input type="number" name="age_max" id="f-age" min="0" max="30"
                           value="<?= htmlspecialchars($criteres['age_max'] ?? '') ?>" placeholder="Ex : 5">
                </div>

                <fieldset class="filter-group filter-cases">
                    <legend>Suivi sanitaire</legend>
                    <label class="case-a-cocher">
                        <input type="checkbox" name="vaccine" value="1" <?= $criteres['vaccine'] ? 'checked' : '' ?>> Vacciné
                    </label>
                    <label class="case-a-cocher">
                        <input type="checkbox" name="sterilise" value="1" <?= $criteres['sterilise'] ? 'checked' : '' ?>> Stérilisé
                    </label>
                    <label class="case-a-cocher">
                        <input type="checkbox" name="identifie" value="1" <?= $criteres['identifie'] ? 'checked' : '' ?>> Identifié
                    </label>
                </fieldset>

                <!-- Sans JavaScript, ce bouton soumet le formulaire normalement -->
                <div class="filter-group filter-submit">
                    <button type="submit" class="btn-submit">Filtrer</button>
                </div>
            </form>
        </section>

        <!-- Le nombre de résultats est annoncé aux lecteurs d'écran -->
        <p id="resultat-compteur" class="resultat-compteur" role="status" aria-live="polite">
            <?= count($animaux) ?> animal<?= count($animaux) > 1 ? 'ux' : '' ?> à l'adoption
        </p>

        <section class="animal-grid" id="animal-grid" aria-label="Liste des animaux à l'adoption">
            <?php if (empty($animaux)): ?>
                <p class="empty-msg">Aucun animal ne correspond à vos critères pour le moment.</p>
            <?php else: ?>
                <?php foreach ($animaux as $animal): ?>
                    <article class="animal-card <?= $animal['statut'] !== 'disponible' ? 'animal-card-indisponible' : '' ?>">
                        <img src="images/animaux/<?= htmlspecialchars($animal['photo']) ?>"
                             alt="<?= htmlspecialchars($animal['alt']) ?>" loading="lazy">

                        <div class="animal-card-body">
                            <h3><?= htmlspecialchars($animal['nom']) ?></h3>

                            <p class="animal-meta">
                                <?= htmlspecialchars($animal['espece_label']) ?> ·
                                <?= htmlspecialchars($animal['sexe_label']) ?> ·
                                <?= htmlspecialchars($animal['age_lisible']) ?>
                            </p>

                            <ul class="animal-badges">
                                <?php if ($animal['vaccine']): ?><li class="badge badge-ok">Vacciné</li><?php endif; ?>
                                <?php if ($animal['sterilise']): ?><li class="badge badge-ok">Stérilisé</li><?php endif; ?>
                                <?php if ($animal['identifie']): ?><li class="badge badge-ok">Identifié</li><?php endif; ?>
                                <?php if ($animal['statut'] !== 'disponible'): ?>
                                    <li class="badge badge-statut"><?= htmlspecialchars($animal['statut_label']) ?></li>
                                <?php endif; ?>
                            </ul>

                            <button type="button" class="btn-detail" data-id="<?= (int)$animal['id_animal'] ?>">
                                Voir la fiche de <?= htmlspecialchars($animal['nom']) ?>
                            </button>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>

        <!-- Fiche détaillée, remplie en AJAX depuis api/get_animal.php -->
        <div id="fiche-animal" class="modale" hidden role="dialog" aria-modal="true" aria-labelledby="fiche-titre">
            <div class="modale-contenu">
                <button type="button" id="fiche-fermer" class="modale-fermer" aria-label="Fermer la fiche">&times;</button>
                <div id="fiche-corps">
                    <h2 id="fiche-titre"></h2>
                </div>
            </div>
        </div>
    </main>

    <?php include('header et footer/footer.php'); ?>
    <script src="js/filtre_ajax.js" defer></script>
</body>
</html>
