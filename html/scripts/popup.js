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
    const resultat_mon_temps = document.getElementById("popup_resultat_mon_temps");
    const resultat_meilleur_temps = document.getElementById("popup_resultat_meilleur_temps");
    const resultat_nombre_joueurs = document.getElementById("popup_resultat_nombre_joueur");
    const detail = document.getElementById("popup_detail")

    // requête sur le back pour obtenir le meilleur temps et le nombre de joueurs
    // et remplir les champs qui dépendent des données distantes
    ApiRequest("GET", "score", (response)=>{
        cookie.read();
        if (response.game_id == 50) { //cookie.game_id ) {
            resultat_meilleur_temps.innerText = ParseSeconds(response.best_time);
            resultat_nombre_joueurs.innerText = response.nb_players;
            globalTimer.start(response.wait_time);
            Deroulement.AttendrePartie();
        }
        else {
            resultat_meilleur_temps.innerText = "expirée"
            resultat_nombre_joueurs.innerText = "expirée";
            Deroulement.ChargerPartie();
        }
    })
    
    // lecture des données locales depuis le cookie
    cookie.read();

    // on inscrit le temps total s'il existe
    resultat_mon_temps.innerText = cookie.seconds_total != undefined ? cookie.seconds_total : "-";

    // on vide le tableau des détails sauf le titre puis on ajoute chaque ligne 
    // avec le mot et le temps par mot s'il existe
    while (detail.rows.length > 0) {
        detail.deleteRow(0)
    }

    let current_row = null
    let current_cell = null
    for (let i = 0; i < cookie.liste_mot.length; i++) {
        current_row = detail.insertRow(-1)
        current_cell = current_row.insertCell(0)
        current_cell.classList.add("popup_detail_gauche")
        current_cell.innerText = cookie.liste_mot[i]
        current_cell = current_row.insertCell(1)
        current_cell.classList.add("popup_detail_droite")
        current_cell.innerText = cookie.seconds_par_mot[i] != undefined ? cookie.seconds_par_mot[i] : "-"
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





