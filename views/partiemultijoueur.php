<?php

require_once 'inc/head.php';

use entities\User;
?>

<body>
    <div class="se-pre-con"></div>
    <header>
        <?php require_once 'inc/nav.php' ?>
    </header>
    <h1><?= $title ?></h1>
    <main class="container">
        <?php if (isset($errors)) { ?>
            <p class="message" data="<?= $data ?>"><?= implode('', $errors ?? []) ?></p>
        <?php } ?>
        <p class="message" data=""></p>
        <?php if (User::getUserSession()) { ?>
            <div class="row">
                <div class="col-2" id="connected">
                    <table class="table table-dark">
                        <thead>
                            <tr>
                                <th scope="col" class="">En ligne</th>
                            </tr>
                        </thead>
                        <tbody class="onlineUsers">

                        </tbody>
                    </table>
                </div>
                <div class="col-8" id="chat">
                    <div class="card">
                        <div class="card-header">Chat</div>
                        <div class="card-body height3">
                            <ul class="chat-list"></ul>
                        </div>
                    </div>
                    <div>
                        <form id="formChat">
                            <input type="text" class="form-control" name="content" id="message" required placeholder="Votre message...">
                            <button type="submit" id="chatButton" class="btn btn-primary col-sm-2 col-form-button"><i class="far fa-paper-plane"></i></button>
                        </form>
                        <small id="textAlert" class="form-text text-danger"></small>
                    </div>
                </div>
            </div>
        <?php } ?>
    </main>
    <footer>
        <?php require_once 'inc/footer.php' ?>
    </footer>
    <?php if (User::getUserSession()) { ?>
        <script src="/assets/js/Online.js"></script>
        <script src="/assets/js/LaunchBattle.js"></script>
        <script src="/assets/js/Chat.js"></script>
        <script src="/assets/js/appCom.js"></script>
        <script src="/assets/js/appLaunchBattle.js"></script>
    <?php } ?>
</body>

</html>