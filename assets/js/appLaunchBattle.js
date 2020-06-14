"Use strict";

let launchBattle = new LaunchBattle();
setInterval(() => launchBattle.listenResponse(), 5000);
setInterval(() => launchBattle.listenDemand(), 5000);
function launch(evt) {
    let player2 = evt.getAttribute('data');
    let name = evt.textContent;
    $.confirm({
        icon: 'fa fa-spinner fa-spin',
        title: 'Invitation partie multijoueur',
        content: `Vous allez inviter ${name}`,
        type: 'orange',
        theme: 'dark',
        columnClass: 'small',
        autoClose: 'Annuler|20000',
        buttons: {
            ok: {
                text: "Confirmer",
                btnClass: 'btn-success',
                keys: ['enter'],
                action: () => launchBattle.launch(player2)
            },
            Annuler: () => { return; }
        }
    });
}