class MessageInfo {
    constructor(messageInfo = null) {
        this.messageInfo = messageInfo ? messageInfo : document.querySelector(".message");
    }

    resetMessageInfo() {
        //Retire le message d'information provenant du server au bout de 3sec.
        if (this.messageInfo.textContent !== "") {
            setTimeout(() => {
                this.messageInfo.textContent = "";
                this.messageInfo.setAttribute("data", "");
            }, 3000);
        }
    }

    displayMessageInfo(message, type = 'success') {
        this.messageInfo.textContent = `${message}`;
        this.messageInfo.setAttribute("data", `${type}`);
        setTimeout(() => {
            this.messageInfo.textContent = "";
            this.messageInfo.setAttribute("data", "");
        }, 3000);
    }
}