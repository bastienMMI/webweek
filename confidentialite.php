<?php
$page_titre = "Politique de confidentialité — SPA de la Haute-Loire";
$page_description = "Politique de confidentialité et traitement des données personnelles (RGPD) du site de la SPA de la Haute-Loire.";
?>
<!DOCTYPE html>
<html lang="fr">
<?php include('header et footer/head.php'); ?>
<body>
<?php include('header et footer/header.php'); ?>

<main class="page-legale">
    <h1>Politique de confidentialité</h1>

    <p>
        La SPA de la Haute-Loire attache de l'importance à la protection de vos données
        personnelles. Cette page explique quelles données sont collectées, pourquoi, combien de
        temps elles sont conservées et quels sont vos droits, conformément au Règlement général
        sur la protection des données (RGPD) et à la loi Informatique et Libertés.
    </p>

    <section>
        <h2>Responsable du traitement</h2>
        <p>
            SPA de la Haute-Loire — 7 Impasse du Refuge, ZA Plaine de Bleu, 43000 Polignac.<br>
            Contact : <a href="mailto:spa-haute-loire@yahoo.fr">spa-haute-loire@yahoo.fr</a>
        </p>
    </section>

    <section>
        <h2>Données collectées et finalités</h2>

        <table class="table-donnees">
            <caption>Traitements réalisés sur le site</caption>
            <thead>
                <tr>
                    <th scope="col">Traitement</th>
                    <th scope="col">Données</th>
                    <th scope="col">Finalité</th>
                    <th scope="col">Base légale</th>
                    <th scope="col">Conservation</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Création de compte</td>
                    <td>Nom, prénom, e-mail, téléphone, mot de passe</td>
                    <td>Gérer votre espace personnel et vos demandes</td>
                    <td>Consentement</td>
                    <td>3 ans après la dernière connexion</td>
                </tr>
                <tr>
                    <td>Pré-réservation d'un animal</td>
                    <td>Compte utilisateur, animal choisi, message</td>
                    <td>Traiter votre demande d'adoption</td>
                    <td>Mesures précontractuelles</td>
                    <td>3 ans après la demande</td>
                </tr>
                <tr>
                    <td>Formulaire de contact</td>
                    <td>Nom, e-mail, téléphone, message</td>
                    <td>Répondre à votre demande</td>
                    <td>Consentement</td>
                    <td>1 an après le dernier échange</td>
                </tr>
                <tr>
                    <td>Dons</td>
                    <td>Identité, coordonnées, montant, date</td>
                    <td>Enregistrer le don et établir un reçu fiscal</td>
                    <td>Obligation légale</td>
                    <td>10 ans (obligations comptables)</td>
                </tr>
            </tbody>
        </table>
    </section>

    <section>
        <h2>Destinataires</h2>
        <p>
            Vos données sont destinées aux seuls membres habilités de l'association. Elles ne
            sont ni vendues, ni cédées, ni transmises à des tiers à des fins commerciales, et ne
            font l'objet d'aucun transfert hors de l'Union européenne.
        </p>
    </section>

    <section>
        <h2>Sécurité</h2>
        <p>
            Les mots de passe sont enregistrés sous forme hachée et ne sont jamais lisibles, y
            compris par l'association. Les accès à la base de données sont restreints et les
            échanges avec le site sont chiffrés.
        </p>
    </section>

    <section>
        <h2>Cookies</h2>
        <p>
            Ce site utilise uniquement un cookie de session, strictement nécessaire à son
            fonctionnement : il permet de vous maintenir connecté à votre espace personnel. Il
            est supprimé à la fermeture du navigateur.
        </p>
        <p>
            Aucun cookie de mesure d'audience, de publicité ou de réseau social n'est déposé.
            Conformément à la réglementation, les cookies strictement nécessaires ne requièrent
            pas votre consentement préalable : aucun bandeau ne vous est donc imposé.
        </p>
        <p>
            La carte du refuge est affichée grâce à la bibliothèque Leaflet et à des fonds de
            plan OpenStreetMap ; leur chargement transmet votre adresse IP à ces services.
        </p>
    </section>

    <section>
        <h2>Vos droits</h2>
        <p>
            Vous disposez d'un droit d'accès, de rectification, d'effacement, de limitation, de
            portabilité et d'opposition sur vos données. Pour les exercer, écrivez à
            <a href="mailto:spa-haute-loire@yahoo.fr">spa-haute-loire@yahoo.fr</a> ou à l'adresse
            postale de l'association, en justifiant de votre identité.
        </p>
        <p>
            Si vous estimez, après nous avoir contactés, que vos droits ne sont pas respectés,
            vous pouvez adresser une réclamation à la CNIL (<a href="https://www.cnil.fr"
            target="_blank" rel="noopener">www.cnil.fr</a>).
        </p>
    </section>
</main>

<?php include('header et footer/footer.php'); ?>
</body>
</html>
