<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Jeu Bataille Navale en ligne">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/velocity/0.11.9/jquery.velocity.min.js"></script>
    <link href='https://fonts.googleapis.com/css?family=Arvo:700' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Raleway:300,100' rel='stylesheet' type='text/css'>
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/img/favicon.png">
    <link rel="stylesheet" href="/assets/css/reset.css">
    <link rel="stylesheet" href="/assets/css/accueil.css">
    <link rel="stylesheet" href="/assets/css/bataillenavale.css">
    <title>Bataille Navale</title>
</head>

<body>
    <div class="container">
        <span class="orbs">
            <span>B</span>
            <span>a</span>
            <span>t</span>
            <span>a</span>
            <span>i</span>
            <span>l</span>
            <span>l</span>
            <span>e</span>
        </span>
        <span class="glow">
            NAVALE
        </span>
        <div class="logo">
            <img src="/assets/img/logo.png" alt="Logo">
        </div>
        <div class="boutonAccueil">
            <a href="/battle/gameOne" id="playGame"><button class="hvr-underline">Jouer</button></a>
            <?php if (entities\User::getUserSession()) { ?>
                <a href="/user/profil"><button class="hvr-underline">Profil</button></a>
                <a href="/user/logout"><button class="hvr-underline">Deconnexion</button></a>
            <?php } else {  ?>
                <a href="/user/connexion"><button class="hvr-underline">Connexion</button></a>
                <a href="/user/inscription"><button class="hvr-underline">Inscription</button></a>
            <?php } ?>
        </div>
    </div>
    <script src="/assets/js/accueil.js"></script>
</body>

</html>