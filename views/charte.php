<?php require_once 'inc/head.php' ?>

<body>
    <div class="se-pre-con"></div>
    <header>
        <?php require_once 'inc/nav.php' ?>
    </header>
    <main class="container flex-around">
        <section class="charte">
            <h1><?= $title ?></h1>
            <p>Respecter les points suivants sinon votre compte sera supprimé :</p>
            <p>Conditions d’utilisation du site Bataille Navale Généralités</p>
            <p>Nous rappelons que l'auteur d'un message est responsable des propos qu'il publie. En cas de manquement grave à la charte ou des lois et règlements en vigueur en France, ce dernier s'expose à une suppression de ses messages, voire de son compte, ainsi qu'aux sanctions civiles ou pénales afférentes.</p>
            <p>gregorythorel.alwaysdata.net ne peut être tenu pour responsable quant à l'agissement de certains membres du site. Si vous trouvez des messages hors-charte vous pouvez nous les signaler via le formulaire de contact.</p>
            <p>En vous inscrivant sur le site de gregorythorel.alwaysdata.net, vous autorisez les administrateurs du site à supprimer votre compte pour n'importe quelles raisons notamment exposées dans cette charte, sans autorisation préalable de votre part.
                Contenus non autorisés</p>
            <p>- Les messages à caractères pornographique et pédopornographique.</p>
            <p>- Les messages racistes, xénophobes, révisionnistes, faisant l'apologie de crime de guerre, discriminant ou incitant à la haine qu’elle soit à l'encontre d'une personne, d'un groupe de personnes en raison de leurs origines, leur ethnie, leurs croyances ou leur mode de vie.</p>
            <p>- Les messages à caractère insultants, violents, menaçants, au contenu choquant ou portant atteinte à la dignité humaine.</p>
            <p>- Les messages diffamatoires.</p>
            <p>- Les messages bafouant le droit d'auteur, le droit à l'image et le respect à la vie privée.</p>
            <p>- Les messages dans le but de nuire au forum tel que le spam ou bien ceux générant une mauvaise ambiance ou un mauvais esprit.</p>
            <p> - Les liens de parrainages et les publicités, qu'elles soient commerciales ou non. La mention de professionnel (ex : courtier) peut-être indiqué dans les messages, mais aucun lien ou caractère promotionnel ne peut être inséré.</p>
            <p>- Les démarchages, de manière générale, sont interdits et peuvent conduire à la suppression du compte.</p>
            <p>- Et de manière plus générale, tous les messages contraires aux lois en vigueur en France.</p>
            <p>Pour toutes questions liées aux fonctionnement du site ou concernant d'éventuels problèmes rencontrés merci me contacter.</p>
        </section>
    </main>
    <?php require_once 'inc/footer.php' ?>
    <?php if (entities\User::getUserSession()) {
        require_once 'inc/userLinksJS.php';
    } ?>
</body>

</html>