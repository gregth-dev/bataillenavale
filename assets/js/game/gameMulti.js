"use strict";

//Initialisation des variables.
let player1 = new Player(document.querySelector('table[id="player1"]').getAttribute('grid'));
let player2 = new Player(document.querySelector('table[id="player2"]').getAttribute('grid'));
let gameEvent = new GameEvent(10);
let grid1 = new GridMulti(player1, 1, gameEvent);
let grid2 = new GridMulti(player2, 2, gameEvent);
let gameControl = new GameControl(gameEvent, grid1, grid2, player1, player2);
let idLaunchBattle = quitGame.getAttribute('data');
let battlePlayers = new Battle(grid1, grid2, idLaunchBattle, gameEvent, gameControl);
//Chargement du fond d'écran
gameEvent.backgroundImageChange();
//Affichage par défaut des bateaux du menu select
gameControl.selectedBoatDefault();
//Menu de sélection des bateaux
let selectBoat = () => gameControl.selectBoat();
//Bouton du son du jeu
gameControl.soundButtonControl();
//Bouton "Placement automatique des bateaux"
gameControl.autoPositionBoats();
//Evénements sur la touche SUPP du clavier
gameControl.deleteKeyDown();
gameControl.deleteKeyUp();
//Affiche un message d'info sur le clic de l'icon Del.
gameControl.deleteAlert();

/** BOUTONS SPECIAUX **/
//Lance le bombardement. Utilisable une seule fois
gameControl.controlPower1();
//Lance MégaBomb. Utilisable une seule fois
gameControl.controlPower2();
//Lance le pouvoir de réparation du joueur. Utilisable une seule fois
gameControl.controlPower3();
//Ecoute la partie et les actions en cours de l'adversaire.
setTimeout(() => setInterval(() => battlePlayers.getBattles(), 2000), 6000);

//Ecouteur sur les clics droit de la grille du joueur1
//Positionne les bateaux verticalement
grid1.gameGrid.forEach(element => {
    grid1.selectCaseHTML(element).addEventListener('click', evt => {
        let boat = selectBoat();
        if (!grid1.existBoat(boat)) {
            if (!grid1.countBoatOk() && !gameEvent.isStart()) {
                grid1.addBoat(boat);
                if (grid1.downManual(boat, evt.target.attributes.case.value))
                    grid1.validPosition(boat);
                else
                    grid1.invalidPosition(boat);
            }
            else
                if (grid1.countBoatOk())
                    GameEvent.infoGame('Nombre maximum de bateau atteint');
        } else
            GameEvent.infoGame(`Le ${boat.name} est déjà installé`);
    })
})

//Ecouteur sur les clics gauche de la grille du joueur1
//Positionne les bateaux horizontalement
grid1.gameGrid.forEach(element => {
    grid1.selectCaseHTML(element).addEventListener('contextmenu', evt => {
        evt.preventDefault();
        let boat = selectBoat();
        if (!grid1.existBoat(boat)) {
            if (!grid1.countBoatOk() && !gameEvent.isStart()) {
                grid1.addBoat(boat);
                if (grid1.horizontalManual(boat, evt.target.attributes.case.value))
                    grid1.validPosition(boat);
                else
                    grid1.invalidPosition(boat);
            }
            else
                if (grid1.countBoatOk())
                    GameEvent.infoGame('Nombre maximum de bateau atteint');
        } else
            GameEvent.infoGame(`Le ${boat.name} est déjà installé`);
    })
})

//Bouton pour réduire la grille de jeu du joueur.
gameControl.reduceGrid1();
//Bouton pour réduire la grille de jeu de l'adversaire.
gameControl.reduceGrid2();


//Envoie la position des bateaux à la BDD et enregistre que le joueur est prêt à jouer.
let readyButton = document.querySelector('.ready');
readyButton.addEventListener('click', () => {
    battlePlayers.postBoat();
});

//Lorsque l'on clique sur le bouton 'Quitter la partie'.
quitGame.addEventListener('click', () => {
    $.alert({
        title: 'Partie multijoueur',
        content: `Voulez-vous quitter la partie?`,
        theme: 'dark',
        type: 'red',
        columnClass: 'small',
        buttons: {
            ok: {
                text: "oui",
                keys: ['enter'],
                action: () => {
                    setTimeout(() => window.location.replace(`/battle/partieMultijoueur`), 1500);
                }
            },
            Annuler: () => { return; }
        }
    });
});

//Actions lors du clic sur la grille du joueur2
let gamePlay = evt => {
    //Remplace l'animation par une couleur
    document.querySelectorAll('[statut=miss]').forEach(element => {
        element.classList.remove('splash');
        element.classList.remove('splashReduce');
        element.classList.add('grey');
    });
    //Si le nombre de bateau sur la grille est égale à 5, si la partie n'est pas finie, si il n'y a pas d'attaque du joueur 2 en cours
    if (grid1.countBoatOk() && !gameEvent.isGameOver() && !player2.isAttack() && player2.isReady() && player1.isReady()) {
        let target = evt.target.attributes.case.value;
        gameEvent.startGame();
        gameEvent.launchMissile(grid1.gridNumber);
        //si la cible n'a jamais été ciblée
        if (grid2.getAttribute('statut', target) === null) {
            //si la cible contient un bateau
            if (grid2.getAttribute('boatName', target) !== null) {
                Battle.postTarget(target, 'touch');
                setTimeout(() => gameEvent.touchBoat(grid2, target, true), 1000);
                let boat = grid2.findBoat(grid2.getAttribute('boatName', target));
                boat.count--;
                if (!boat.count) {
                    for (const position of boat.positions)
                        setTimeout(() => gameEvent.downBoat(grid2, position), 1000);
                    gameEvent.animationBoatDown(boat);
                    gameEvent.score += boat.setPoint();
                    gameEvent.getScore(gameEvent.score);
                    if (grid2.countBoatDown() === 5) {
                        //Annule l'écouteur de click sur la grille de jeu
                        grid2.gridHTML.forEach(element => element.removeEventListener('click', gamePlay))
                        //Animation lorsque le joueur gagne.
                        gameEvent.animationGameWin();
                        //Propose la sauvegarde du score
                        gameEvent.saveScore(gameEvent.score);
                        Battle.postReady(3);
                    }
                }
            }
            //sinon la cible est considérée loupée
            else {
                Battle.postTarget(target, 'miss');
                setTimeout(() => {
                    gameEvent.missBoat(grid2, target);
                    gameEvent.score -= 15;
                }, 1000);
            }
            //AttaquePlayer.
            if (!gameEvent.isGameOver()) {
                player2.attack();

            }
        }
        else
            GameEvent.infoGame('La case a deja été ciblée.');
    } else {
        if (gameEvent.isGameOver())
            GameEvent.infoGame('La partie est finie.');
        else if (player2.isAttack())
            GameEvent.infoGame('Ce n\'est pas votre tour.');
        else if (!player2.isReady())
            GameEvent.infoGame('Votre adversaire n\'est pas prêt.');
        else if (!player1.isReady())
            GameEvent.infoGame('Cliquez sur le bouton "Je suis prêt!!!".');
        else if (!grid1.countBoatOk())
            GameEvent.infoGame('Il manque des bateaux');
    }
}
//Ecoute les clics sur la grille du joueur2. Provoque également les attaques IA.
grid2.gridHTML.forEach(element => element.addEventListener('click', gamePlay, false))


