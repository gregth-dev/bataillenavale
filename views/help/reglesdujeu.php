<?php require_once 'inc/head.php' ?>

<body>
    <div class="se-pre-con"></div>
    <header>
        <?php require_once 'inc/nav.php' ?>
    </header>
    <main class="container flex-around">
        <section class="reglesdujeu">
            <h1 class="reglesdujeu"><?= $title ?></h1>
            <h3>Pour jouer à la bataille navale, il faut par joueur :</h3>
            <p class="reglesdujeu">
                <ul>
                    <li>Une grille de jeu numérotée de 1 à 10 horizontalement et de A à J verticalement</li>
                    <li>1 porte avion (5 cases)</li>
                    <li>1 croiseur (4 cases)</li>
                    <li>1 contre torpilleur (3 cases)</li>
                    <li>1 sous-marin (3 cases)</li>
                    <li>1 torpilleur (2 cases)</li>
                </ul>
            </p>
            <h3>Commencer une partie de bataille navale :</h3>
            <p class="p-reglesdujeu">
                Au début du jeu, chaque joueur place à sa guise tous les bateaux sur sa grille de façon stratégique. Le
                but
                étant de compliquer au maximum la tache de son adversaire, c’est-à-dire détruire tous vos navires. Bien
                entendu, le joueur ne voit pas la grille de son adversaire.
                Une fois tous les bateaux en jeu, la partie peut commencer.. Un à un, les joueurs se tire dessus pour
                détruire les navires ennemis.
                <span>Exemple le joueur dit a voit haute H7 correspondant à la case au croisement de la lettre H et du
                    numéro 7 sur
                    les côtés des grilles.
                </span>
            </p>
            <p class="p-reglesdujeu">
                Si un joueur tire sur un navire ennemi, l’adversaire doit le signaler en disant « touché ». Il peut pas
                jouer
                deux fois de suite et doit attendre le tour de l’autre joueur.
                Si le joueur ne touche pas de navire, l’adversaire le signale en disant « raté » .
                Si le navire est entièrement touché l’adversaire doit dire « touché coulé ».
            </p>
            <p class="p-reglesdujeu">
                Les pions blancs et des pions rouges servent à se souvenir des tirs ratés (blancs) et les tirs touchés
                (rouges).
                Il est indispensable de les utiliser pour ne pas tirer deux fois au même endroit et donc ne pas perdre
                de
                temps
                inutilement. Ces pions se placent sur la grille du dessus.
                Comment gagner une partie de bataille navale
            </p>
            <p class="p-reglesdujeu">
                Une partie de bataille navale se termine lorsque l’un des joueurs n’a plus de navires.
                <h3>Astuces pour gagner à la bataille navale</h3>
                Pour gagner plus rapidement, vous pouvez jouer vos tirs en croix, étant donné que le plus petit navire
                fait
                deux
                cases alors vous ne pourrez éviter aucun autre bateau sur votre chemin. Cette méthode est infaillible
                car
                elle
                est purement logique.
            </p>
        </section>
    </main>
    <footer>
        <?php require_once 'inc/footer.php' ?>
    </footer>
    <?php if (entities\User::getUserSession()) { require_once 'inc/userLinksJS.php'; } ?>
</body>

</html>