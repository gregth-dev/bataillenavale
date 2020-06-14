<?php require_once 'inc/head.php' ?>

<body>
    <div class="se-pre-con"></div>
    <header>
        <?php require_once 'inc/nav.php' ?>
    </header>
    <main class="container flex-around">
        <section class="multijoueur">
            <h1><?= $title ?></h1>
            <div class="multijoueur">
                <ul>
                    <li>Pour créer une partie multijoueur :</li>
                    <li>Se connecter avec un compte utilisateur :</li>
                    <li>- cliquer sur multijoueur</li>
                    <li>- cliquer sur le joueur que l'on souhaite inviter à jouer</li>
                    <li>- confirmer l'invitation</li>
                </ul>
                <video width="640" height="360" controls>
                    <source src="/assets/videos/tuto7.mp4" type="video/mp4">
                </video>
            </div>
            <div class="multijoueur">
                <ul>
                    <li>Pour créer une partie multijoueur depuis n'importe qu'elle page :</li>
                    <li>Se connecter avec un compte utilisateur :</li>
                    <li>- cliquer sur online</li>
                    <li>- cliquer sur le joueur que l'on souhaite inviter à jouer</li>
                    <li>- confirmer l'invitation</li>
                </ul>
                <video width="640" height="360" controls>
                    <source src="/assets/videos/tuto8.mp4" type="video/mp4">
                </video>
            </div>
            <div class="multijoueur">
                <ul>
                    <li>Accepter une partie multijoueur :</li>
                    <li>- cliquer sur accepter</li>
                    <li>On rentre automatiquement dans la partie</li>
                </ul>
                <video width="640" height="360" controls>
                    <source src="/assets/videos/tuto10.mp4" type="video/mp4">
                </video>
            </div>
        </section>
    </main>
    <?php require_once 'inc/footer.php' ?>
    <?php if (entities\User::getUserSession()) {
        require_once 'inc/userLinksJS.php';
    } ?>
</body>

</html>