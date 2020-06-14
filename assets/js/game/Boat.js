class Boat {

    //constructeur du bateau qui prend le nom du bateau et le type de joueur en paramètre
    /**
     * 
     * @param {nom du bateau} name 
     */
    constructor(name) {
        this.name = name;
        this.direction = undefined;
        this.positions = [];
        this.length = this.setLength();
        this.point = this.setPoint();
        this.count = this.length;
    }
    //renvoie la longueur du bateau (Nb cases occupées par le bateau)
    setLength() {
        switch (this.name) {
            case "porteAvions":
                return 5;
            case "croiseur":
                return 4;
            case "contreTorpilleur":
                return 3;
            case "sousMarin":
                return 3;
            case "torpilleur":
                return 2;
        }
    }

    setPoint() {
        switch (this.name) {
            case "porteAvions":
                return 1000;
            case "croiseur":
                return 750;
            case "contreTorpilleur":
                return 500;
            case "sousMarin":
                return 500;
            case "torpilleur":
                return 300;
        }
    }

    /**
     * Méthode static qui renvoie un tableau d'objets Boat
     * @param {*} player Instance de Player
     */
    static tabOfBoats(player) {
        let boats = ['porteAvions', 'croiseur', 'contreTorpilleur', 'sousMarin', 'torpilleur'];
        let tempTab = [];
        for (const element of boats)
            tempTab.push(new Boat(element, player.name));
        return tempTab;
    }
}