"use strict";

/**
 * Class Chat gère la fonctionnalité du chat sur le site.
 */
class Chat {
    /**
     * Constructeur de Chat
     * 
     */
    constructor(player, option = '') {
        this.messageInput = document.querySelector('#message');
        this.chatList = document.querySelector('.chat-list');
        this.card = document.querySelector('.card-body');
        this.option = option;
        if(this.option != '') {
            this.cardMulti = document.querySelector('.card-bodyMulti');
            this.badge = document.querySelector('.badge');
        }
        this.player = player;
        this.messagesRead = false;
        this.lastMessagesNumber = 0;
    }

    /**
     * Envoie les messages au controller pour enregistrement en BDD.
     */
    postMessage() {
        event.preventDefault();
        let data = new FormData();
        data.append('content', message.value);
        data.append('option', this.option);
        let init = { method: 'post', body: data };
        if (!this.messageInput.value) {
            document.querySelector('#textAlert').textContent = 'Veuillez taper votre message';
            return;
        }
        fetch('/message/postMessage', init).then(() => {
            this.messageInput.value = '';
            this.messageInput.focus();
            this.getMessage();
        })
    }

    /**
     * Affiche les messages.
     * @param {string} classLi class du Li
     * @param {string} name nom du joueur
     * @param {string} content contenu du message
     * @param {int} avatar Numero de l'avatar
     */
    createChatElement(classLi, avatar, name, content) {
        let li = document.createElement('li');
        li.classList.add(classLi);
        if(this.option !== 'private') {
            let divChatImg = document.createElement('div');
            divChatImg.classList.add('chat-img');
            let img = document.createElement('img');
            img.src = `/assets/img/avatars/avatar${avatar}.png`;
            divChatImg.appendChild(img);
            li.appendChild(divChatImg);
        }
        let divChatBody = document.createElement('div');
        divChatBody.classList.add('chat-body')
        let divChatMsg = document.createElement('div');
        divChatMsg.classList.add('chat-message');
        divChatBody.appendChild(divChatMsg);
        let titreMsg = document.createElement('h5');
        titreMsg.classList.add('name');
        titreMsg.textContent = `${name}`;
        let pMsg = document.createElement('p');
        pMsg.textContent = `${content}`;
        divChatMsg.appendChild(titreMsg);
        divChatMsg.appendChild(pMsg);
        li.appendChild(divChatBody);
        this.chatList.appendChild(li);
    }

    /**
     * Récupère les messages du chat global. 
     */
    getMessage() {
        let data = new FormData();
        data.append('option', this.option);
        let init = { method: 'POST', body: data };
        fetch('/message/getMessage', init)
            .then(response => response.ok ? response.json() : Promise.reject(new Error("Invalid response from server")))
            .then(messages => {
                this.chatList.innerHTML = '';
                messages.forEach(message => {
                    if (this.option === 'popup' || this.option === 'private') {
                        if (messages.length > this.lastMessagesNumber && message.name !== this.player)
                            this.newMessagesNumber = messages.length - this.lastMessagesNumber;
                    }
                    if (message.name === this.player)
                        this.createChatElement('in', message.avatar, message.name, message.content);
                    else {
                        this.createChatElement('out', message.avatar, message.name, message.content);
                        if (this.option === 'popup' || this.option === 'private') {
                            if (!this.messagesRead)
                                this.badge.textContent = this.newMessagesNumber;
                            else {
                                this.badge.textContent = '';
                                this.lastMessagesNumber = messages.length;
                                this.newMessagesNumber = '';
                            }
                        }
                    }
                });
                if (this.option === 'popup' || this.option === 'private')
                    this.cardMulti.scrollTop = this.cardMulti.scrollHeight;
                else
                    this.card.scrollTop = this.card.scrollHeight;
            })
    }
}


