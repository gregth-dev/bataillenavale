<?php require_once 'inc/head.php'; ?>

<body>
    <div class="se-pre-con"></div>
    <header>
        <?php require_once 'inc/nav.php' ?>
    </header>
    <main class="container flex-around">
        <section class="meilleurscore">
            <h1 class="meilleurscore"><?= $title ?></h1>
            <p class="message" data=""></p>
            <table class="table table-dark">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col" class="meilleurscore">Pseudo</th>
                        <th scope="col" class="meilleurscore">Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) { ?>
                        <tr id="user<?= $user->idUser ?>">
                            <th scope="row"><?= $user->idUser ?></th>
                            <td class="meilleurscore align-middle"><?= $user->name ?></td>
                            <td class="meilleurscore align-middle"><?= $user->email ?></td>
                            <td class="meilleurscore align-middle">
                                <button type="submit" class="btn btn-danger" value="delete" name="delete" onclick="deleteUser(<?= $user->idUser ?>)"><i class="fas fa-user-times"></i> Supprimer</button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
    </main>
    <footer>
        <?php require_once 'inc/footer.php' ?>
    </footer>
    <?php if (entities\User::getUserSession()->role) {
        require_once 'inc/userLinksJS.php'; ?>
        <script src="/assets/js/adminUsers.js"></script>
    <?php } ?>
</body>

</html>