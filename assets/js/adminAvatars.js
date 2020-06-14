"use strict";

let messageInfo = document.querySelector(".message");
//Retire le message d'information provenant du server au bout de 3sec.
if (messageInfo.textContent !== "") {
  setTimeout(() => {
    messageInfo.textContent = "";
    messageInfo.setAttribute("data", "");
  }, 3000);
}

function del(id) {
  let data = new FormData();
  data.append("id", id);
  let init = { method: "post", body: data };
  fetch("/user/deleteAvatar", init)
    .then((response) => response.ok ? response.json() : Promise.reject(new Error("Invalid response")))
    .then((obj) => {
      if (obj.value) {
        messageInfo.setAttribute("data", "success");
        messageInfo.textContent = obj.message;
        document.querySelector(`#avatar${obj.id}`).remove();
        setTimeout(() => {
          messageInfo.setAttribute("data", "");
          messageInfo.textContent = "";
        }, 4000);
      }
    })
    .catch((error) => console.error(error));
}

function deleteAvatar(id) {
  $.confirm({
    title: "Supprimer l'avatar",
    content: "Attention vous allez supprimer l'avatar",
    type: "red",
    theme: "dark",
    columnClass: "small",
    typeAnimated: true,
    buttons: {
      tryAgain: {
        text: "Supprimer",
        btnClass: "btn-red",
        action: () => del(id),
      },
      annuler: () => {
        return;
      },
    },
  });
}

function displayPhoto(files) {
  // Si pas de FileList ou FileList vide, abandonner.
  if (!files || !files.length) return;
  // Récupérer le File en premier élément de la FileList.
  let file = files[0];
  // Si le fichier est vide, alerter.
  if (!file.size) displayAlert("Le fichier est vide.");
  // Si le fichier est trop lourd, alerter.
  if (file.size > IMG_MAX_FILE_SIZE) displayAlert("Le fichier est trop lourd.");
  // Si le fichier n'a pas un type MIME autorisé, alerter.
  // Attention, JS ne se base que sur l'extension, pas sur l'encodage du fichier.
  if (IMG_MIMES.length && !IMG_MIMES.includes(file.type))
    displayAlert("L'image doit être JPEG ou PNG.");
  // Récupérer une référence de la vignette.
  let avatar = document.querySelector("#thumbnail img");
  // Instancier un FileReader.
  let reader = new FileReader();
  // Définir le traitement à effectuer quand le résultat de la lecture sera disponible.
  reader.onload = () => (avatar.src = reader.result);
  // Lire le fichier.
  reader.readAsDataURL(file);
}

function displayAlert(message) {
  return $.alert({
    title: "Ajout avatar",
    content: message,
    theme: "dark",
    type: "red",
    columnClass: "small",
  });
}
