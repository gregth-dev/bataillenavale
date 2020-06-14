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
                        <a class="nav-link hvr-underline" href="/user/connexion"><i class="far fa-user"></i> Connexion</a>
                    </li>
                <?php } ?>
            </div>
        </nav>
    </header>
    <main class="container flex-around">
        <section>
            <h1 class="inscription"><?= $title ?></h1>
            <p class="message" data=""></p>
            <div class="inscription">
                <?php if (isset($errors)) { ?>
                    <p class="message" data="<?= $data ?>"><?= implode('', $errors ?? []) ?></p>
                <?php } ?>
                <div class="container form">
                    <div id="inscription-form" role="form">
                        <div class="row">
                            <div class="col-md-6">
                                <label></label>
                                <input type="email" name="email" class="form-control" placeholder="* Votre email" id="email" required>
                                <p class="invalid-feedback"></p>
                            </div>
                            <div class="col-md-6">
                                <label></label>
                                <input type="password" name="password" class="form-control" placeholder="* Mot de passe" id="password" required>
                                <p class="invalid-feedback"></p>
                                <small id="passwordHelp" class="form-text text-muted">
                                    *8 caractères dont 1 chiffre, 1 minuscule, 1 majuscule et un caractère spécial.
                                </small>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="conditions" value="accept" id="check" required>
                                    <p class="invalid-feedback"></p>
                                    <label>
                                        <a href="/charte">J'accepte les conditions d'utilisation</a>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-12 mt-4 text-left">
                                <p>*Informations sont requises.</p>
                            </div>
                            <div class="col-md-12 mt-4">
                                <button type="submit" value="Envoyer" class="btn btn-outline-info waves-effect mx-auto"><i class="far fa-paper-plane"></i> Envoyer</button>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </main>
    <footer>
        <?php require_once 'inc/footer.php' ?>
    </footer>
</body>
<script src="/assets/js/FormValidator.js"></script>
<script src="/assets/js/MessageInfo.js"></script>
<script src="/assets/js/inscription.js"></script>

</html>