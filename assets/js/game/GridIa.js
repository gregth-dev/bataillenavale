/**
 * Class GridIa utilisé par l'IA uniquement
 */
class GridIa extends Grid {
    /**
     * Constructeur de GridIa hérite de Grid
     * @param {Object} player Instance de player
     * @param {int} gridNumber Numero de la grille. Soit 1, soit 2. 
     * @param {Object} gameEvent Instance de Event 
     */
    constructor(player, gridNumber, gameEvent) {
        super(player, gridNumber, gameEvent);
        /**
         * Position aléatoire provenant des tableaux newTargets || targets || targetsOver
         */
        this.randPosition = "";
        /**
         * Grille de position aléatoire pour le cible de l'IA
         */
        this.randomGrid = this.setRandomGrid();
        /**
         * Tableau de nouvelles cibles déterminées d'après les positions enregistrées du bateau
         */
        this.newTargets = [];
        /**
         * Tableau des cibles autour de randposition
         */
        this.targets = [];
        /**
         * Tableau des cibles déjà ciblées
         */
        this.targetsOver = [];
        /**
         * Tableau des cibles touchées
         */
        this.listTouchPositions = [];
        /**
         * Fourni le nom du bateau qui sera réparé par l'IA
         */
        this.boatRepair = this.randCase(Boat.tabOfBoats(player));
        /**
         * Chiffre random pour lancer l'exécution de power1
         */
        this.startPower1 = Math.round(Math.random() * 17);
        /**
         * Chiffre random pour lancer l'exécution de power2
         */
        this.startPower2 = Math.round(Math.random() * 17);

    }

    /**
     * Fourni une position aléatoire sur une grille en fonction des positions identifiées comme touchées ou loupées
     * et les positions qui n'ont pas encore été ciblées
     * @param {Object} grid Instance de Grid
     */
    chooseTarget(grid) {
        /*console.log('targets : ', grid2.targets);
        console.log('newTargets : ', grid2.newTargets);
        console.log('targetsOver : ', grid2.targetsOver);
        console.log('listTouchPositions : ', grid2.listTouchPositions); */
        if (this.newTargets.length > 0) {
            this.newTargets = this.newTargets.filter(e => !this.targetsOver.includes(e));
            this.randPosition = grid.randCase(this.newTargets);
            this.targetsOver.push(this.randPosition);
            this.newTargets = this.newTargets.filter(e => e !== this.randPosition);
            this.targets = this.targets.filter(e => e !== this.randPosition);
            this.randomGrid = this.randomGrid.filter(e => e !== this.randPosition);
            this.gameGrid = this.gameGrid.filter(e => e !== this.randPosition);
        }
        else if (this.targets.length > 0) {
            this.targets = this.targets.filter(e => !this.targetsOver.includes(e));
            this.randPosition = grid.randCase(this.targets);
            this.targetsOver.push(this.randPosition);
            this.targets = this.targets.filter(e => e !== this.randPosition);
            this.randomGrid = this.randomGrid.filter(e => e !== this.randPosition);
            this.gameGrid = this.gameGrid.filter(e => e !== this.randPosition);
        }
        else if (this.randomGrid.length > 0) {
            this.randomGrid = this.randomGrid.filter(e => !this.targetsOver.includes(e));
            this.randPosition = grid.randCase(this.randomGrid);
            this.targetsOver.push(this.randPosition);
            this.randomGrid = this.randomGrid.filter(e => e !== this.randPosition);
            this.gameGrid = this.gameGrid.filter(e => e !== this.randPosition);
        }
        else {
            this.gameGrid = this.gameGrid.filter(e => !this.targetsOver.includes(e));
            this.randPosition = grid.randCase(this.gameGrid);
            this.targetsOver.push(this.randPosition);
            this.gameGrid = this.gameGrid.filter(e => e !== this.randPosition);
        }
        return this.randPosition;
    }

    /**
     * Défini une position puis l'ajoute au tableau targets
     * @param {string} target Position de la grille
     */
    defineNewTarget(target) {
        this.newTargetsPosition(target);
        this.targets = this.targets.filter(e => !this.targetsOver.includes(e));
    }

    /**
     * Défini un nouveau tableau de position basé sur les positions du bateau
     * @param {Object} boat Instance de Boat
     */
    defineNewTargetsTab(boat) {
        this.newTargets = boat.positions;
        this.newTargets = this.newTargets.filter(e => !this.targetsOver.includes(e));
    }

    /**
     * Fourni un tableau aléatoire pourles attaque IA
     */
    setRandomGrid() {
        let tab = this.tab;
        let randNumber = Math.round(Math.random());
        if (randNumber) {
            let tempTab1 = [], tempTab2 = [];
            for (let j = 1; j <= 10; j += 2)
                for (let i = 0; i < tab.length; i += 2)
                    tempTab1.push(tab[i] + j)
            for (let j = 2; j <= 10; j += 2)
                for (let i = 1; i < tab.length; i += 2)
                    tempTab2.push(tab[i] + j)
            return tempTab1.concat(tempTab2);
        }
        else {
            let tempTab1 = [], tempTab2 = [];
            for (let j = 2; j <= 10; j += 2)
                for (let i = 0; i < tab.length; i += 2)
                    tempTab1.push(tab[i] + j)
            for (let j = 1; j <= 10; j += 2)
                for (let i = 1; i < tab.length; i += 2)
                    tempTab2.push(tab[i] + j)
            return tempTab1.concat(tempTab2);
        }
    }

    /**
     * Efface les anciennes cibles et défini les nouvelles cibles
     * @param {object} gridOpponent Instance de Grid de l'adversaire
     */
    resetTargets(gridOpponent) {
        this.newTargets = [];
        this.targets = [];
        for (const position of this.listTouchPositions) {
            if (gridOpponent.getAttribute('statut', position) === 'touch')
                this.defineNewTarget(position);
        }
    }

    /**
     * Gère l'attaque 1. Bombardement avec animation.
     * @param {object} grid Instance de Grid
     * @param {HTMLElement} HTMLElement Elément HTML bouton power1 du jeu.
     */
    attackPower1(gridOpponent, HTMLElement) {
        HTMLElement.classList.remove('powerOn');
        HTMLElement.classList.add('powerOff');
        this.usePower1 = true;
        while (this.targetsPower1.length < 3) {
            this.targetsPower1.push(this.chooseTarget(gridOpponent));
        }
        this.gameEvent.launchBombing(this.gridNumber);
        setInterval(() => {
            if (!this.gameEvent.isGameOver() && this.targetsPower1.length > 0) {
                let randCase = this.randCase(this.targetsPower1);
                if (gridOpponent.getAttribute('boatName', randCase) !== null) {
                    this.listTouchPositions.push(randCase);
                    setTimeout(() => this.gameEvent.touchBoat(gridOpponent, randCase, false, true), 200);
                    let boat = gridOpponent.findBoat(gridOpponent.getAttribute('boatName', randCase));
                    boat.count--;
                    //si le bateau est touché 2 fois on crée un nouveau tableau de cible prioritaire
                    if ((boat.length - boat.count) === 2)
                        this.defineNewTargetsTab(boat);
                    if (!this.newTargets.length)
                        this.defineNewTarget(randCase);
                    //si le bateau est entièrement touché
                    if (!boat.count) {
                        setTimeout(() => boat.positions.forEach(position => {
                            this.gameEvent.downBoat(gridOpponent, position);
                            this.resetTargets(gridOpponent);
                        }), 200);
                        this.gameEvent.animationBoatDown(boat);
                        this.gameEvent.score -= 100;
                    }
                    //Si tous les bateaux sont coulés
                    if (gridOpponent.countBoatDown() === 5)
                        this.gameEvent.animationGameLose(this);
                    this.player.notAttack();
                }
                else {
                    setTimeout(() => this.gameEvent.missBoat(gridOpponent, randCase), 200);
                    this.player.notAttack();
                }
                this.targetsPower1.splice(this.targetsPower1.indexOf(randCase), 1);
            }
        }, 500);
        GameEvent.infoGame(`Bombardement lancé par ${this.player.name}`);
    }

    /**
     * Lance une attaque Power 2 qui cible plusieurs cible autour de la cible passée en paramètre.
     * @param {object} gridOpponent Instance de Grid de l'adversaire
     * @param {string} target Nom de la cible sur la grille de jeu
     * @param {HTMLElement} HTMLElement Elément HTML bouton power2 du jeu.
     */
    attackPower2(gridOpponent, target, HTMLElement) {
        this.targetsOfAttackPower2(gridOpponent, target);
        HTMLElement.classList.remove('powerOn');
        HTMLElement.classList.add('powerOff');
        //ajoute la cible random de l'IA au tableau
        this.targetsPower2.push(target);
        if (!this.gameEvent.isGameOver()) {
            this.targetsPower2.forEach(e => this.targetsOver.push(e));
            this.usePower2 = true;
            this.gameEvent.launchMegaBomb();
            //Tant que le tableau des cibles n'est pas vide
            while (this.targetsPower2.length > 0) {
                let randCase = this.randCase(this.targetsPower2);
                if (gridOpponent.getAttribute('boatName', randCase) !== null) {
                    this.listTouchPositions.push(randCase);
                    this.newTargets.push(randCase);
                    setTimeout(() => this.gameEvent.touchBoat(gridOpponent, randCase), 200);
                    let boat = gridOpponent.findBoat(gridOpponent.getAttribute('boatName', randCase));
                    boat.count--;
                    //si le bateau est touché 2 fois on crée un nouveau tableau de cible prioritaire
                    if ((boat.length - boat.count) === 2)
                        this.defineNewTargetsTab(boat);
                    if (!this.newTargets.length)
                        this.defineNewTarget(randCase);
                    //si le bateau est entièrement touché
                    if (!boat.count) {
                        setTimeout(() => boat.positions.forEach(position => {
                            this.gameEvent.downBoat(gridOpponent, position);
                            this.resetTargets(gridOpponent);
                        }), 200);
                        this.gameEvent.animationBoatDown(boat);
                        this.gameEvent.score -= 100;
                    }
                    //Si tous les bateaux sont coulés
                    if (gridOpponent.countBoatDown() === 5)
                        this.gameEvent.animationEndGame('Vous avez perdu', this, 'ia-victory', 'gameOver');
                    this.player.notAttack();
                }
                else {
                    setTimeout(() => this.gameEvent.missBoat(gridOpponent, randCase), 200);
                    this.player.notAttack();
                }
                this.targetsPower2.splice(this.targetsPower2.indexOf(randCase), 1);
            }
            GameEvent.infoGame(`MégaBomb lancée par ${this.player.name}`);
        }
        else if (this.gameEvent.isGameOver())
            GameEvent.infoGame("La partie est finie");
    }

    /**
     * Permet d'utiliser le power3: remet les données à 0 concernant la case passé en paramètre
     * @param {string} target case ciblée
     * @param {HTMLElement} HTMLElement Elément HTML bouton power3 du jeu.
     */
    attackPower3(target, HTMLElement) {
        HTMLElement.classList.remove('powerOn');
        HTMLElement.classList.add('powerOff');
        let el = this.selectCaseHTML(target);
        el.innerHTML = '';
        el.removeAttribute('statut');
        el.classList.remove('cible');
        el.classList.add('hidden');
        let boat = this.findBoat(this.getAttribute('boatName', target));
        boat.count++;
        this.usePower3 = true;
        GameEvent.infoGame(`Réparation utilisée par ${this.player.name}`);
    }
}