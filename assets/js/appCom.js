"Use strict";

if (document.querySelector(".message")) {
  let messageInfo = document.querySelector(".message");
  //Retire le message d'information provenant du server au bout de 3sec.
  if (messageInfo.textContent !== "") {
    setTimeout(() => {
      messageInfo.textContent = "";
      messageInfo.setAttribute("data", "");
    }, 3000);
  }
}

let path = window.location.pathname;
let page = path.split("/").pop();
let chat;

if (page === "partieMultijoueur") {
  let player = document.querySelector("#namePlayer").textContent;
  //On instancie le chat.
  chat = new Chat(player);
  //On charge les messages.
  chat.getMessage();
  setInterval(() => chat.getMessage(), 3000);
  //On post les messages sur le chat.
  document
    .querySelector("#formChat")
    .addEventListener("submit", () => chat.postMessage());
  let online = new Online();
  //On charge la liste des utilisateurs connectés.
  online.getOnlineUsers();
  setInterval(() => online.getOnlineUsers(), 10000);
} else if (parseInt(page)) {
  let player = document.querySelector("#player1").getAttribute("grid");
  //On instancie le chat.
  chat = new Chat(player, "private");
} else {
  let player = document.querySelector("#namePlayer").textContent;
  //On instancie le chat.
  chat = new Chat(player, "popup");
  let online = new Online();
  //On charge la liste des utilisateurs connectés.
  online.getOnlineUsers();
  setInterval(() => online.getOnlineUsers(), 10000);

  document.getElementById("onlinePopup").style.display = "none";
  function openOnline() {
    document.getElementById("onlinePopup").style.display = "block";
    document.querySelector(".badgeOnline").style.display = "none";
  }
  function closeOnline() {
    document.getElementById("onlinePopup").style.display = "none";
    document.querySelector(".badgeOnline").style.display = "block";
  }
}
if (chat.option === "popup" || chat.option === "private") {
  //On charge les messages.
  setInterval(() => chat.getMessage(), 2000);
  //On post les messages sur le chat.
  document
    .querySelector("#formChat")
    .addEventListener("submit", () => chat.postMessage());
  document.getElementById("chatMulti").style.display = "none";
  function openChat() {
    chat.getMessage();
    document.getElementById("chatMulti").style.display = "block";
    document.querySelector(".badge").textContent = "";
    chat.messagesRead = true;
  }
  function closeChat() {
    document.getElementById("chatMulti").style.display = "none";
    chat.messagesRead = false;
  }
}
