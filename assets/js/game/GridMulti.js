class GridMulti extends Grid {
    constructor(player, gridNumber, gameEvent) {
        super(player, gridNumber, gameEvent);
    }


    /**
     * Gère l'attaque 1. Bombardement avec animation.
     * @param {object} grid Instance de Grid
     * @param {HTMLElement} HTMLElement Elément HTML bouton power1 du jeu.
     */
    attackPower1(grid) {
        let newTab = [];
        //On crée un tableau avec toutes les cases non ciblées disponibles
        grid.gridHTML.forEach(e => {
            if (e.getAttribute('statut') === null)
                newTab.push(e.getAttribute('case'));
        })
        //On crée un tableau avec 3 case aléatoires provenant du tableau newTab
        while (this.targetsPower1.length < 3) {
            let randCase = this.randCase(newTab)
            this.targetsPower1.push(randCase);
            newTab.splice(newTab.indexOf(randCase), 1);
        }
        if (!this.gameEvent.isGameOver()) {
            this.usePower1 = true;
            this.gameEvent.launchBombing(this.gridNumber);
            Battle.postTarget(this.targetsPower1.join(','), 'bombing');
            setInterval(() => {
                if (this.targetsPower1.length > 0) {
                    //Pour chaque cible définie dans this.targetsPower1, exécute le mécanisme attaque IA de game.js
                    let randCase = this.randCase(this.targetsPower1);
                    if (grid.getAttribute('boatName', randCase) !== null) {
                        this.gameEvent.touchBoat(grid, randCase, true);
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
        else if (this.gameEvent.isGameOver())
            GameEvent.infoGame("La partie est finie");
    }

    /**
     * Lance une attaque Power 2 qui cible plusieurs cible autour de la cible passée en paramètre.
     * @param {object} grid Instance de Grid
     * @param {string} target Nom de la cible sur la grille de jeu
     */
    attackPower2(gridOpponent, target) {
        this.targetsOfAttackPower2(gridOpponent, target);
        if (!this.gameEvent.isGameOver()) {
            this.usePower2 = true;
            setTimeout(() => {
                Battle.postTarget(this.targetsPower2.join(','), 'megaBomb');
                //Pour chaque cible définie dans this.targetsPower2, exécute le mécanisme attaque IA de game.js
                this.gameEvent.launchMegaBomb();
                this.targetsPower2.forEach(e => {
                    let randCase = e;
                    if (gridOpponent.getAttribute('boatName', randCase) !== null) {
                        this.gameEvent.touchBoat(gridOpponent, randCase, true);
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
                        this.gameEvent.score -= 15;
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
     * @param {object} gridOpponent Instance de Grid de l'adversaire IA. Inutile en multijoueur.
     * @param {HTMLElement} HTMLElement Elément HTML bouton power3 du jeu.
     */
    attackPower3(target, gridOpponent = null, HTMLElement) {
        Battle.postTarget(target, 'repair');
        let el = this.selectCaseHTML(target);
        el.innerHTML = '';
        HTMLElement.classList.remove('powerSelect');
        HTMLElement.classList.add('powerOff');
        el.removeAttribute('statut');
        let boat = this.findBoat(this.getAttribute('boatName', target));
        boat.count++;
        this.usePower3 = true;
        GameEvent.infoGame(`Réparation utilisée par ${this.player.name}`);
    }

    /**
     * Gère l'attaque 1. Bombardement avec animation.
     * @param {object} grid Instance de Grid
     * @param {HTMLElement} HTMLElement Elément HTML bouton power1 du jeu.
     */
    attackPower1Player2(gridOpponent, HTMLElement) {
        HTMLElement.classList.remove('powerOn');
        HTMLElement.classList.add('powerOff');
        this.usePower1 = true;
        this.gameEvent.launchBombing(this.gridNumber);
        setInterval(() => {
            if (!this.gameEvent.isGameOver() && this.targetsPower1.length > 0) {
                let randCase = this.randCase(this.targetsPower1);
                if (gridOpponent.getAttribute('boatName', randCase) !== null) {
                    setTimeout(() => this.gameEvent.touchBoat(gridOpponent, randCase), 200);
                    let boat = gridOpponent.findBoat(gridOpponent.getAttribute('boatName', randCase));
                    boat.count--;
                    //si le bateau est entièrement touché
                    if (!boat.count) {
                        setTimeout(() => boat.positions.forEach(position => this.gameEvent.downBoat(gridOpponent, position)), 200);
                        this.gameEvent.animationBoatDown(boat);
                        this.gameEvent.score -= 100;
                    }
                    //Si tous les bateaux sont coulés
                    if (gridOpponent.countBoatDown() === 5)
                        this.gameEvent.animationGameLose(gridOpponent);
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
     * @param {HTMLElement} HTMLElement Elément HTML bouton power2 du jeu.
     */
    attackPower2Player2(gridOpponent, HTMLElement) {
        HTMLElement.classList.remove('powerOn');
        HTMLElement.classList.add('powerOff');
        if (!this.gameEvent.isGameOver()) {
            this.usePower2 = true;
            this.gameEvent.launchMegaBomb();
            //Tant que le tableau des cibles n'est pas vide
            while (this.targetsPower2.length > 0) {
                let randCase = this.randCase(this.targetsPower2);
                if (gridOpponent.getAttribute('boatName', randCase) !== null) {
                    setTimeout(() => this.gameEvent.touchBoat(gridOpponent, randCase), 200);
                    let boat = gridOpponent.findBoat(gridOpponent.getAttribute('boatName', randCase));
                    boat.count--;
                    //si le bateau est entièrement touché
                    if (!boat.count) {
                        setTimeout(() => boat.positions.forEach(position => this.gameEvent.downBoat(gridOpponent, position)), 200);
                        this.gameEvent.animationBoatDown(boat);
                        this.gameEvent.score -= 100;
                    }
                    //Si tous les bateaux sont coulés
                    if (gridOpponent.countBoatDown() === 5)
                        this.gameEvent.animationGameLose(gridOpponent);
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
    attackPower3Player2(target, HTMLElement) {
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