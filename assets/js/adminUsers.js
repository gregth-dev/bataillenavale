"use strict";

let messageInfo = document.querySelector('.message');
//Retire le message d'information provenant du server au bout de 3sec.
if (messageInfo.textContent !== '') {
    setTimeout(() => {
        messageInfo.textContent = '';
        messageInfo.setAttribute('data', '');
    }, 3000);
}
function del(id) {
    let data = new FormData();
    data.append('idUser', id);
    let init = { method: 'post', body: data };
    fetch('/user/deleteUser', init)
        .then(response => response.ok ? response.json() : Promise.reject(new Error("Invalid response")))
        .then(obj => {
            if (obj.value) {
                messageInfo.setAttribute('data', 'success');
                messageInfo.textContent = obj.message;
                document.querySelector(`#user${obj.id}`).remove();
                setTimeout(() => {
                    messageInfo.setAttribute('data', '');
                    messageInfo.textContent = '';
                }, 4000);
            }
        })
        .catch(error => console.error(error));
}

function deleteUser(id) {
    $.confirm({
        title: 'Supprimer l\'utilisateur',
        content: 'Attention vous allez supprimer l\'utilisateur',
        type: 'red',
        theme: 'dark',
        columnClass: 'small',
        typeAnimated: true,
        buttons: {
            tryAgain: {
                text: 'Supprimer',
                btnClass: 'btn-red',
                action: () => del(id)
            },
            annuler: () => {
                return;
            }
        }
    });
}