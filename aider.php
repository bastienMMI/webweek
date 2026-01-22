<!DOCTYPE html>
<html lang="fr">
    <?php 
  include('header et footer/head.php'); 
?>
<body>
    <?php 
    include('header et footer/header.php'); 
    ?>
    <main>
        <section class="banniere-aider">
            <div class="banniere-aider__overlay">
                <div class="banniere-aider__contenu">
                    <h1>Comment nous aider ?</h1>
                    <p>
                        En adhérant à l'association ou en faisant des dons libres.<br>
                        En venant promener les chiens ou sociabiliser les chats.
                    </p>
                </div>
                <div class="banniere-aider__actions">
                    <a href="don.php" class="btn-don">Faire un don</a>
                    <a href="images/Adhesion.pdf" class="btn-adhesion">Adhérer</a>
                </div>
            </div>
            </div>
        </section>
        <section class="section-benevolat">
            <div class="section-benevolat__cards">
                <div class="section-benevolat__card">
                    <h2>Bénévolat</h2>
                    <p>Donner de son temps pour venir en aide aux pensionnaires du refuge ou lors de travaux ponctuels
                        dans nos locaux.</p>
                </div>
                <div class="section-benevolat__card">
                    <h2>Mécénat</h2>
                    <p>Réservé aux professionnels, c'est un don déductible des impôts de l'entreprise.</p>
                </div>
            </div>
            <a href="index.php#contact" class="section-benevolat__cta">Contacter</a>
        </section>
    <section class="section-partenaires">
        <h2 class="section-partenaires__title">Partenaires</h2>
            <div class="section-partenaires__carousel">
                <div class="carousel-defilement">
                    <div class="partenaire-card"><img src="images/partenaires/Logo_Auvergne-Rhone-Alpes.svg" alt="LogoAURA"> </div>
                    <div class="partenaire-card"><img src="images/partenaires/Logo_Haute_Loire_2014.svg" alt="LogoHauteLoire"></div>
                    <div class="partenaire-card"><img src="images/partenaires/LogoCommunaute_d'agglomeration_du_Puy-en-Velay.webp" alt="LogoAggloDuPuy"></div>
                    <div class="partenaire-card"><img src="images/partenaires/LePuy-en-VelayLogo.webp" alt="LogoPEV"></div>
                    <div class="partenaire-card"><img src="images/partenaires/Logo-MairiePOLIGNAClogoPBVF.webp" alt="LogoMairiePolignac"></div>
                    <div class="partenaire-card"><img src="images/partenaires/Logo-Defense-delAnimal-2023_quadri-1.webp" alt="LogoDefenseAnimale"></div>
                    <div class="partenaire-card"><img src="images/partenaires/Fondation_30_millions_d'amis_logo.svg"alt="Logo30MillionsDamis"></div>
                    <div class="partenaire-card"><img src="images/partenaires/Logo_Carrefour.svg" alt="LogoCarrefour"></div>
                    <div class="partenaire-card"><img src="images/partenaires/Super_U_logo_2009.svg" alt="LogoSuperU"></div>
                    <div class="partenaire-card"><img src="images/partenaires/logo_ARGILE_DU_VELAY-horizontal.webp" alt="LogoArgileVelay"></div>
                    <div class="partenaire-card"><img src="images/partenaires/logo_ALTILIVET_CMJN.webp" alt="LogoAltilivet"></div>
                </div>
            </div>
        </section>
</section>
    </main>
    <?php 
        include('header et footer/footer.php'); 
    ?>

</body>

</html>