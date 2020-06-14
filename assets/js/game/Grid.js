class Grid {
    /**
     * Constructeur de Grid
     * @param {Object} player Instance de player
     * @param {int} gridNumber Numero de la grille. Soit 1, soit 2. 
     * @param {object} gameEvent Instance de gameEvent. 
     */
    constructor(player, gridNumber, gameEvent) {
        this.player = player;
        this.gridNumber = gridNumber;
        this.gameEvent = gameEvent;
        this.listBoat = []; //Tableau des bateaux installés
        this.boatPositions = []; //Tableau des positions occupées par les bateaux
        this.leftLimit = 65;//position de la 1ere lettre de la grilleIa converti en ASCII
        this.rightLimit = 74;//position de la dernière lettre de la grilleIa converti en ASCII
        this.tab = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J"];
        this.number = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        /**
         * Tableau contenant les 100 positions possible de la grille
         */
        this.gameGrid = this.setGrid();
        /**
         * Grille HTML du joueur
         */
        this.gridHTML = document.querySelectorAll(`table[grid="${this.player.name}"] td`);
        this.gridHTML.forEach(e => e.setAttribute('player', `${this.player.name}`));
        /**
         * Tableau des cibles du pouvoir1 (bombardement).
         */
        this.targetsPower1 = [];
        /**
         * Tableau des cibles du pouvoir2 (mégabomb).
         */
        this.targetsPower2 = [];
        /**
         * Défini si oui ou non le pouvoir 1 a été utilisé.
         */
        this.usePower1 = false;
        /**
         * Défini si oui ou non le pouvoir 2 a été utilisé.
         */
        this.usePower2 = false;
        /**
         * Défini si oui ou non le pouvoir 3 a été utilisé.
         */
        this.usePower3 = false;
        /**
        * Option si la grille est réduite.
        */
        this.reduce = false;

    }

    /**
     * Construit un tableau de position
     */
    setGrid() {
        let grid = [];
        for (const letter of this.tab)
            for (const number of this.number)
                grid.push(`${letter}${number}`)
        return grid;
    }

    /**
     * Retourne un élement <td> HTML
     * @param {string} position nom de la case à ciblée ex: A10
     * @return HTMLElement
     */
    selectCaseHTML(position) {
        return document.querySelector(`table[grid="${this.player.name}"] td[case="${position}"]`);
    }

    /**
     * Ajoute un Boat au tableau des bateaux de la grille
     * @param {Object} boat Instance de Boat
     */
    addBoat(boat) {
        this.listBoat.push(boat);
    }

    /**
     * Recherche une correspondance dans le tableau listBoat
     * @param {string} boatName Nom du bateau
     * @return Boat: object
     */
    findBoat(boatName) {
        for (const boat of this.listBoat)
            if (boat.name === boatName)
                return boat;
    }

    /**
     * Compte le nombre de bateaux installés sur la grille
     * Retourne true si 5.
     */
    countBoatOk() {
        return this.listBoat.length === 5;
    }

    /**
     * Compte le nombre de bateaux coulés
     */
    countBoatDown() {
        let count = 0;
        for (const boat of this.listBoat)
            if (!boat.count)
                count++;
        return count;
    }

    /**
     * Supprime un bateau du tableau listBoat
     * @param {Object} boat Instance de Boat
     */
    deleteBoat(boat) {
        this.listBoat.splice(this.listBoat.indexOf(boat), 1);
        for (const e of boat.positions)
            this.boatPositions.splice(this.boatPositions.indexOf(e), 1);
    }

    /**
     * Affecte à un bateau ses positions et sa direction
     * Ajoute au tableau boatPositions les positions du bateau
     * @param {Object} boat Instance de Boat
     * @param {int} direction Direction du bateau: 1 = verticale, 2 = horizontale
     * @param  {...any} positions Positions du bateau. ex: A4,A5,A6...
     */
    addPositions(boat, direction, ...positions) {
        this.listBoat.forEach(e => {
            if (e.name === boat.name) {
                boat.positions.push(...positions);
                boat.direction = direction;
                this.boatPositions.push(...positions);
            }
        });
    }

    /**
     * Retourne true si le bateau est présent dans listBoat
     * @param {Object} boat Instance de Boat
     * @return bool
     */
    existBoat(boat) {
        for (const e of this.listBoat)
            if (e.name === boat.name)
                return true;
    }

    /**
     * Retourne un élément aléatoire d'un tableau
     * @param {array} tab Tableau de positions
     * @return void
     */
    randCase(tab) {
        let rand = Math.ceil(Math.random() * tab.length - 1); //
        return tab[rand];
    }

    /**
     * Positionne un bateau à la verticale
     * @param {Object} boat Instance de Boat
     * @param {string} startCase Position de départ
     */
    downManual(boat, startCase) {
        let startLetter = startCase[0].toUpperCase(), positions = [];//renvoi le 1er et le 2eme caractère de startCase
        let startNumber = startCase.length < 3 ? startCase[1] : startCase[1] + startCase[2];
        for (let i = 0; i < boat.length; i++) {
            let nextNumber = parseInt(startNumber) + i;//a chaque tour de boucle ajoute 1 et converti startNumber en nombre
            if (nextNumber > 0 && nextNumber < 11) {
                if (this.boatPositions.indexOf(startLetter + nextNumber) !== -1) {
                    this.addPositions(boat, 1, ...positions);
                    return false;
                }
                positions.push(startLetter + nextNumber);
            }
            else {
                this.addPositions(boat, 1, ...positions);
                return false;
            }
        }
        this.addPositions(boat, 1, ...positions);
        return true;
    }

    /**
     * Positionne un bateau à l'horizontale
     * @param {Object} boat Instance de Boat
     * @param {string} startCase Position de départ
     */
    horizontalManual(boat, startCase) {
        let nextStartLetter = startCase[0].toUpperCase().charCodeAt(0), positions = [];
        let startNumber = startCase.length < 3 ? startCase[1] : startCase[1] + startCase[2];
        for (let i = 0; i < boat.length; i++) {
            let nextLetter = nextStartLetter + i;//ajoute 1 au format ASCII de la lettre de départ => décale vers la droite
            if (nextLetter >= this.leftLimit && nextLetter <= this.rightLimit) { //test si on est dans la surface de la grille de jeu
                //recherche si des bateaux sont deja présents sur les case sélectionnées si oui, on annule le positionnement et on affiche un message d'erreur
                if (this.boatPositions.indexOf(String.fromCharCode(nextLetter) + startNumber) !== -1) {
                    this.addPositions(boat, 2, ...positions);
                    return false;
                }
                positions.push(String.fromCharCode(nextLetter) + startNumber);
            }
            else {
                this.addPositions(boat, 2, ...positions);
                return false;
            }
        }
        this.addPositions(boat, 2, ...positions);
        return true;
    }

    /**
     * Installe les bateaux aléatoirement sur la grille de jeu
     */
    installBoatAuto() {
        for (const boat of Boat.tabOfBoats(this.player)) {
            this.addBoat(boat);
            let randomPosition = Math.round(Math.random() * 1) + 1;
            if (randomPosition === 1) {
                let positions = this.downAuto(boat);
                this.addPositions(boat, 1, ...positions);
            } else {
                let positions = this.horizontalAuto(boat);
                this.addPositions(boat, 2, ...positions);
            }
        }
    }

    /**
     * Positionne aléatoirement un bateau à la verticale
     * @param {Object} boat Instance de Boat
     * @param {string} startCase Position de départ aléatoire
     */
    downAuto(boat) {
        let positions = [];//tableau de positions vide
        while (positions.length < boat.length) {
            let startCase = this.randCase(this.gameGrid);//startCase renvoi la case de départ du positionnement du bateau
            positions = [];
            let startLetter = startCase[0].toUpperCase();//renvoi le 1er et le 2eme caractère de startCase
            let startNumber = startCase.length < 3 ? startCase[1] : startCase[1] + startCase[2];
            for (let i = 0; i < boat.length; i++) {
                let nextNumber = parseInt(startNumber) + i;//a chaque tour de boucle ajoute 1 et converti startNumber en nombre
                if (nextNumber > 0 && nextNumber < 11)
                    if (this.boatPositions.indexOf(startLetter + nextNumber) !== -1)
                        break;
                    else
                        positions.push(startLetter + nextNumber);
            }
        }
        return positions;
    }

    /**
     * Positionne aléatoirement un bateau à l'horizontale
     * @param {Object} boat Instance de Boat
     * @param {string} startCase Position de départ aléatoire
     */
    horizontalAuto(boat) {
        let positions = [];//tableau de positions vide
        while (positions.length < boat.length) {
            let startCase = this.randCase(this.gameGrid);//startCase renvoi la case de départ du positionnement du bateau
            positions = [];
            let startLetter = startCase[0].toUpperCase(), nextStartLetter = startLetter.charCodeAt(0);//renvoi le code ASCII du 1er caractère de startCase
            let startNumber = startCase.length < 3 ? startCase[1] : startCase[1] + startCase[2];
            for (let i = 0; i < boat.length; i++) {
                let nextLetter = nextStartLetter + i;//ajoute 1 a chaque tour de boucle 
                if (nextLetter >= this.leftLimit && nextLetter <= this.rightLimit)
                    if (this.boatPositions.indexOf(String.fromCharCode(nextLetter) + startNumber) !== -1)
                        break;
                    else
                        positions.push(String.fromCharCode(nextLetter) + startNumber);
            }
        }
        return positions;
    }

    /**
     * Retire les attributs HTML de la case indiquée en paramètre
     * @param {case de la grille} caseName ex: A10
     * @param  {...any} attributs Liste des attributs
     */
    removeAttributes(caseName, ...attributs) {
        attributs.forEach(attribut => this.selectCaseHTML(caseName).removeAttribute(attribut));
    }

    /**
     * Gère l'affichage dans la grille lorsque le bateau n'est pas validé en position auto.
     * @param {instance de Boat} boat 
     */
    invalidPosition(boat) {
        let i = 0;
        for (const position of boat.positions) {
            this.reduce ? this.selectCaseHTML(position).classList.add(boat.name + (i++) + '-' + boat.direction + 'reduce') : this.selectCaseHTML(position).classList.add(boat.name + (i++) + '-' + boat.direction);
            setTimeout(() => {
                this.removeAttributes(position, 'class', 'boatName');
                if (this.reduce)
                    this.selectCaseHTML(position).classList.add('reduce');
            }, 1000)
        }
        this.deleteBoat(boat);
    }

    /**
     * Gère l'affichage dans la grille lorsque le bateau n'est pas validé
     * @param {instance de Boat} boat 
     */
    invalidPositionManual(boat) {
        let i = 0;
        for (const position of boat.positions) {
            this.selectCaseHTML(position).classList.add(boat.name + (i++) + '-' + boat.direction);
            this.removeAttributes(position, 'class', 'boatName');
            if (this.reduce)
                this.selectCaseHTML(position).classList.add('reduce');
        }
        this.deleteBoat(boat);
    }

    /**
     * Affiche le bateau sur la grille lorsque celui-ci est validé
     * @param {instance de Boat} boat 
     * @param {bool} hidden Indique si le bateau est masqué avec true ou non avec false
     */
    validPosition(boat, hidden = false) {
        let i = 0;
        for (const position of boat.positions) {
            if (hidden)
                this.selectCaseHTML(position).setAttribute("class", "hidden");
            this.reduce ? this.selectCaseHTML(position).classList.add(boat.name + (i++) + '-' + boat.direction + 'reduce') : this.selectCaseHTML(position).classList.add(boat.name + (i++) + '-' + boat.direction);
            this.selectCaseHTML(position).setAttribute("boatName", boat.name);
        }
    }

    /**
     * Affiche le bateau sur la grille lorsque celui-ci est validé
     * @param {instance de Boat} boat 
     * @param {bool} hidden Indique si le bateau est masqué avec true ou non avec false
     */
    removeReducePosition(boat, hidden = false) {
        let i = 0;
        for (const position of boat.positions) {
            if (hidden)
                this.selectCaseHTML(position).removeAttribute("class", "hidden");
            this.selectCaseHTML(position).classList.remove(boat.name + (i++) + '-' + boat.direction + 'reduce');
            this.selectCaseHTML(position).removeAttribute("boatName", boat.name);
        }
    }

    /**
     * Affiche sur la grille de jeu chaque bateau contenu dans listBoat.
     * @param {boo} hidden Indique si le bateau est masqué avec true ou non avec false
     */
    removeReducePositionAuto(hidden = false) {
        for (const boat of this.listBoat)
            this.removeReducePosition(boat, hidden);
    }

    /**
     * Affiche sur la grille de jeu chaque bateau contenu dans listBoat.
     * @param {boo} hidden Indique si le bateau est masqué avec true ou non avec false
     */
    validPositionAuto(hidden = false) {
        for (const boat of this.listBoat)
            this.validPosition(boat, hidden);
    }

    /**
     * Reset les positions des bateaux présent sur la grille du joueur
     */
    reset() {
        this.listBoat.forEach(boat => {
            boat.positions.forEach(position => {
                this.removeAttributes(position, 'class', 'boatName', 'type');
                if (this.reduce)
                    this.selectCaseHTML(position).classList.add('reduce');
            });
        })
        this.listBoat = [];
        this.boatPositions = [];
    }

    /**
     * Renvoie la valeur de l'attribut passé en paramètre en fonction de la position
     * @param {string} attribut Nom de l'attribut
     * @param {string} position Nom de la case ex: A10
     * @return string
     */
    getAttribute(attribut, position) {
        return this.selectCaseHTML(position).getAttribute(attribut);
    }

    /**
     * Cible la position a droite de la position passée en paramètre
     * @param {string} target Position
     */
    rightAttack(target) {
        let startLetter = target[0];
        let startNumber = target.length > 2 ? target[1] + target[2] : target[1];
        let nextLetter = startLetter.charCodeAt(0) + 1
        return nextLetter <= 74 ? String.fromCharCode(nextLetter) + startNumber : false;
    }

    /**
     * Cible la position a gauche de la position passée en paramètre
     * @param {string} target Position
     */
    leftAttack(target) {
        let startLetter = target[0];
        let startNumber = target.length > 2 ? target[1] + target[2] : target[1];
        let nextLetter = startLetter.charCodeAt(0) - 1
        return nextLetter >= 65 ? String.fromCharCode(nextLetter) + startNumber : false;
    }

    /**
     * Cible la position en bas de la position passée en paramètre
     * @param {string} target Position
     */
    bottomAttack(target) {
        let startLetter = target[0];
        let startNumber = target.length > 2 ? target[1] + target[2] : target[1];
        let nextNumber = parseInt(startNumber);
        nextNumber++;
        return nextNumber <= 10 ? startLetter + nextNumber.toString() : false;
    }

    /**
     * Cible la position en haut de la position passée en paramètre
     * @param {string} target Position
     */
    topAttack(target) {
        let startLetter = target[0];
        let startNumber = target.length > 2 ? target[1] + target[2] : target[1];
        let nextNumber = parseInt(startNumber);
        nextNumber--;
        return nextNumber > 0 ? startLetter + nextNumber.toString() : false;
    }

    /**
     * Ajoute une position au tableau targets si elle respecte les conditions :
     * existante, pas touchée, pas loupée5
     * @param {string} target Position
     */
    getNewTargets(target) {
        if (target !== false && this.selectCaseHTML(target).classList !== 'touch' && this.selectCaseHTML(target).classList !== 'miss') {
            this.targets.push(target);
        }
    }

    /**
     * Détermine les cibles possible à partir de la position passée en paramètre
     * a gauche, a droite, en bas, en haut 
     * @param {string} position Position
     */
    newTargetsPosition(position) {
        this.getNewTargets(this.leftAttack(position));
        this.getNewTargets(this.rightAttack(position));
        this.getNewTargets(this.bottomAttack(position));
        this.getNewTargets(this.topAttack(position));
    }

    /**
     * Gère l'attaque 1. Bombardement avec animation.
     * @param {object} grid Instance de Grid
     */
    attackPower1(grid) {
        let newTab = [];
        grid.gridHTML.forEach(e => {
            if (e.getAttribute('statut') === null)
                newTab.push(e.getAttribute('case'));
        })
        while (this.targetsPower1.length < 3) {
            this.targetsPower1.push(this.randCase(newTab));
        }
        if (!this.gameEvent.isGameOver()) {
            this.usePower1 = true;
            this.gameEvent.launchBombing(this.gridNumber);
            setInterval(() => {
                if (this.targetsPower1.length > 0) {
                    //Pour chaque cible définie dans this.targetsPower1, exécute le mécanisme attaque IA de game.js
                    let randCase = this.randCase(this.targetsPower1);
                    if (grid.getAttribute('boatName', randCase) !== null) {
                        this.gameEvent.touchBoat(grid, randCase, true);
                        setTimeout(() => {
                            if (!grid.usePower3 && boat.name === grid.boatRepair.name)
                                grid.attackPower3(randCase);
                        }, 3000)
                        let boat = grid.findBoat(grid.getAttribute('boatName', randCase));
                        boat.count--;
                        if (!boat.count) {
                            for (const position of boat.positions)
                                this.gameEvent.downBoat(grid, position);
                            this.gameEvent.animationBoatDown(boat);
                            this.gameEvent.score += boat.setPoint();
                            this.gameEvent.getScore(this.gameEvent.score);
                            if (grid.countBoatDown() === 5) {
                                this.gameEvent.saveScore();
                                this.gameEvent.animationGameWin();
                            }
                        }
                    }
                    else {
                        this.gameEvent.missBoat(grid, randCase);
                        this.gameEvent.score -= 15;
                    }
                    this.targetsPower1.splice(this.targetsPower1.indexOf(randCase), 1);
                }
            }, 1000);
            GameEvent.infoGame(`Bombardement lancé par ${this.player.name}`);
        }
        else if (gameEvent.isGameOver())
            GameEvent.infoGame("La partie est finie");
    }

    /**
     * Ajoute les cibles pour AttackPower2 au tableau targetsPower2
     * @param {object} gridOpponent Instance de Grid de l'adversaire
     * @param {string} target cible de départ
     */
    targetsOfAttackPower2(gridOpponent, target) {
        let target1, target2, target3, target4, target5, target6, target7, target8;
        if (target) {
            target1 = this.topAttack(target);
            this.targetsPower2.push(target1);
            target5 = this.bottomAttack(target);
            this.targetsPower2.push(target5);
            target3 = this.leftAttack(target);
            this.targetsPower2.push(target3);
            target7 = this.rightAttack(target);
            this.targetsPower2.push(target7);
        }
        if (target1) {
            target2 = this.leftAttack(target1);
            this.targetsPower2.push(target2);
        }
        if (target3) {
            target4 = this.bottomAttack(target3);
            this.targetsPower2.push(target4);
        }
        if (target5) {
            target6 = this.rightAttack(target5);
            this.targetsPower2.push(target6);
        }
        if (target7) {
            target8 = this.topAttack(target7);
            this.targetsPower2.push(target8);
        }
        let newTab = [];
        this.targetsPower2.forEach(e => {
            let el = gridOpponent.selectCaseHTML(e);
            if (el !== null) {
                let statut = el.getAttribute('statut');
                if (statut === null)
                    newTab.push(e);
            }
        });
        this.targetsPower2 = newTab;
    }

    /**
     * Lance une attaque Power 2 qui cible plusieurs cible autour de la cible passée en paramètre.
     * @param {object} grid Instance de Grid
     * @param {string} target Nom de la cible sur la grille de jeu
     * @param {HTMLElement} HTMLElement Elément HTML bouton power2 du jeu.
     */
    attackPower2(gridOpponent, target, HTMLElement) {
        this.targetsOfAttackPower2(gridOpponent, target);
        if (!this.gameEvent.isGameOver()) {
            this.usePower2 = true;
            setTimeout(() => {
                //Pour chaque cible définie dans this.targetsPower2, exécute le mécanisme attaque IA de game.js
                this.gameEvent.launchMegaBomb();
                this.targetsPower2.forEach(e => {
                    let randCase = e;
                    if (gridOpponent.getAttribute('boatName', randCase) !== null) {
                        this.gameEvent.touchBoat(gridOpponent, randCase, true);
                        setTimeout(() => {
                            if (!gridOpponent.usePower3 && boat.name === gridOpponent.boatRepair.name)
                                gridOpponent.attackPower3(randCase, HTMLElement);
                        }, 3000)
                        let boat = gridOpponent.findBoat(gridOpponent.getAttribute('boatName', randCase));
                        boat.count--;
                        if (!boat.count) {
                            for (const position of boat.positions)
                                this.gameEvent.downBoat(gridOpponent, position);
                            this.gameEvent.animationBoatDown(boat);
                            this.gameEvent.score += boat.setPoint();
                            this.gameEvent.getScore(this.gameEvent.score);
                            if (gridOpponent.countBoatDown() === 5) {
                                this.gameEvent.saveScore();
                                this.gameEvent.animationGameWin();
                            }
                        }
                    }
                    else {
                        this.gameEvent.missBoat(gridOpponent, randCase);
                        gameEvent.score -= 15;
                    }
                });
            }, 1000);
            GameEvent.infoGame(`MégaBomb lancée par ${this.player.name}`);
        }
        else if (this.gameEvent.isGameOver())
            GameEvent.infoGame("La partie est finie");
    }

    /**
     * Lance le power3 réparation qui annule la class Touch et l'explosion du bateau.
     * @param {string} target case ciblé
     * @param {object} gridOpponent Instance de Grid de l'adversaire
     * @param {HTMLElement} HTMLElement Elément HTML bouton power3 du jeu.
     */
    attackPower3(target, gridOpponent, HTMLElement) {
        let el = this.selectCaseHTML(target);
        el.innerHTML = '';
        HTMLElement.classList.remove('powerSelect');
        HTMLElement.classList.add('powerOff');
        el.removeAttribute('statut');
        gridOpponent.targetsOver.splice(gridOpponent.targetsOver.indexOf(target), 1);
        gridOpponent.newTargets.push(target);
        gridOpponent.targets.push(target);
        let boat = this.findBoat(this.getAttribute('boatName', target));
        boat.count++;
        this.usePower3 = true;
        GameEvent.infoGame(`Réparation utilisée par ${this.player.name}`);
    }
}