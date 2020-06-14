<?php require_once 'inc/head.php'; ?>

<body>
    <div class="se-pre-con"></div>
    <header>
        <?php require_once 'inc/nav.php' ?>
    </header>
    <main class="container-fluid flex-around">
        <section class="powers">
            <h1 class="powers"><?= $title ?></h1>
            <div class="powers">
                <ul>
                    <li>Bombardement</li>
                    <li><img class="powersbutton" src="/assets/img/power1.png" alt="power1"></li>
                    <li>Lancer une attaque qui touchera 3 cibles au hazard</li>
                </ul>
                <video width="640" height="360" controls>
                    <source src="/assets/videos/tuto4.mp4" type="video/mp4">
                </video>
            </div>
            <div class="powers">
                <ul>
                    <li>MégaBomb</li>
                    <li><img class="powersbutton" src="/assets/img/power2.png" alt="power2"></li>
                    <li>Lancer une attaque dévastatrice qui touchera toutes les cibles autour de votre attaque.</li>
                </ul>
                <video width="640" height="360" controls>
                    <source src="/assets/videos/tuto5.mp4" type="video/mp4">
                </video>
            </div>
            <div class="powers">
                <ul>
                    <li>Réparation</li>
                    <li><img class="powersbutton" src="/assets/img/power3.png" alt="power3"></li>
                    <li>Réparer une case d'une bateau touché. (impossible de réparer un bateau coulé.</li>
                </ul>
                <video width="640" height="360" controls>
                    <source src="/assets/videos/tuto6.mp4" type="video/mp4">
                </video>
            </div>
        </section>
    </main>
    <footer>
        <?php require_once 'inc/footer.php' ?>
    </footer>
    <?php if (entities\User::getUserSession()) { require_once 'inc/userLinksJS.php'; } ?>
</body>

</html>