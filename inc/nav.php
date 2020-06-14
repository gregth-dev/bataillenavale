<?php

declare(strict_types=1);

use entities\User;
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-black">
    <a class="navbar-brand" href="/"><img src="/assets/img/logoNav.png" alt="logo"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link hvr-underline" href="/battle/gameOne"><i class="fas fa-gamepad"></i> Jouer</a>
            </li>
            <li class="nav-item">
                <a class="nav-link hvr-underline" href="/user/bestscore"><i class="fas fa-trophy"></i> Meilleurs Scores</a>
            </li>
            <li class="nav-item">
                <a class="nav-link hvr-underline" href="/battle/partieMultijoueur"><i class="far fa-comments"></i> Multijoueur</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="far fa-question-circle"></i> Aide
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item hvr-underline" href="/help/reglesdujeu">Règles du jeu</a>
                    <a class="dropdown-item hvr-underline" href="/help/installation">Installation des bateaux</a>
                    <a class="dropdown-item hvr-underline" href="/help/attaques">Les attaques spéciales</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item hvr-underline" href="/help/multijoueur">Le mode multijoueur</a>
                </div>
            </li>
        </ul>
        <?php if (User::getUserSession()) { ?>
            <li class="nav-item text-center">
                <img id="avatarPlayer" src="/assets/img/avatars/avatar<?= User::getUserSession()->avatar ?>.png" alt="Avatar" data="<?= User::getUserSession()->avatar ?>"><br>
                <span id="namePlayer" data="<?= User::getUserSession()->idUser ?>"><?= User::getUserSession()->name ?></span><br>
                <span id="scorePlayer">Score : <?= User::getUserSession()->score ?></span>
            </li>
            <li class="nav-item text-center">
                <a class="nav-link hvr-underline" href="/user/profil"><i class="far fa-user pr-2" aria-hidden="true"></i> Profil</a><br>
                <a class="nav-link hvr-underline" href="/user/logout"><i class="fas fa-sign-out-alt"></i> Deconnexion</a>
            </li>
        <?php } else { ?>
            <li class="nav-item">
                <a class="nav-link hvr-underline" href="/user/connexion"><i class="far fa-user"></i> Connexion</a>
            </li>
            <li class="nav-item">
                <a class="nav-link hvr-underline" href="/user/inscription"><i class="fas fa-user-plus"></i> Inscription</a>
            </li>
        <?php } ?>
    </div>
</nav>