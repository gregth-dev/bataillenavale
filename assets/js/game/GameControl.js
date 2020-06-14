/**
 * Class GameControl gère toutes les commandes liées au jeu.
 * Ecouteurs sur les boutons du jeu et les grilles des joueurs.
 */
class GameControl {
    /**
     * Constructeur de GameControl
     * @param {object} gameEvent Instance de GameEvent.
     * @param {object} grid1 Instance de Grid. Obligatoirement grid1.
     * @param {object} grid2 Instance de Grid. Obligatoirement grid2.
     * @param {object} player1 Instance de Player. Obligatoirement player1.
     * @param {object} player2 Instance de Player. Obligatoirement player2.
     */
    constructor(gameEvent, grid1, grid2, player1, player2) {
        this.gameEvent = gameEvent;
        this.grid1 = grid1;
        this.grid2 = grid2;
        this.player1 = player1;
        this.player2 = player2;
        /**
         * Objet contenant les éléments HTML du jeu.
         */
        this.HTMLElement = {
            selectedBoat: document.querySelector('#selectBoat'),
            speakerButton: document.querySelector('#speaker'),
            speakerImg: document.querySelector('#speakerIMG'),
            speakerText: document.querySelector('#speakerText'),
            newGameButton: document.querySelector('#newGame'),
            autoPositionButton: document.querySelector('#autoPosition'),
            deleteButton: document.querySelector('#delButton'),
            buttonPower1Player1: document.querySelector('#power1ButtonPlayer1'),
            buttonPower2Player1: document.querySelector('#power2ButtonPlayer1'),
            buttonPower3Player1: document.querySelector('#power3ButtonPlayer1'),
            buttonPower1Player2: document.querySelector('#power1ButtonPlayer2'),
            buttonPower2Player2: document.querySelector('#power2ButtonPlayer2'),
            buttonPower3Player2: document.querySelector('#power3ButtonPlayer2'),
            reduceButtonPlayer1: document.querySelector('#reducePlayer1'),
            reduceButtonPlayer2: document.querySelector('#reducePlayer2')

        };
        /**
         * Tableau des éléments HTML Audio du jeu
         */
        this.sounds = [
            document.querySelector('#fireSound'), document.querySelector('#fireMiss'),
            document.querySelector('#fireTouch'), document.querySelector('#gameOver'),
            document.querySelector('#gameWin'), document.querySelector('#megaBomb'),
            document.querySelector('#alerte'), document.querySelector('#bombing')
        ];
    }

    /**
     * Sélectionne et affiche le porteAvions par défaut dans le menu de sélection.
     */
    selectedBoatDefault() {
        this.HTMLElement.selectedBoat.options[0].selected = 'selected';
        this.gameEvent.displaySelectBoat("porteAvions", 5);
    }

    /**
     * Sélection manuelle d'un bateau et affichage de celui-ci
     */
    selectBoat() {
        let boat = new Boat(this.HTMLElement.selectedBoat.value, this.player1);
        switch (this.HTMLElement.selectedBoat.value) {
            case "porteAvions":
                this.gameEvent.displaySelectBoat("porteAvions", 5);
                break;
            case "croiseur":
                this.gameEvent.displaySelectBoat("croiseur", 4);
                break;
            case "contreTorpilleur":
                this.gameEvent.displaySelectBoat("contreTorpilleur", 3);
                break;
            case "sousMarin":
                this.gameEvent.displaySelectBoat("sousMarin", 3);
                break;
            case "torpilleur":
                this.gameEvent.displaySelectBoat("torpilleur", 2);
                break;
        }
        return boat;
    }

    /**
     * Ecouteur sur le bouton du son du jeu. Active ou désactive le son en cliquant dessus.
     */
    soundButtonControl() {
        this.HTMLElement.speakerButton.addEventListener('click', () => {
            if (!this.gameEvent.isSoundOff()) {
                this.sounds.forEach(e => e.pause());
                this.gameEvent.soundOff();
                this.HTMLElement.speakerImg.src = '/assets/img/mute.png';
                this.HTMLElement.speakerText.textContent = 'OFF';
                GameEvent.infoGame("Son désactivé");
            }
            else {
                this.gameEvent.soundOn();
                this.HTMLElement.speakerImg.src = '/assets/img/speaker.png';
                this.HTMLElement.speakerText.textContent = 'ON';
                GameEvent.infoGame("Son activé");
            }
        });
    }

    /**
     * Recharge la page lorsque l'on clique sur le bouton HTML.
     */
    newGameLoad() {
        this.HTMLElement.newGameButton.addEventListener('click', () => location.reload());
    }

    /**
     * Permet le positionnement automatique en cliquant sur le bouton.
     */
    autoPositionBoats() {
        this.HTMLElement.autoPositionButton.addEventListener('click', () => {
            if (!this.gameEvent.isStart()) {
                this.grid1.reset();
                this.grid1.installBoatAuto();
                this.grid1.validPositionAuto();
            }
            else
                GameEvent.infoGame('La partie a commencé');
        })
    }

    /**
     * Supprime le bateau sélectionné dans le menu déroulant.
     */
    deleteKeyDown() {
        this.keyDownEvent = evt => {
            let boat = this.selectBoat();
            boat = this.grid1.findBoat(boat.name);
            if (evt.key === 'Delete') {
                this.HTMLElement.deleteButton.classList.replace('del', 'delPress');
                if (boat && !this.gameEvent.isStart())
                    this.grid1.invalidPositionManual(boat);
                else if (this.gameEvent.isStart())
                    GameEvent.infoGame('La partie a commencé');
                else if (!boat)
                    GameEvent.infoGame('Aucun bateau sur la grille de jeu');
            }
        }
        window.addEventListener('keydown', this.keyDownEvent);
    }

    /**
     * Animation du bouton delButton HTML
     */
    deleteKeyUp() {
        this.keyUpEvent = evt => {
            if (evt.key === 'Delete')
                this.HTMLElement.deleteButton.classList.replace('delPress', 'del'); S
        }
        window.addEventListener('keyup', this.keyUpEvent)
    }

    /**
     * Message d'information au clic sur le bouton delButton HTML.
     * Utilise la librairie confirm-js.
     */
    deleteAlert() {
        this.alertMsg = () => {
            $.alert({
                title: '',
                content: 'Utilisez la touche Suppr de votre clavier',
                theme: 'dark',
                columnClass: 'small',
            });
        }
        this.HTMLElement.deleteButton.addEventListener('click', this.alertMsg);
    }

    /**
     * Retire les différents écouteurs d'évenements concernant:
     * deleteButton
     */
    deleteEventsListener() {
        window.removeEventListener('keydown', this.keyDownEvent);
        window.removeEventListener('keyup', this.keyUpEvent)
        this.HTMLElement.deleteButton.removeEventListener('click', this.alertMsg);

    }

    /**
     * Ecouteur sur le bouton power1 du joueur1 et lance l'action : Bombardement
     */
    controlPower1() {
        this.HTMLElement.buttonPower1Player1.addEventListener('click', () => {
            if (this.grid1.countBoatOk() && this.gameEvent.isStart() && !this.player2.isAttack() && !this.grid1.usePower1) {
                this.HTMLElement.buttonPower1Player1.classList.replace('powerOn', 'powerOff');
                this.grid1.attackPower1(this.grid2);
            }
            else if (this.grid1.usePower1)
                GameEvent.infoGame("Le pouvoir est épuisé");
            else if (this.player2.isAttack())
                GameEvent.infoGame("Ce n\'est pas votre tour");
            else
                GameEvent.infoGame("La partie n'a pas démarré");
        });
    }

    /**
     * Ecoute sur le bouton power2 du joueur1 et lance l'action: MégaBomb
     */
    controlPower2() {
        this.HTMLElement.buttonPower2Player1.addEventListener('click', () => {
            if (this.grid1.countBoatOk() && this.gameEvent.isStart() && !this.player2.isAttack() && !this.grid1.usePower2) {
                if (this.HTMLElement.buttonPower2Player1.classList.contains('powerSelect')) {
                    this.HTMLElement.buttonPower2Player1.classList.replace('powerSelect', 'powerOn');
                    this.grid2.gridHTML.forEach(e => e.removeEventListener('click', power2Attack));
                }
                else {
                    this.HTMLElement.buttonPower2Player1.classList.replace('powerOn', 'powerSelect');
                    //Ecouteur sur la grille du joueur adverse
                    if (!this.grid1.usePower2)
                        this.grid2.gridHTML.forEach(e => e.addEventListener('click', power2Attack));
                }
            }
            else if (!this.gameEvent.isStart())
                GameEvent.infoGame("La partie n'a pas démarré");
            else if (this.grid1.usePower2)
                GameEvent.infoGame("Le pouvoir est épuisé");
            else
                GameEvent.infoGame("Ce n'est pas votre tour");
        })
        let power2Attack = evt => {
            this.HTMLElement.buttonPower2Player1.classList.remove('powerOn');
            this.HTMLElement.buttonPower2Player1.classList.add('powerOff');
            let target = evt.target.getAttribute('case');
            if (target) {
                this.grid1.attackPower2(this.grid2, target, this.HTMLElement.buttonPower2Player1);
                this.grid2.gridHTML.forEach(e => e.removeEventListener('click', power2Attack));
            }
            else if (!target)
                GameEvent.infoGame("Cible incorrect");
        }
    }

    /**
     * Ecoute le bouton Power3 du joueur1 et lance l'action: Réparation du bateau.
     * 
     */
    controlPower3() {
        this.HTMLElement.buttonPower3Player1.addEventListener('click', () => {
            if (!this.grid1.usePower3) {
                if (this.HTMLElement.buttonPower3Player1.classList.contains('powerSelect')) {
                    this.HTMLElement.buttonPower3Player1.classList.replace('powerSelect', 'powerOn');
                }
                else {
                    this.HTMLElement.buttonPower3Player1.classList.replace('powerOn', 'powerSelect');
                }
                //Ecouteur sur la grille du joueur.
                this.grid1.gridHTML.forEach(e => e.addEventListener('click', power3Attack))
            }
            else
                GameEvent.infoGame("Réparation épuisée");
        })
        let power3Attack = evt => {
            let target = evt.target.offsetParent.getAttribute('case');
            let statut = evt.target.offsetParent.getAttribute('statut');
            if (statut === 'touch') {
                this.grid1.attackPower3(target, this.grid2, this.HTMLElement.buttonPower3Player1);
                this.grid1.gridHTML.forEach(e => e.removeEventListener('click', power3Attack))
            }
            else {
                this.HTMLElement.buttonPower3Player1.classList.replace('powerSelect', 'powerOn');
                GameEvent.infoGame("Cible incorrect");
            }
        }
    }

    /**
     * Ecoute les clics sur la grille du joueur1 et positionne les bateaux en fonction.
     * right : position horizontale.
     * left : position verticale.
     * @param {string} clic Indiquer le clic a écouter rigth ou left
     */
    controlClicPosition(clic) {
        let position = evt => {
            if (clic === 'right')
                evt.preventDefault();
            let boat = this.selectBoat();
            if (!this.grid1.existBoat(boat) && !this.grid1.countBoatOk() && !this.gameEvent.isStart()) {
                this.grid1.addBoat(boat);
                if (clic === 'right') {
                    this.grid1.horizontalManual(boat, evt.target.attributes.case.value) ? this.grid1.validPosition(boat) : this.grid1.invalidPosition(boat);
                }
                else {
                    this.grid1.downManual(boat, evt.target.attributes.case.value) ? this.grid1.validPosition(boat) : this.grid1.invalidPosition(boat);
                }
            }
            if (this.gameEvent.isStart()) {
                GameEvent.infoGame('La partie a commencé');
                this.grid1.gridHTML.forEach(element => element.removeEventListener('click', position));
            }
            else
                GameEvent.infoGame(`Le ${boat.name} est en position`);
        }
        if (clic === 'right')
            this.grid1.gridHTML.forEach(element => element.addEventListener('contextmenu', position));
        this.grid1.gridHTML.forEach(element => element.addEventListener('click', position));
    }

    reduceGrid1() {
        this.HTMLElement.reduceButtonPlayer1.addEventListener('click', () => {
            if (!this.grid1.reduce) {
                this.grid1.reduce = true;
                this.grid1.gridHTML.forEach(element => {
                    element.classList.add('reduce');
                    if (element.classList.contains('splash'))
                        element.classList.replace('splash', 'splashReduce');
                });
                this.grid1.validPositionAuto();
                this.HTMLElement.reduceButtonPlayer1.textContent = 'Rétablir la grille';
            }
            else {
                this.grid1.reduce = false;
                this.grid1.gridHTML.forEach(element => {
                    element.classList.remove('reduce');
                    if (element.classList.contains('splashReduce'))
                        element.classList.replace('splashReduce', 'splash');
                });
                this.grid1.removeReducePositionAuto();
                this.grid1.validPositionAuto();
                this.HTMLElement.reduceButtonPlayer1.textContent = 'Réduire la grille de jeu';
            }
        })
    }

    reduceGrid2() {
        this.HTMLElement.reduceButtonPlayer2.addEventListener('click', () => {
            if (!this.grid2.reduce) {
                this.grid2.reduce = true;
                this.grid2.gridHTML.forEach(element => {
                    element.classList.add('reduce');
                    if (element.classList.contains('cible'))
                        element.classList.replace('cible', 'cibleReduce');
                    if (element.classList.contains('splash'))
                        element.classList.replace('splash', 'splashReduce');
                });
                this.grid2.validPositionAuto();
                this.HTMLElement.reduceButtonPlayer2.textContent = 'Rétablir la grille';
            }
            else {
                this.grid2.reduce = false;
                this.grid2.gridHTML.forEach(element => {
                    element.classList.remove('reduce');
                    if (element.classList.contains('cibleReduce'))
                        element.classList.replace('cibleReduce', 'cible');
                    if (element.classList.contains('splashReduce'))
                        element.classList.replace('splashReduce', 'splash');
                });
                this.grid2.removeReducePositionAuto();
                this.grid2.validPositionAuto();
                this.HTMLElement.reduceButtonPlayer2.textContent = 'Réduire la grille de jeu';
            }
        })
    }
}