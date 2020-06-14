"use strict";

//Initialisation des variables.
let player1 = new Player(document.querySelector('table[id="player1"]').getAttribute('grid'));
let player2 = new Player(document.querySelector('table[id="player2"]').getAttribute('grid'));
let gameEvent = new GameEvent(10);
let grid1 = new Grid(player1, 1, gameEvent);
let grid2 = new GridIa(player2, 2, gameEvent);
let gameControl = new GameControl(gameEvent, grid1, grid2, player1, player2);
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
//Placement des bateaux IA.
grid2.installBoatAuto();
grid2.validPositionAuto(true);

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


//Actions lors du clic sur la grille du joueur2.
let gamePlay = evt => {
    //Remplace l'animation par une couleur
    document.querySelectorAll('[statut=miss]').forEach(element => {
        element.classList.remove('splash');
        element.classList.remove('splashReduce');
        element.classList.add('grey');
    });
    //Si le nombre de bateau sur la grille est égale à 5, si la partie n'est pas finie, si il n'y a pas d'attaque du joueur 2 en cours
    if (grid1.countBoatOk() && !gameEvent.isGameOver() && !player2.isAttack()) {
        let target = evt.target.attributes.case.value;
        gameEvent.startGame();
        gameEvent.launchMissile(grid1.gridNumber);
        //si la cible n'a jamais été ciblée
        if (grid2.getAttribute('statut', target) === null) {
            //si la cible contient un bateau
            if (grid2.getAttribute('boatName', target) !== null) {
                setTimeout(() => gameEvent.touchBoat(grid2, target, true), 1000);
                let boat = grid2.findBoat(grid2.getAttribute('boatName', target));
                boat.count--;
                //Réparation utilisé par IA s'il est disponible et si le nom du bateau correspond au bateau désigné au hasard
                setTimeout(() => {
                    if (!grid2.usePower3 && boat.name === grid2.boatRepair.name)
                        grid2.attackPower3(target, gameControl.HTMLElement.buttonPower3Player2);
                }, 3000);
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
                    }
                }
            }
            //sinon la cible est considérée loupée
            else {
                setTimeout(() => {
                    gameEvent.missBoat(grid2, target);
                    gameEvent.score -= 15;
                }, 1000);
            }
            //AttaqueIA aléatoire, si l'IA touche 2 fois le même bateau le bateau devient alors une cible unique jusqu'à qu'il coule.
            //Chaque attaque est mémorisée dans un tableau en fonction de son résultat (touch, miss).
            //Les cases déjà ciblées sont éliminées de la liste des cases disponibles.
            if (!gameEvent.isGameOver()) {
                player2.attack();
                //Lance l'attaque1 quand la variable est à 0 (sert de compte à rebours).
                if (!grid2.usePower1 && !grid2.startPower1)
                    grid2.attackPower1(grid1, gameControl.HTMLElement.buttonPower1Player2);
                //Lance l'attaque2 quand la variable est à 0 (sert de compte à rebours).
                if (!grid2.usePower2 && !grid2.startPower2)
                    grid2.attackPower2(grid1, grid2.chooseTarget(grid1), gameControl.HTMLElement.buttonPower2Player2);
                setTimeout(() => {
                    //On test si power1 ou power2 viennent d'être lancés si ce n'est pas le cas on lance launchMissile
                    //Défini une cible
                    let randCase = grid2.chooseTarget(grid1);
                    gameEvent.launchMissile(grid2.gridNumber);
                    //si la cible aléatoire contient un bateau
                    if (grid1.getAttribute('boatName', randCase) !== null) {
                        grid2.listTouchPositions.push(randCase);
                        setTimeout(() => gameEvent.touchBoat(grid1, randCase), 1000);
                        let boat = grid1.findBoat(grid1.getAttribute('boatName', randCase));
                        boat.count--;
                        //si le bateau est touché 2 fois on crée un nouveau tableau de cible prioritaire
                        if ((boat.length - boat.count) === 2)
                            grid2.defineNewTargetsTab(boat);
                        if (!grid2.newTargets.length)
                            grid2.defineNewTarget(randCase);
                        //si le bateau est entièrement touché on reset les tableaux et on crée un nouveau tableau de cibles si toutes les positions touchées ne sont pas coulées.
                        if (!boat.count) {
                            boat.positions.forEach(position => gameEvent.downBoat(grid1, position));
                            grid2.resetTargets(grid1);
                            gameEvent.animationBoatDown(boat);
                            gameEvent.score -= 100;
                        }
                        //Si tous les bateaux sont coulés
                        if (grid1.countBoatDown() === 5) {
                            //Annule l'écouteur de click sur la grille de jeu
                            grid2.gridHTML.forEach(element => element.removeEventListener('click', gamePlay))
                            gameEvent.animationGameLose(grid2);
                        }
                        player2.notAttack();
                    }
                    else {
                        setTimeout(() => gameEvent.missBoat(grid1, randCase), 1000);
                        player2.notAttack();
                    }
                }, 3000);
                grid2.startPower1--;
                grid2.startPower2--;
            }
        }
        else
            GameEvent.infoGame('La case a deja été ciblée');
    } else {
        if (gameEvent.isGameOver())
            GameEvent.infoGame('La partie est finie');
        if (player2.isAttack())
            GameEvent.infoGame('Ce n\'est pas votre tour');
        else
            GameEvent.infoGame('Il manque des bateaux');
    }
}
//Ecoute les clics sur la grille du joueur2. Provoque également les attaques IA.
grid2.gridHTML.forEach(element => element.addEventListener('click', gamePlay, false))


