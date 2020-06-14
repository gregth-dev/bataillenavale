/**
 * Class GameEvent gère l'affichage des événements du jeu.
 */
class GameEvent {
    constructor(numberOfBackgroundImage = 0) {
        this.start = false;
        this.over = false;
        this.score = 0;
        this.engage = false;
        this.stopSound = false;
        //Element HTML pour l'affichage
        this.divAnimation = document.querySelector("#animation");
        this.backGround = document.getElementById('gameGrid');
        this.messageInfo = document.querySelector('.message');
        /**
         * Nombre de background disponibles dans le dossier /assets/img/background
         */
        this.numberOfBackgroundImage = numberOfBackgroundImage;
    }

    /**
     * Change le fond d'écran aléatoirement en fonction du nombre de fond d'écran disponibles
     */
    backgroundImageChange() {
        if (this.numberOfBackgroundImage) {
            let randNumber = Math.round(Math.random() * (this.numberOfBackgroundImage - 1)) + 1;
            this.backGround.style.backgroundImage = `url(/assets/img/background/background${randNumber}.jpg)`;
        }
    }

    /**
     * Affiche un bateau en dessous du menu select
     * @param {string} boat Nom du bateau
     * @param {int} length Longueur du bateau
     */
    displaySelectBoat(boat, length) {
        let imgBoats = document.querySelector('#imgBoats');
        imgBoats.innerHTML = '';
        for (let i = 0; i < length; i++) {
            let imgBoat = document.createElement('img');
            imgBoat.src = `/assets/img/${boat}/${boat}${i}-2.png`;
            imgBoats.appendChild(imgBoat);
        }
    }

    /**
     * Passe le jeu au statut démarré
     */
    startGame() {
        this.start = true;
    }

    /**
     * Retoune true si le jeu a démarré
     * @return Bool
     */
    isStart() {
        return this.start;
    }

    /**
     * Passe le jeu a terminé
     */
    gameOver() {
        this.over = true;
    }

    /**
     * Retourne true si le jeu est terminé
     * @return Bool
     */
    isGameOver() {
        return this.over;
    }

    /**
     * Active le son du jeu
     */
    soundOn() {
        this.stopSound = false;
    }

    /**
     * Coupe le son du jeu
     */
    soundOff() {
        this.stopSound = true;
    }

    /**
     * Retourne true si le son est coupé
     * @return Bool
     */
    isSoundOff() {
        return this.stopSound;
    }

    /**
     * Ajoute une image a un élément HTML <td> de la grille de jeu
     * @param {Object} grid Instance de Grid
     * @param {string} position case du jeu
     * @param {string} imgName Nom de l'image à ajouter
     */
    addImg(grid, position, imgName) {
        let img;
        if (grid.reduce)
            img = new Image(20, 20);
        else
            img = new Image(40, 40);
        img.src = `/assets/img/${imgName}.gif`;
        img.classList.add("center");
        grid.selectCaseHTML(position).appendChild(img);
    }

    //
    /**
     * Affiche la partie HTML quand le bateau est touché
     * @param {Object} grid Instance de Grid
     * @param {string} position case du jeu
     * @param {bool} cible Valide ou non l'ajout de la classe "cible"
     */
    touchBoat(grid, position, cible = false) {
        if (!this.isSoundOff())
            GameEvent.playSound("fireTouch");
        if (cible)
            grid.selectCaseHTML(position).classList.add('cible');
        if (cible && grid.reduce)
            grid.selectCaseHTML(position).classList.add('cibleReduce');
        if (grid.reduce)
            this.addImg(grid, position, 'touchExplodereduce', true);
        else
            this.addImg(grid, position, 'touchExplode');
        grid.selectCaseHTML(position).classList.remove("hidden");
        grid.selectCaseHTML(position).setAttribute("statut", "touch");
    }

    /**
     * Affiche la partie HTML quand le bateau est coulé
     * @param {Object} grid Instance de Grid
     * @param {string} position case du jeu
     */
    downBoat(grid, position) {
        if (grid.reduce)
            grid.selectCaseHTML(position).classList.remove('cibleReduce');
        grid.selectCaseHTML(position).classList.remove('cible');
        grid.selectCaseHTML(position).setAttribute("statut", "down");
    }

    /**
     * Lance l'animation lorsque le bateau de l'adversaire est coulé
     * @param {object} boat Instance de Boat
     */
    animationBoatDown(boat) {
        GameEvent.infoGame(`le ${boat.name} de votre adversaire a été coulé`);
        this.divAnimation.classList.add(`boatDown`);
        setTimeout(() => this.divAnimation.classList.remove(`boatDown`), 2000);
    }

    /**
     * Affiche une animation en fin de partie.
     * @param {string} text Texte à insérer.
     * @param {object} grid Grille de jeu si besoin pour l'affichage des bateaux.
     * @param {string} imgName Nom de la class concernant l'animation à afficher.
     * @param {string} soundName Nom de l'id du son à ajouter.
     */
    animationEndGame(text, grid = null, imgName, soundName) {
        GameEvent.infoGame(text);
        if (!this.isSoundOff())
            GameEvent.playSound(soundName);
        this.divAnimation.classList.add(imgName);
        grid.gridHTML.forEach(e => e.classList.remove('hidden'));
        setTimeout(() => {
            this.divAnimation.classList.remove(imgName);
            GameEvent.infoGame('GAME OVER');
        }, 6000);
        this.gameOver();
    }

    /**
     * Lance l'animation lorsque le joueur a perdu contre l'IA. (modifier pour adapter avec joueur humain)
     * @param {object} grid Instance de Grid de l'adversaire
     */
    animationGamePlayer2Lose(grid) {
        GameEvent.infoGame('Vous avez perdu');
        if (!this.isSoundOff())
            GameEvent.playSound('gameOver');
        this.divAnimation.classList.add(`player-lose`);
        grid.gridHTML.forEach(e => e.classList.remove('hidden'));
        setTimeout(() => {
            this.divAnimation.classList.remove(`player-lose`);
            GameEvent.infoGame('GAME OVER');
        }, 6000);
        this.gameOver();
    }

    /**
     * Lance l'animation lorsque le joueur a perdu contre l'IA. (modifier pour adapter avec joueur humain)
     * @param {object} grid Instance de Grid de l'adversaire
     */
    animationGameLose(grid) {
        GameEvent.infoGame('Vous avez perdu');
        if (!this.isSoundOff())
            GameEvent.playSound('gameOver');
        this.divAnimation.classList.add(`ia-victory`);
        grid.gridHTML.forEach(e => e.classList.remove('hidden'));
        setTimeout(() => {
            this.divAnimation.classList.remove(`ia-victory`);
            GameEvent.infoGame('GAME OVER');
        }, 6000);
        this.gameOver();
    }

    /**
     * Lance l'animation lorsque le joueur gagne la partie.
     */
    animationGameWin() {
        this.divAnimation.classList.add(`player-victory`);
        GameEvent.infoGame('Vous avez gagné');
        if (!this.isSoundOff())
            GameEvent.playSound('gameWin');
        setTimeout(() => {
            this.divAnimation.classList.remove(`player-victory`);
            GameEvent.infoGame('GAME OVER');
        }, 6000)
        gameEvent.gameOver();
    }

    /**
     * Affiche la partie HTML quand le bateau est manqué
     * @param {Object} grid Instance de Grid
     * @param {string} position case du jeu
     */
    missBoat(grid, position) {
        if (!this.isSoundOff())
            GameEvent.playSound("fireMiss");
        if (grid.reduce)
            grid.selectCaseHTML(position).classList.add('splashReduce');
        else
            grid.selectCaseHTML(position).classList.add('splash');
        grid.selectCaseHTML(position).setAttribute("statut", "miss");
    }

    /**
    * Afficher un message dans la div HTML infoGame
    * @param {texte à afficher} text 
    * @param {delai durant laquelle le texte est affiché} delai 
    */
    static infoGame(text) {
        let infoGame = document.querySelector("#infoGame");
        infoGame.innerHTML = text;
        setTimeout(() => infoGame.innerHTML = "", 3000);
    }

    /**
     * Lance la lecture d'un son.
     * @param {string} sound Id de l'élément HTML
     */
    static playSound(sound) {
        let audio = document.querySelector(`#${sound}`);
        audio.play();
    }

    /**
     * Affiche le bouton pour sauvegarder le score et envoie le formulaire de sauvegarde au serveur
     */
    saveScore(score) {
        let infoScore = document.querySelector("#infoScore");
        infoScore.classList.remove('displayNone');
        infoScore.classList.add('displayBlock');
        let data = new FormData();
        data.append('score', score);
        let init = { method: 'post', body: data };
        infoScore.addEventListener('click', () => {
            fetch('/battle/score', init)
                .then(response => response.ok ? response.json() : Promise.reject(new Error("Invalid response from battle/score")))
                .then(obj => {
                    if (!obj.value) {
                        this.messageInfo.setAttribute('data', 'error');
                        this.messageInfo.textContent = obj.message;
                        setTimeout(() => {
                            this.messageInfo.setAttribute('data', '');
                            this.messageInfo.textContent = '';
                        }, 2500);
                    }
                    else {
                        this.messageInfo.setAttribute('data', 'success');
                        this.messageInfo.textContent = obj.message;
                        document.querySelector('#scorePlayer').textContent = 'Score : ' + score;
                        setTimeout(() => {
                            this.messageInfo.setAttribute('data', '');
                            this.messageInfo.textContent = '';
                        }, 2500);
                    }
                })
                .catch(error => console.error('erreur pas de réponse'));
        })
    }

    /**
     * Affiche le score du joueur. Et rempli le champ du formulaire score
     * @param {int} score 
     */
    getScore(score) {
        document.querySelector('#score').innerHTML = score + ' points';
    }

    /**
     * Affiche l'animation de lancement de missile
     * @param {int} gridNumber Numero de la grille de jeu.
     */
    launchMissile(gridNumber) {
        if (!this.isSoundOff())
            GameEvent.playSound("fireSound");
        let newGameImg = document.querySelector("#animation");
        newGameImg.classList.add('missile-' + gridNumber);
        setTimeout(() => newGameImg.classList.remove('missile-' + gridNumber), 1000);
    }

    /**
     * Affiche l'animation de bombardement de missile
     * @param {int} gridNumber Numero de la grille de jeu.
     */
    launchBombing(gridNumber) {
        if (!this.isSoundOff())
            GameEvent.playSound("bombing");
        let newGameImg = document.querySelector("#animation");
        newGameImg.classList.add('bombing-' + gridNumber);
        setTimeout(() => newGameImg.classList.remove('bombing-' + gridNumber), 2000);
    }

    /**
     * Affiche l'animation de MégaBomb
     */
    launchMegaBomb() {
        if (!this.isSoundOff())
            GameEvent.playSound("megaBomb");
        let newGameImg = document.querySelector("#animation");
        newGameImg.classList.add('megaBomb');
        setTimeout(() => newGameImg.classList.remove('megaBomb'), 2000);
    }
}