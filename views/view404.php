<?php require_once 'inc/head.php' ?>

<body>
    <header>
        <?php require_once 'inc/nav.php' ?>
    </header>
    <main class="container-fluid flex-around">
        <img src="/assets/img/error404.png" alt="">
    </main>
    <footer>
        <?php require_once 'inc/footer.php' ?>
    </footer>
    <?php if (entities\User::getUserSession()) { ?>
        <script src="/assets/js/LaunchBattle.js"></script>
        <script src="/assets/js/appLaunchBattle.js"></script>
    <?php } ?>
</body>

</html>