/**
 * Class Player gère les joueurs du jeu
 */
class Player {
    /**
     * Constructeur de Player
     * @param {string} name Nom du joueur
     */
    constructor(name) {
        this.name = name;
        this.playerReady = false;
        this.playerAttack = false;
    }

    /**
     * Défini le joueur comme prêt à joueur
     */
    ready(){
        this.playerReady = true;
    }

    /**
     * Défini le joueur comme non prêt à jouer
     */
    notReady(){
        this.playerReady = false;
    }

    /**
     * Retourne true si le joueur est prêt à jouer
     * @return Bool
     */
    isReady(){
        return this.playerReady;
    }

    /**
     * Le joueur attaque
     */
    attack(){
        this.playerAttack = true;
    }

    /**
     * Le joueur n'attaque pas
     */
    notAttack(){
        this.playerAttack = false;
    }

    /**
     * Retourne true si le joueur attaque
     * @return Bool
     */
    isAttack(){
        return this.playerAttack;
    }
    
}