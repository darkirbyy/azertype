function GererPopup() {
    const fermer_bouton = document.getElementById("popup_fermer_bouton")
    fermer_bouton.addEventListener("click", () => {
        HidePopup()
    })

    const popup = document.getElementById("popup")
    popup.addEventListener("click", (event) => {
        if (event.target === popup) {
            HidePopup()
        }
    })
}


function DisplayPopup() {
    // on récupère les champs texte à modifier selon les données
    const game_id = document.getElementById("popup_game_id");
    const resultat_mon_temps = document.getElementById("popup_resultat_mon_temps");
    const resultat_meilleur_temps = document.getElementById("popup_resultat_meilleur_temps");
    const resultat_nombre_joueurs = document.getElementById("popup_resultat_nombre_joueur");
    const detail = document.getElementById("popup_detail")

    // lecture des données locales depuis le cookie et sauvegarde, car le cookie peut
    // se faire écraser si une nouvelle partie est chargée
    cookie.read();
    let cookie_save = cookie;

    // requête sur le back pour obtenir le meilleur temps et le nombre de joueurs
    // et remplir les champs qui dépendent des données distantes
    ApiRequest("GET", "score", async (response)=>{
        if (response.game_id == 50) { //cookie_save.game_id ) {
            resultat_meilleur_temps.innerText = ParseSeconds(response.best_time);
            resultat_nombre_joueurs.innerText = response.nb_players;
        }
        else {
            resultat_meilleur_temps.innerText = "expirée";
            resultat_nombre_joueurs.innerText = "expirée";
        }
    })

    // on met des icônes de chargement en attendant la reponsé du serveur
    resultat_meilleur_temps.innerText = "chargement";
    resultat_nombre_joueurs.innerText = "chargement";
    
    // on inscrit le numéro de partie et le temps total s'ils existent
    game_id.innerText = cookie_save.game_id;
    resultat_mon_temps.innerText = cookie_save.seconds_total != undefined ? cookie_save.seconds_total : "-";

    // on vide le tableau des détails sauf le titre puis on ajoute chaque ligne 
    // avec le mot et le temps par mot s'il existe
    while (detail.rows.length > 0) {
        detail.deleteRow(0)
    }

    let current_row = null
    let current_cell = null
    for (let i = 0; i < cookie_save.liste_mot.length; i++) {
        current_row = detail.insertRow(-1)
        current_cell = current_row.insertCell(0)
        current_cell.classList.add("popup_detail_gauche")
        current_cell.innerText = cookie_save.liste_mot[i]
        current_cell = current_row.insertCell(1)
        current_cell.classList.add("popup_detail_droite")
        current_cell.innerText = cookie_save.seconds_par_mot[i] != undefined ? cookie_save.seconds_par_mot[i] : "-"
    }

    // on affiche la popup et on désactive tous les inputs du formulaire game
    const popup = document.getElementById("popup")
    popup.classList.add("active")
    document.querySelectorAll("#game input").forEach((element) => {
        element.setAttribute("disabled", "disabled")
    });

    document.onkeydown = (event) => {
        if (event.key === "Escape" || event.key === "Esc") {
            HidePopup()
        }
    }
}

function HidePopup() {
    const popup = document.getElementById("popup");
    popup.classList.remove("active")
    document.querySelectorAll("#game input").forEach((element) => {
        element.removeAttribute("disabled")
    });
    document.onkeydown = null
}





