<?php require_once 'inc/head.php' ?>
<script src="https://www.google.com/recaptcha/api.js?render=6LdXTOsUAAAAAOcG-k0xGrxkZ8YoNQbzjvxYwNiK"></script>

<body>
    <div class="se-pre-con"></div>
    <header>
        <?php require_once 'inc/nav.php' ?>
    </header>
    <main class="container flex-around">
        <section class="contact">
            <h1><?= $title ?></h1>
            <p class="message" data=""></p>
            <div class="container form">
                <div id="contact-form" role="form">
                    <div class="row">
                        <div class="col-md-6">
                            <label></label>
                            <input type="text" name="prenom" class="form-control" placeholder="* Votre prénom" id="prenom" required>
                            <p class="invalid-feedback"></p>
                        </div>
                        <div class="col-md-6">
                            <label></label>
                            <input type="text" name="nom" class="form-control" placeholder="* Votre nom" id="nom" required>
                            <p class="invalid-feedback"></p>
                        </div>
                        <div class="col-md-6">
                            <label></label>
                            <input type="email" name="email" class="form-control" placeholder="* Votre email" id="email" required>
                            <p class="invalid-feedback"></p>
                        </div>
                        <div class="col-md-6">
                            <label></label>
                            <textarea name="message" id="message" class="form-control area" placeholder="* Votre message" required></textarea>
                            <p class="invalid-feedback"></p>
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
    <?php require_once 'inc/footer.php' ?>
</body>
<script src="/assets/js/FormValidator.js"></script>
<script src="/assets/js/MessageInfo.js"></script>
<script src="/assets/js/contact.js"></script>
<?php if (entities\User::getUserSession()) {
    require_once 'inc/userLinksJS.php';
} ?>

</html>