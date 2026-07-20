<?php
$page_titre = "Déclaration d'accessibilité — SPA de la Haute-Loire";
$page_description = "Déclaration d'accessibilité numérique du site de la SPA de la Haute-Loire.";
?>
<!DOCTYPE html>
<html lang="fr">
<?php include('header et footer/head.php'); ?>
<body>
<?php include('header et footer/header.php'); ?>

<main class="page-legale">
    <h1>Déclaration d'accessibilité</h1>

    <p>
        La SPA de la Haute-Loire souhaite que son site soit consultable par toutes et tous,
        quel que soit le matériel ou le mode de navigation utilisé.
    </p>

    <section>
        <h2>État de conformité</h2>
        <p>
            Ce site est en <strong>conformité partielle</strong> avec le Référentiel général
            d'amélioration de l'accessibilité (RGAA), en raison des non-conformités listées
            ci-dessous. Cette déclaration a été établie en juillet 2026 à la suite d'un audit
            interne du code source.
        </p>
    </section>

    <section>
        <h2>Améliorations réalisées</h2>
        <ul>
            <li>Textes alternatifs descriptifs sur les photographies des animaux (nom, espèce, sexe).</li>
            <li>Hiérarchie des titres corrigée : un titre de niveau 1 unique par page.</li>
            <li>Menu de navigation utilisable au clavier, avec les attributs ARIA appropriés.</li>
            <li>Champs de formulaire tous associés à une étiquette explicite.</li>
            <li>Résultats de recherche et messages de confirmation annoncés aux lecteurs d'écran.</li>
            <li>Fiche animal utilisable au clavier, fermeture par la touche Échap et retour du focus.</li>
            <li>Site consultable sans JavaScript (filtrage et formulaires en dégradation gracieuse).</li>
        </ul>
    </section>

    <section>
        <h2>Non-conformités connues</h2>
        <ul>
            <li>Certains contrastes de couleurs restent à vérifier sur l'ensemble des composants.</li>
            <li>La vidéo de la page d'accueil est décorative et ne dispose pas de transcription.</li>
            <li>Le carrousel de la page d'accueil n'offre pas encore de commande de mise en pause.</li>
            <li>Les documents PDF proposés au téléchargement ne sont pas encore accessibles.</li>
        </ul>
    </section>

    <section>
        <h2>Retour d'information et contact</h2>
        <p>
            Si vous n'arrivez pas à accéder à un contenu ou à un service, contactez-nous à
            <a href="mailto:spa-haute-loire@yahoo.fr">spa-haute-loire@yahoo.fr</a> ou au
            04 71 02 65 50 : nous vous orienterons vers une alternative accessible ou vous
            fournirons le contenu sous une autre forme.
        </p>
    </section>

    <section>
        <h2>Voie de recours</h2>
        <p>
            Si vous constatez un défaut d'accessibilité vous empêchant d'accéder à un contenu et
            que vous n'obtenez pas de réponse satisfaisante de notre part, vous pouvez saisir le
            Défenseur des droits (<a href="https://www.defenseurdesdroits.fr" target="_blank"
            rel="noopener">defenseurdesdroits.fr</a>).
        </p>
    </section>
</main>

<?php include('header et footer/footer.php'); ?>
</body>
</html>
