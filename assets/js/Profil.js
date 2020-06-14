class Profil {

    constructor(player, avatar, radioBox, name, email, password, newPassword) {
        this.messageInfo = new MessageInfo();
        this.player = player;
        this.avatar = avatar;
        this.radioBox = radioBox;
        this.name = name;
        this.email = email;
        this.password = password;
        this.newPassword = newPassword;
    }

    /**
     * Met à jour les données de l'utilisateur coté client et envoie les données coté serveur.
     * @return void
     */
    save(token) {
        let avatar = this.avatar.getAttribute('data');
        this.radioBox.forEach(element => {
            if (element.checked)
                avatar = element.value;
        });
        let data = new FormData();
        data.append('avatar', avatar);
        data.append('name', this.name.value);
        data.append('email', this.email.value);
        data.append('password', this.password.value);
        data.append('newPassword', this.newPassword.value);
        data.append('token', token);
        let init = { method: 'post', body: data };
        fetch('/user/update', init)
            .then(response => response.ok ? response.json() : Promise.reject(new Error("Invalid response")))
            .then(obj => {
                if (!obj.value) {
                    this.messageInfo.displayMessageInfo(obj.message, "error");
                } else {
                    this.messageInfo.displayMessageInfo(obj.message, "success");
                    this.avatar.src = `/assets/img/avatars/avatar${obj.avatar}.png`;
                    this.avatar.setAttribute('data', obj.avatar);
                    this.player.textContent = obj.name;
                    this.name.value = obj.name;
                    this.email.value = obj.email;
                    this.password.value = '';
                }
            })
            .catch(error => console.error(error));
    }

    /**
     * Envoie l'ordre de suppression au server.
     * Redirige si tout se passe bien.
     */
    delete(token) {
        let data = new FormData();
        data.append('password', this.password.value);
        data.append('token', token);
        let init = { method: 'post', body: data };
        fetch('/user/deleteProfil', init)
            .then(response => response.ok ? response.json() : Promise.reject(new Error("Invalid response")))
            .then(obj => {
                if (!obj.value)
                    this.messageInfo.displayMessageInfo(obj.message, "error");
                else
                    window.location.replace("/");
            })
            .catch(error => console.error(error));
    }

}
