"use strict";

let formValidator = new FormValidator();
let messageInfo = new MessageInfo();
let buttonForm = document.querySelector("#contact-form .btn");
let prenom = document.querySelector("#contact-form #prenom");
let nom = document.querySelector("#contact-form #nom");
let email = document.querySelector("#contact-form #email");
let message = document.querySelector("#contact-form #message");
let fields = [prenom, nom, email, message];

messageInfo.resetMessageInfo();

buttonForm.addEventListener("click", () => {
  formValidator.textValidator(prenom, 'Prénom invalide');
  formValidator.textValidator(nom, 'Nom invalide');
  formValidator.emailValidator(email, 'Email invalide');
  formValidator.textValidator(message, 'Message invalide');
  if (formValidator.validate) {
    // On utilise ReCaptcha de Google et on récupère le token de validation.
    grecaptcha.ready(function () {
      grecaptcha
        .execute("6LdXTOsUAAAAAOcG-k0xGrxkZ8YoNQbzjvxYwNiK", { action: "submit" })
        .then(function (token) {
          let data = new FormData();
          data.append("prenom", prenom.value);
          data.append("nom", nom.value);
          data.append("email", email.value);
          data.append("message", message.value);
          data.append("token", token);
          let init = { method: "post", body: data };
          fetch("/postContact", init)
            .then((response) => response.ok ? response.json() : Promise.reject(new Error("Invalid response from server")))
            .then((data) => {
              if (!data.value && data.errorCaptcha) {
                messageInfo.displayMessageInfo("Formulaire invalide", "error");
              } else if (!data.value) {
                let element = document.querySelector(`#contact-form #${data.field}`);
                formValidator.setInvalidField(element);
              } else {
                messageInfo.displayMessageInfo("Formulaire envoyé");
                fields.forEach((element) => (element.value = ""));
              }
            });
        });
    });
  }
});
