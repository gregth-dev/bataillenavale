class LaunchBattle {

    /**
     * Envoie une demande de partie multijoueur.
     * @param {int} idPlayer2 
     */
    launch(idPlayer2) {
        let data = new FormData();
        data.append('idUser2', idPlayer2);
        let init = { method: 'post', body: data };
        fetch('/battle/launchBattle', init)
            .then(response => response.ok ? response.json() : Promise.reject(new Error("Invalid response from server")))
            .then(launchData => {
                if (launchData.message) {
                    $.alert({
                        title: 'Partie multijoueur',
                        content: `${launchData.message}`,
                        theme: 'dark',
                        type: 'red',
                        columnClass: 'small',
                    });
                }
                else {
                    $.confirm({
                        icon: 'fa fa-spinner fa-spin',
                        title: 'Invitation partie multijoueur',
                        content: `En attente...`,
                        type: 'orange',
                        theme: 'dark',
                        columnClass: 'small',
                        autoClose: 'Annuler|40000',
                        buttons: {
                            Annuler: () => this.responseLaunch(launchData, 3)
                        }
                    });
                }
            })
            .catch(error => console.log(error))
    }

    /**
     * Affiche le message lors d'une réponse.
     * @param {string} content 
     */
    displayResponseLaunch(content) {
        $.alert({
            title: 'Partie multijoueur',
            content: `${content}`,
            theme: 'dark',
            type: 'red',
            columnClass: 'small',
            buttons: {
                ok: {
                    text: "Fermer"
                }
            }
        });
    }

    /**
     * Affiche le message lors d'une invitation.
     */
    displayLaunchPlay(launchData) {
        $.confirm({
            icon: 'fa fa-spinner fa-spin',
            title: 'Partie Multijoueur',
            content: `${launchData.from} t'invite à jouer`,
            type: 'blue',
            theme: 'dark',
            buttons: {
                ok: {
                    text: "Accepter",
                    btnClass: 'btn-primary',
                    keys: ['enter'],
                    action: () => {
                        this.responseLaunch(launchData, 1)
                            .then(response => response.ok ? response.json() : Promise.reject(new Error("Invalid response from server")))
                            .then(launchData => {
                                if (!launchData.value)
                                    this.displayResponseLaunch(`La partie n\'est plus disponible`);
                                else
                                    window.location.replace(`/battle/gameMultijoueur/${launchData.id}`);
                            })
                    }
                },
                Refuser: () => this.responseLaunch(launchData, 2)
            }
        });
    }

    /**
     * Interroge la base de donnée s'il y a une reponse à l'invitation pour la partie multijoueur
     * 
     */
    listenResponse() {
        fetch('/battle/listenResponse')
            .then(response => response.ok ? response.json() : Promise.reject(new Error("Invalid response from server")))
            .then(launchData => {
                if (launchData.timeOut) {
                    this.displayResponseLaunch(`${launchData.from} n'a pas répondu à l'invitation`);
                    this.responseLaunch(launchData, 3);
                }
                if (launchData.accept)
                    window.location.replace(`/battle/gameMultijoueur/${launchData.id}`);
                if (launchData.refused) {
                    this.displayResponseLaunch(`${launchData.from} a refusé l'invitation`);
                    this.responseLaunch(launchData, 3);
                }
            })
            .catch(error => console.error(error));
    }

    /**
     * Interroge la base de donnée si une demande a été faite pour une partie multijoueur
     * 
     */
    listenDemand() {
        fetch('/battle/listenDemand')
            .then(response => response.ok ? response.json() : Promise.reject(new Error("Invalid response from server")))
            .then(launchData => {
                if (launchData.value)
                    this.displayLaunchPlay(launchData);
            })
            .catch(error => console.error(error));
    }

    /**
     * Réponse :
     * 1: Accept.
     * 2: Refused.
     * 3: Delete.
     * @param {int} option Choix la réponse.
     */
    responseLaunch(launchData, option) {
        let data = new FormData();
        console.log(launchData.id);
        data.append('idLaunchBattle', launchData.id);
        data.append('option', option);
        let init = { method: 'POST', body: data };
        return fetch('/battle/responseLaunch', init)
            .catch(error => console.error(error));
    }
}