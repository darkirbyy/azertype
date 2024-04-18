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
    // on insère le temps total et le nombre d'erreur
    const resultat_temps = document.getElementById("popup_resultat_temps");
    const resultat_erreur = document.getElementById("popup_resultat_erreur");
    resultat_temps.innerText = partie.seconds_total
    resultat_erreur.innerText = partie.nombre_erreur.toString()


    // on vide le tableau des détails sauf le titre
    const detail = document.getElementById("popup_detail")
    while (detail.rows.length > 0) {
        detail.deleteRow(0)
    }

    // on ajoute chaque ligne avec le mot et le temps par mot
    let current_row = null
    let current_cell = null
    for (let i = 0; i < partie.liste_mot.length; i++) {
        current_row = detail.insertRow(-1)
        current_cell = current_row.insertCell(0)
        current_cell.classList.add("popup_detail_gauche")
        current_cell.innerText = partie.liste_mot[i]
        current_cell = current_row.insertCell(1)
        current_cell.classList.add("popup_detail_droite")
        current_cell.innerText = partie.seconds_par_mot[i]
    }

    // on affiche la popup et on désactive tous les inputs du formulaire game
    const popup = document.getElementById("popup")
    popup.classList.add("active")
    document.querySelectorAll("#game input").forEach((element) => {
        element.setAttribute("disabled", "disabled")
    });

    //document.onkeydown = GererEscapeKey
}

function HidePopup() {
    const popup = document.getElementById("popup");
    popup.classList.remove("active")
    document.querySelectorAll("#game input").forEach((element) => {
        element.removeAttribute("disabled")
    });
    document.onkeydown = null
    AttendrePartie()
}


/*function GererEscapeKey(event) {
    if (event.key === "Escape" || event.key === "Esc") {
        HidePopup()
    }
}*/


