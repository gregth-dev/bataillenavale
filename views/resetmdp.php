<?php require_once 'inc/head.php'; ?>

<body>
    <div class="se-pre-con"></div>
    <header>
        <?php require_once 'inc/nav.php' ?>
    </header>
    <main class="container flex-around">
        <section>
            <h1 class="connexion"><?= $title ?></h1>
            <div class="connexion">
                <?php if (isset($errors)) { ?>
                    <p class="message" data="<?= $data ?>"><?= implode('', $errors ?? []) ?></p>
                <?php } ?>
                <form action="/user/restoremdp" method="POST">
                    <div class="form-group">
                        <label for="inputEmail1">Email du compte</label>
                        <input type="email" name="email" class="form-control" id="inputEmail1">
                    </div>
                    <div class="form-group">
                        <label for="inputPassword1">Nouveau mot de passe</label>
                        <input type="password" name="password" class="form-control" id="inputPassword1">
                        <small id="passwordHelp" class="form-text text-muted">
                            *8 caractères dont 1 chiffre, 1 minuscule, 1 majuscule et un caractère spécial.
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword2">Retaper votre nouveau mot de passe</label>
                        <input type="password" name="newPassword" class="form-control" id="inputPassword2">
                        <small id="passwordHelp" class="form-text text-muted">
                            *8 caractères dont 1 chiffre, 1 minuscule, 1 majuscule et un caractère spécial.
                        </small>
                    </div>
                    <input type="hidden" name="restoreCode" value="<?= isset($restoreCode) ? $restoreCode : '' ?>">
                    <button type="submit" class="btn btn-primary">Reset le mot de passe</button>
                </form>
            </div>
        </section>
    </main>
    <footer>
        <?php require_once 'inc/footer.php' ?>
    </footer>
</body>

</html>