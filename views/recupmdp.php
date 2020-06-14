<?php require_once 'inc/head.php';?>

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
                <form action="/user/recupmdp" method="POST">
                    <div class="form-group">
                        <label for="inputEmail1">Email du compte</label>
                        <input type="email" name="email" class="form-control" id="inputEmail1">
                    </div>
                    <button type="submit" class="btn btn-primary">Envoyer</button>
                </form>
            </div>
        </section>
    </main>
    <footer>
        <?php require_once 'inc/footer.php' ?>
    </footer>
</body>

</html>