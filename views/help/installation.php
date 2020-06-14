<?php require_once 'inc/head.php' ?>

<body>
    <div class="se-pre-con"></div>
    <header>
        <?php require_once 'inc/nav.php' ?>
    </header>
    <main class="container flex-around">
        <section class="installation-boat">
            <h1 class="installation-boat"><?= $title ?></h1>
            <div class="installation-boat">
                <ul>
                    <li>Pour ajouter un bateau :</li>
                    <li>Choisissez votre bateau dans la liste :</li>
                    <li>- clic gauche positionnement vertical du bateau</li>
                    <li>- clic droit positionnement horizontal du bateau</li>
                </ul>
                <video width="640" height="360" controls>
                    <source src="/assets/videos/tuto1.mp4" type="video/mp4">
                </video>
            </div>
            <div class="installation-boat">
                <ul>
                    <li>Pour supprimer un bateau :</li>
                    <li>Choisissez votre bateau dans la liste :</li>
                    <li>- Appuyer sur la touche Suppr de votre clavier</li>
                </ul>
                <video width="640" height="360" controls>
                    <source src="/assets/videos/tuto2.mp4" type="video/mp4">
                </video>
            </div>
            <div class="installation-boat">
                <ul>
                    <li>Pour installer les bateaux automatiquement cliquez sur le bouton "Placement automatique"</li>
                </ul>
                <video width="640" height="360" controls>
                    <source src="/assets/videos/tuto3.mp4" type="video/mp4">
                </video>
            </div>
            <div class="installation-boat">
                <ul>
                    <li>Réduire les grilles de jeu pour une meilleure visibilité si besoin</li>
                </ul>
                <video width="640" height="360" controls>
                    <source src="/assets/videos/tuto9.mp4" type="video/mp4">
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