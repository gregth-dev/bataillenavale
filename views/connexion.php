<?php

use entities\User;

require_once 'inc/head.php'; ?>
<script src="https://www.google.com/recaptcha/api.js?render=6LdXTOsUAAAAAOcG-k0xGrxkZ8YoNQbzjvxYwNiK"></script>

<body>
    <div class="se-pre-con"></div>
    <header>
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
                        <a class="nav-link hvr-underline" href="/user/inscription"><i class="fas fa-user-plus"></i> Inscription</a>
                    </li>
                <?php } ?>
            </div>
        </nav>
    </header>
    <main class="container flex-around">
        <section>
            <h1 class="connexion"><?= $title ?></h1>
            <div class="connexion">
                <?php if (isset($errors)) { ?>
                    <p class="message" data="<?= $data ?>"><?= implode('', $errors ?? []) ?></p>
                <?php } ?>
                <form action="/user/login" method="POST">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email</label>
                        <input type="email" name="email" class="form-control" id="exampleInputEmail1">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Mot de passe</label>
                        <input type="password" name="password" class="form-control" id="exampleInputPassword1">
                    </div>
                    <button type="submit" class="btn btn-outline-info waves-effect"><i class="far fa-user pr-2" aria-hidden="true"></i>Connexion</button>
                </form>
                <p><a href="/user/recuperation">Mot de passe oublié</a></p>
            </div>
        </section>
    </main>
    <footer>
        <?php require_once 'inc/footer.php' ?>
    </footer>
</body>

</html>