<?php require_once 'inc/head.php'; ?>

<body>
    <div class="se-pre-con"></div>
    <header>
        <?php require_once 'inc/nav.php' ?>
    </header>
    <main class="container flex-around">
        <section class="meilleurscore">
            <h1 class="meilleurscore"><?= $title ?></h1>
            <table class="table table-dark">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col" class="meilleurscore">Pseudo</th>
                        <th scope="col" class="meilleurscore">Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $user) { ?>
                    <tr>
                        <th scope="row"><?= $i++ ?></th>
                        <td class="meilleurscore align-middle"><img src="/assets/img/avatars/avatar<?= $user->avatar ?>.png" alt="avatar"> <?= $user->name ?></td>
                        <td class="meilleurscore align-middle"><?= $user->score ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
    </main>
    <footer>
        <?php require_once 'inc/footer.php' ?>
    </footer>
    <?php if (entities\User::getUserSession()) { require_once 'inc/userLinksJS.php'; } ?>
</body>

</html>