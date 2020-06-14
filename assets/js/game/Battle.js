"use strict";

/**
 * Class Battle. Gère les échanges de données entre les joueurs concernant la partie.
 */
class Battle {
    constructor(gridPlayer1, gridPlayer2, battleId, gameEvent, gameControl) {
        this.gridPlayer1 = gridPlayer1;
        this.gridPlayer2 = gridPlayer2;
        this.battleId = battleId;
        this.gameEvent = gameEvent;
        this.gameControl = gameControl;
        this.stopGetBoats = false;
        this.stopGetReady = false;
        this.stopGetEnd = false;
        this.stopGet = false;
        this.stopGetBombing = false;
        this.stopGetMegaBomb = false;
        this.stopGetRepair = false;
    }

    /**
     * Récupère la position des bateaux de l'adversaire.
     */
    getBoats() {
        fetch('/boat/getBoats')
            .then(response => response.ok ? response.json() : Promise.reject(new Error("Invalid response from server")))
            .then(boats => {
                boats.forEach(boat => {
                    let letters = boat.positions.split(',');
                    let startCase = letters[0];
                    let newBoat = new Boat(boat.name)
                    this.gridPlayer2.addBoat(newBoat);
                    if (parseInt(boat.direction) === 1)
                        this.gridPlayer2.downManual(newBoat, startCase);
                    else if (parseInt(boat.direction) === 2)
                        this.gridPlayer2.horizontalManual(newBoat, startCase);
                    this.gridPlayer2.validPosition(newBoat, true);
                })
                this.stopGetBoats = true;
            })
            .catch(error => console.error(error));
    };

    /**
     * Envoie au controller la position des bateaux du joueur.
     */
    postBoat() {
        let data = new FormData();
        if (this.gridPlayer1.listBoat.length === 5) {
            this.gridPlayer1.listBoat.forEach(boat => {
                data.append('name', boat.name)
                data.append('positions', boat.positions)
                data.append('direction', boat.direction)
                data.append('count', boat.count)
                let init = { method: 'post', body: data };
                fetch('/boat/postBoat', init)
                    .then(() => {
                        //Le joueur passe en mode ready.
                        this.gridPlayer1.player.ready();
                        //La partie passe en mode start.
                        this.gameEvent.startGame();
                        //On envoie à l'autre joueur que l'on est prêt à jouer.
                        Battle.postReady(1);

                    })
                    .catch(error => console.error(error))
            });
        }
        else {
            $.alert({
                title: `Partie Multijoueur`,
                content: `Il manque des bateaux`,
                type: 'red',
                theme: 'dark',
                columnClass: 'small'
            });
        }
    }

    /**
     * Envoie la requête au controller pour obtenir les données de la BDD.
     */
    getBattles() {
        fetch('/battle/getBattles')
            .then(response => response.ok ? response.json() : Promise.reject(new Error("Invalid response from server")))
            .then(battles => {
                console.log(battles);
                if (battles.value) {
                    if (!battles.player2.ready)
                        this.gridPlayer2.player.notReady();
                    else if (battles.player2.ready === 1 && !this.stopGetReady) {
                        $.alert({
                            title: 'Partie multijoueur',
                            content: `${this.gridPlayer2.player.name} est prêt à jouer`,
                            theme: 'dark',
                            type: 'green',
                            columnClass: 'small',
                        });
                        this.getBoats();
                        this.gridPlayer2.player.ready();
                        this.stopGetReady = true;
                    }
                    else if (battles.player2.ready === 3 && !this.stopGetEnd) {
                        this.gameEvent.animationGamePlayer2Lose(this.gridPlayer1);
                        this.stopGetEnd = true;
                        this.gameEvent.gameOver();
                    }
                    if (battles.player2.touch !== null) {
                        if (this.gridPlayer1.getAttribute('statut', battles.player2.touch) !== 'touch') {
                            this.gameEvent.touchBoat(this.gridPlayer1, battles.player2.touch, false, true);
                            this.gridPlayer2.player.notAttack();
                        }
                    }
                    if (battles.player2.miss !== null) {
                        if (this.gridPlayer1.getAttribute('statut', battles.player2.miss) !== 'miss') {
                            this.gameEvent.missBoat(this.gridPlayer1, battles.player2.miss, true);
                            this.gridPlayer2.player.notAttack();
                        }
                    }
                    if (battles.player2.bombing !== null && !this.stopGetBombing) {
                        this.gridPlayer2.targetsPower1 = battles.player2.bombing.split(',');
                        this.gridPlayer2.attackPower1Player2(this.gridPlayer1, this.gameControl.HTMLElement.buttonPower1Player2);
                        this.stopGetBombing = true;
                    }
                    if (battles.player2.megaBomb !== null && !this.stopGetMegaBomb) {
                        this.gridPlayer2.targetsPower2 = battles.player2.megaBomb.split(',');
                        this.gridPlayer2.attackPower2Player2(this.gridPlayer1, this.gameControl.HTMLElement.buttonPower2Player2);
                        this.stopGetMegaBomb = true;
                    }
                    if (battles.player2.repair !== null && !this.stopGetRepair) {
                        this.gridPlayer2.attackPower3Player2(battles.player2.repair, this.gameControl.HTMLElement.buttonPower3Player2);
                        this.stopGetRepair = true;
                    }
                }
                else if (!this.stopGet) {
                    $.alert({
                        title: 'Partie multijoueur',
                        content: `${this.gridPlayer2.player.name} a quitté la partie`,
                        theme: 'dark',
                        type: 'red',
                        columnClass: 'small',
                        autoClose: 'Quitter|20000',
                        buttons: {
                            Quitter: () => window.location.replace(`/battle/partieMultijoueur`)
                        }
                    });
                    this.stopGet = true;
                }
                else if (battle.reloaded) {
                    $.alert({
                        title: 'Partie multijoueur',
                        content: `${this.gridPlayer2.player.name} a quitté la partie`,
                        theme: 'dark',
                        type: 'red',
                        columnClass: 'small',
                        autoClose: 'Quitter|20000',
                        buttons: {
                            Quitter: () => window.location.replace(`/battle/partieMultijoueur`)
                        }
                    });
                    this.stopGet = true;
                }
            })
            .catch(error => console.error(error));
    }

    /**
     * Envoie la valeur de ready au controller.
     * 1: prêt à joueur.
     * 2: abandon de la partie.
     * 3: le joueur gagne la partie.
     * @param {int} ready Valeur de ready. 
     */
    static postReady(ready) {
        let data = new FormData();
        data.append('ready', ready);
        let init = { method: 'post', body: data };
        fetch('/battle/postReady', init)
            .then(response => response.ok ? response.json() : Promise.reject(new Error("Invalid response from server")))
            .then(battle => {
                if (!battle.value) {
                    $.alert({
                        title: 'Partie multijoueur',
                        content: `Une erreur est survenue`,
                        theme: 'dark',
                        type: 'red',
                        columnClass: 'small'
                    });
                }
            })
            .catch(error => console.error(error));
    }

    /**
     * Envoie au controller la valeur de la cible ciblée par le joueur.
     * @param {string} target cible
     * @param {string} value valeur de la cible
     */
    static postTarget(target, value) {
        let data = new FormData();
        data.append('target', target);
        data.append('value', value);
        let init = { method: 'post', body: data };
        fetch('/battle/postTarget', init);
    }

}


