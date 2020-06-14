"use strict";

let player = document.querySelector("#namePlayer");
let avatar = document.querySelector('#avatarPlayer');
let radioBox = document.querySelectorAll('.form-check-input');
let name = document.querySelector('#name');
let email = document.querySelector('#email');
let password = document.querySelector('#password');
let newPassword = document.querySelector('#newPassword');
let buttonSaveProfil = document.querySelector('#saveProfil');
let buttonDeleteProfil = document.querySelector('#deleteProfil');
let profil = new Profil(player, avatar, radioBox, name, email, password, newPassword);



/**
 * Affiche une demande de confirmation avant validation et test les données.
 */
buttonSaveProfil.addEventListener('click', () => {
    let formValidator = new FormValidator();
    if (!formValidator.textValidator(name, 'Pseudo invalide'))
        return;
    if (!formValidator.emailValidator(email, 'Email invalide'))
        return
    if (newPassword.value)
        if (!formValidator.passwordValidator(newPassword, 'Nouveau mot de passe invalide'))
            return;
    if (!formValidator.passwordValidator(password, 'Mot de passe invalide'))
        return;
    $.confirm({
        icon: 'fa fa-spinner fa-spin',
        title: 'Mise à jour du profil',
        content: `Attention vous allez mettre à jour votre profil`,
        type: 'orange',
        theme: 'dark',
        columnClass: 'small',
        buttons: {
            ok: {
                text: "Confirmer",
                btnClass: 'btn-success',
                keys: ['enter'],
                action: () => {
                    grecaptcha.ready(function () {
                        grecaptcha
                            .execute("6LdXTOsUAAAAAOcG-k0xGrxkZ8YoNQbzjvxYwNiK", { action: "submit" })
                            .then(function (token) {
                                profil.save(token);
                            });
                    });
                }
            },
            Annuler: () => {
                return;
            }
        }
    });
})

/**
 * Affiche une alerte avant suppression.
 */
buttonDeleteProfil.addEventListener('click', () => {
    let formValidator = new FormValidator();
    if (!formValidator.passwordValidator(password, 'Mot de passe invalide'))
        return;
    $.confirm({
        title: 'Supprimer le profil',
        content: 'Attention vous allez supprimer votre compte',
        type: 'red',
        theme: 'dark',
        columnClass: 'small',
        typeAnimated: true,
        buttons: {
            tryAgain: {
                text: 'Supprimer',
                btnClass: 'btn-red',
                action: () => {
                    grecaptcha.ready(function () {
                        grecaptcha
                            .execute("6LdXTOsUAAAAAOcG-k0xGrxkZ8YoNQbzjvxYwNiK", { action: "submit" })
                            .then(function (token) {
                                profil.delete(token);
                            });
                    });
                }
            },
            annuler: () => {
                return;
            }
        }
    });
})
