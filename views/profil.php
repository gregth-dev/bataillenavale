<?php require_once 'inc/head.php' ?>
<script src="https://www.google.com/recaptcha/api.js?render=6LdXTOsUAAAAAOcG-k0xGrxkZ8YoNQbzjvxYwNiK"></script>

<body>
    <div class="se-pre-con"></div>
    <header>
        <?php require_once 'inc/nav.php' ?>
    </header>
    <main class="container flex-around">
        <section>
            <h1 class="profil"><?= $title ?></h1>
            <div class="profil">
                <p class="message" data=""></p>
                <div class="form">
                    <div class="profil-avatars">
                        <?php foreach ($avatarsList as $avatar) {
                            $id = substr($avatar, 6, -4) ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="avatar" value="<?= $id ?>">
                                <img src="/assets/img/avatars/avatar<?= $id ?>.png" alt="avatar<?= $id ?>">
                            </div>
                        <?php } ?>
                    </div>
                    <div class="profil-content">
                        <div class="profil-text">
                            <div class="form-group">
                                <label for="name">Changer votre pseudo</label>
                                <input type="text" name="name" class="form-control" id="name" value="<?= $user->name ?>" placeholder="<?= $user->name ?>">
                                <p class="invalid-feedback"></p>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" class="form-control" id="email" value="<?= $user->email ?>" placeholder="<?= $user->email ?>">
                                <p class="invalid-feedback"></p>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword2">Nouveau Mot de passe</label>
                                <input type="password" name="newPassword" class="form-control" id="newPassword">
                                <p class="invalid-feedback"></p>
                                <small id="passwordHelp" class="form-text text-muted">
                                    *8 caractères dont 1 chiffre, 1 minuscule, 1 majuscule et un caractère spécial.
                                </small>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword1">Mot de passe actuel</label>
                                <input type="password" name="password" class="form-control" id="password" placeholder="Obligatoire" required>
                                <p class="invalid-feedback"></p>
                            </div>
                        </div>
                        <div class="profil-text">
                            <button type="submit" id="saveProfil" class="btn btn-success" value="update" name="update"><i class="fas fa-user-edit"></i><br>Mettre à jour le profil</button>
                            <?php if ($user->role) { ?>
                                <a href="/user/updateUsers"><button type="submit" class="btn btn-primary" value="updateUsers" name="updateUsers"><i class="fas fa-cogs"></i><br>Gestion des joueurs</button></a>
                                <a href="/user/updateAvatars"><button type="submit" class="btn btn-primary" value="updateAvatars" name="updateAvatars"><i class="fas fa-cogs"></i><br>Gestion des avatars</button></a>
                            <?php } ?>
                            <?php if (!$user->role) { ?>
                                <button type="submit" id="deleteProfil" class="btn btn-danger" value="delete" name="delete"><i class="fas fa-user-times" style="font-size: 20px"></i><br>Supprimer le compte</button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer>
        <?php require_once 'inc/footer.php' ?>
    </footer>
    <script src="/assets/js/MessageInfo.js"></script>
    <script src="/assets/js/FormValidator.js"></script>
    <script src="/assets/js/Profil.js"></script>
    <script src="/assets/js/appProfil.js"></script>
    <?php if (entities\User::getUserSession()) {
        require_once 'inc/userLinksJS.php';
    } ?>
</body>

</html>