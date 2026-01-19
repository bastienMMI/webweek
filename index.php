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
            <video autoplay muted loop playsinline id="hero-video" poster="fallback-image.jpg">
                <source src="vidéo/videobanieres.webm" type="video/webm">
            </video>
            <div class="hero-content">
                <h1>La SPA de la Haute-Loire vous souhaite la bienvenue !</h1>
                <h3>Nous vous accueillons du Lundi au Samedi, de 13h30 à 17h30.</h3>
                <a href="adopter.php" class="btn-hero">J'adopte</a>
            </div>
        </section>

        <section class="section-carousel">
    <div class="container carousel-layout">
        
        <div id="main-carousel" class="carousel-container">
            <button class="carousel-btn prev" aria-label="Précédent">❮</button>
            <button class="carousel-btn next" aria-label="Suivant">❯</button>

            <div class="carousel-track">
                <!-- Mettre les bonnes images-->
                <div class="carousel-item"><img src="images/attrape poil.webp" alt=""></div>
                <div class="carousel-item"><img src="images/decapsuleur.webp" alt=""></div>
                <div class="carousel-item"><img src="images/banniere.webp" alt=""></div>
            </div>
            
            <div class="carousel-nav"></div>
        </div>

        <div class="carousel-text">
            <h2>Leur futur commence par votre visite : découvrez-les tous.</h2>
        </div>
        
    </div>
</section>
        <section class="section-about">
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


        <section id="contact" class="section-contact">
            <div class="container contact-grid">

                <div class="contact-info">
                    <h3>Contact</h3>
                    <p>7 Impasse du Refuge ZA Plaine de Bleu<br>43000 Polignac</p>
                    <p>spa-haute-loire@yahoo.fr</p>
                    <p>04 71 02 65 50</p>


                </div>
                <div id="map-container" class="map-box"></div>
            </div>

            <div class="contact-form">
                <h3>Formulaire</h3>
                <form>
                    <div class="form-row">
                        <div class="input-group">
                            <label>Nom</label>
                            <input type="text">
                        </div>
                        <div class="input-group">
                            <label>E-mail</label>
                            <input type="email">
                        </div>
                    </div>
                    <div class="input-group">
                        <label>Téléphone</label>
                        <input type="tel">
                    </div>
                    <div class="input-group">
                        <label>Objet</label>
                        <input type="text">
                    </div>
                    <div class="input-group">
                        <label>Message</label>
                        <textarea rows="4"></textarea>
                    </div>
                    <button type="submit" class="btn-submit">Envoyer</button>
                </form>
            </div>

            </div>
        </section>
        <button id="scrollToTop" class="scroll-to-top">↑</button>
    </main>

<?php 
  include('header et footer/footer.php'); 
?>
</body>

</html>