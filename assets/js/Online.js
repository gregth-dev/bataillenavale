/**
 * Class Online. Affiche les utilisateurs en ligne.
 */
class Online {
  constructor() {
    this.online = document.querySelector(".onlineUsers");
    this.player = document.querySelector("#namePlayer").textContent;
    this.badge = document.querySelector(".badgeOnline");
  }

  /**
   * Interroge la BDD et retourne la liste des utilisateurs en ligne.
   */
  getOnlineUsers() {
    fetch("/user/onlineUsers")
      .then((response) =>
        response.ok
          ? response.json()
          : Promise.reject(new Error("Invalid response from server"))
      )
      .then((data) => {
        this.online.innerHTML = "";
        if (this.badge) this.badge.textContent = data.users.length;
        data.users.forEach((user) => {
          let row = this.online.insertRow();
          let cell = row.insertCell();
          let link = document.createElement("a");
          link.style.fontSize = "15px";
          link.classList.add("userOnline");
          link.textContent = ` ${user.name} `;
          if (this.player != user.name) {
            link.setAttribute("data", `${user.id}`);
            link.style.cursor = "pointer";
            link.setAttribute("onclick", `launch(this)`);
          }
          let img = document.createElement("img");
          img.src = `/assets/img/avatars/avatar${user.avatar}.png`;
          cell.appendChild(img);
          cell.appendChild(link);
        });
      })
      .catch((error) => console.log(error));
  }
}
