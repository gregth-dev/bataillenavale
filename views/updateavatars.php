<?php

use cfg\CfgApp;
use peps\core\Cfg;

require_once 'inc/head.php'; ?>

<body>
    <div class="se-pre-con"></div>
    <header>
        <?php require_once 'inc/nav.php' ?>
    </header>
    <main class="container flex-around">
        <section class="updateAvatar">
            <h1 class="updateAvatar"><?= $title ?></h1>
            <?php if (isset($errors)) { ?>
                <p class="message" data="<?= $data ?>"><?= implode('', $errors ?? []) ?></p>
            <?php } ?>
            <p class="message" data=""></p>
            <form name="form1" action="/user/avatarSave" method="POST" enctype="multipart/form-data">
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" name="avatar" class="custom-file-input" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" onchange="displayPhoto(this.files)">
                        <label class="custom-file-label" for="inputGroupFile04">Ajouter un fichier</label>
                    </div>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit" id="inputGroupFileAddon04">Valider</button>
                    </div>
                </div>
            </form>
            <div id="thumbnail"><img src="" alt=""></div>
            <table class="table table-dark">
                <thead>
                    <tr>
                        <th scope="col">#ID</th>
                        <th scope="col" class="updateAvatar">Nom du fichier</th>
                        <th scope="col" class="updateAvatar">Image</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($avatarsList as $avatar) {
                        $id = substr($avatar, 6, -4) ?>
                        <tr id="avatar<?= $id ?>">
                            <th scope="row"><?= $id ?></th>
                            <td class="updateAvatar align-middle"><?= $avatar ?></td>
                            <td class="updateAvatar align-middle"><img src="/assets/img/avatars/avatar<?= $id ?>.png" alt="avatar1"></td>
                            <td class="updateAvatar align-middle">
                                <button type="submit" class="btn btn-danger" value="delete" name="delete" onclick="deleteAvatar(<?= $id ?>)"><i class="fas fa-trash"></i><br>Supprimer</button>
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
        <script src="/assets/js/adminAvatars.js"></script>
    <?php } ?>
    <script>
        const IMG_MAX_FILE_SIZE = <?= Cfg::get('imgMaxFileSize') ?>;
        const IMG_MIMES = <?= json_encode(Cfg::get('imgAllowedMimeTypes'), JSON_UNESCAPED_SLASHES) ?>;
    </script>
</body>

</html>