"use strict";

let formValidator = new FormValidator();
let messageInfo = new MessageInfo();
let buttonForm = document.querySelector("#inscription-form .btn");
let email = document.querySelector("#inscription-form #email");
let password = document.querySelector("#inscription-form #password");
let check = document.querySelector("#inscription-form #check");
let fields = [email, password];

messageInfo.resetMessageInfo();

buttonForm.addEventListener("click", () => {
  formValidator.emailValidator(email, 'Email invalide');
  formValidator.passwordValidator(password, 'Mot de passe invalide');
  if (!check.checked) {
    messageInfo.displayMessageInfo("Vous devez accepter les conditions d'utilisation", "error");
    return;
  }
  grecaptcha.ready(function () {
    grecaptcha
      .execute("6LdXTOsUAAAAAOcG-k0xGrxkZ8YoNQbzjvxYwNiK", { action: "submit" })
      .then(function (token) {
        let data = new FormData();
        data.append("email", email.value);
        data.append("password", password.value);
        data.append("token", token);
        let init = { method: "post", body: data };
        fetch("/user/create", init)
          .then((response) => response.ok ? response.json() : Promise.reject(new Error("Invalid response from server")))
          .then((data) => {
            if (!data.value && data.errorCaptcha) {
              messageInfo.displayMessageInfo("Formulaire invalide", "error");
            }
            if (data.message) {
              messageInfo.displayMessageInfo(data.message);
            }
            if (data.value)
              window.location.replace("/battle/gameOne");
          });
      });
  });
});
