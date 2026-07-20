<?php
$page_titre = "SPA de la Haute-Loire — Refuge et adoption au Puy-en-Velay";
$page_description = "Le refuge de la SPA de la Haute-Loire accueille chiens et chats à l'adoption à Polignac. Adoptez, faites un don ou devenez bénévole.";
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
    <main>
        <section class="hero">
            <video autoplay muted loop playsinline id="hero-video" poster="images/carousel_index/Banniere1.webp">
                <source src="vidéo/videobanieres.webm" type="video/webm">
            </video>
            <div class="hero-content">
                <p class="eyebrow"><span class="patte" aria-hidden="true"></span>Refuge de Polignac · Depuis 1983</p>
                <h1>La SPA de la Haute-Loire vous souhaite la bienvenue</h1>
                <p class="hero-horaires">Ouvert du lundi au samedi, de 13h30 à 17h30.</p>
                <div class="hero-actions">
                    <a href="adopter.php" class="btn-hero">J'adopte</a>
                    <a href="boutique.php" class="btn-hero-secondaire">La boutique solidaire</a>
                </div>
            </div>
        </section>

        <section class="section-carousel">
    <div class="container carousel-layout">
        
        <div id="main-carousel" class="carousel-container">
            <button class="carousel-btn prev" aria-label="Précédent">❮</button>
            <button class="carousel-btn next" aria-label="Suivant">❯</button>

            <div class="carousel-track">
               
                <div class="carousel-item"><img src="images/carousel_index/Banniere1.webp" alt="" loading="lazy"></div>
                <div class="carousel-item"><img src="images/carousel_index/Banniere2.webp" alt="" loading="lazy"></div>
                <div class="carousel-item"><img src="images/carousel_index/Banniere3.webp" alt="" loading="lazy"></div>
            </div>
            
            <div class="carousel-nav"></div>
        </div>

        <div class="carousel-text reveal">
            <h2>Leur futur commence par votre visite : découvrez-les tous.</h2>
        </div>
        
    </div>
</section>
        <section class="section-about reveal">
            <div class="container">
                <h3>A Propos de nous</h3>
                <p>La SPA a vu le jour en 1983 grâce à une poignée d'amoureux des animaux. Pendant près de 10 ans, ses
                    locaux se situèrent au rez-de-chaussée d'une maison vétuste Rue des Farges au Puy en Velay. Lieu peu
                    approprié pour recevoir les chiens ou chats en errance. Le travail des bénévoles y était difficile
                    et il n'y avait alors que très peu d'organisation. Enfin en 1994, le refuge actuel est "sorti" de
                    terre grâce à l'acharnement de ces mêmes bénévoles et de quelques élus convaincus de la nécessité de
                    ce site. Aujourd'hui, la SPA de la Haute-Loire est affiliée à la Confédération Nationale Défense de
                    l'Animal. Elle accueille toute l'année une soixantaine de chiens et une trentaine de chats et
                    chatons venant des communes conventionnées.</p>
            </div>
        </section>


<section id="contact" class="section-contact reveal">
    <div class="container">
        <div class="contact-flex-container">
            <div class="contact-info">
                <h3>Contact</h3>
                <p>7 Impasse du Refuge ZA Plaine de Bleu<br>43000 Polignac</p>
                <p>spa-haute-loire@yahoo.fr</p>
                <p>04 71 02 65 50</p>
            </div>
            <div id="mapSPA_XX" class="map-box"></div>
        </div>

        <div class="contact-form">
               <h3>Formulaire de contact</h3>

                <div id="contact-retour" role="status" aria-live="polite"></div>

                <form id="monFormulaireContact" action="api/contact.php" method="POST">
                    <div class="form-row">
                        <div class="input-group-index">
                            <label for="contact-nom">Nom</label>
                            <input type="text" id="contact-nom" name="nom" required>
                        </div>
                        <div class="input-group-index">
                            <label for="contact-email">E-mail</label>
                            <input type="email" id="contact-email" name="email" required>
                        </div>
                    </div>
                    <div class="input-group-index">
                        <label for="contact-tel">Téléphone</label>
                        <input type="tel" id="contact-tel" name="telephone">
                    </div>
                    <div class="input-group-index">
                        <label for="contact-objet">Objet</label>
                        <input type="text" id="contact-objet" name="objet">
                    </div>
                    <div class="input-group-index">
                        <label for="contact-message">Message</label>
                        <textarea id="contact-message" name="message" rows="4" required
                                  aria-describedby="aide-message"></textarea>
                        <small id="aide-message">10 caractères minimum.</small>
                    </div>
                    <button type="submit" class="btn-submit">Envoyer</button>
                </form>
            </div>
        </div>
</section>
        <div class="bouton_fixe">
        <button id="scrollToTop" class="scroll-to-top">↑</button>
        <a href="don.php" id="bouton_don">Faire un don</a>
        </div>
    </main>

<?php 
  include('header et footer/footer.php'); 
?>
<script src="js/contact.js" defer></script>
</body>

</html>