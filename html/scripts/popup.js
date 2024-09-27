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


function DisplayPopup(animate) {
    // on récupère les champs texte à modifier selon les données
    const popup = document.getElementById("popup")

    // lecture des données locales depuis le cookie et sauvegarde, car le cookie peut
    // se faire écraser si une nouvelle partie est chargée
    cookie.read();
    cookie_save = structuredClone(cookie);

    FillLocalInfo();
    FillDistantInfo(animate);

    // on affiche la popup 
    popup.classList.add("active")
    document.onkeydown = (event) => {
        if (event.key === "Escape" || event.key === "Esc") {
            HidePopup()
        }
    }
}

function HidePopup() {
    // on cache la popup
    const popup = document.getElementById("popup");
    popup.classList.remove("active")
    document.onkeydown = null
}

function FillLocalInfo() {
    // on recupère les champs à remplir
    const game_id = document.getElementById("popup_game_id");
    const resultat_mon_temps = document.getElementById("popup_resultat_mon_temps");
    const detail = document.getElementById("popup_detail")

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

}


function FillDistantInfo(animate) {
    // on recupère les champs à remplir
    const resultat_mon_temps = document.getElementById("popup_resultat_mon_temps");
    const resultat_meilleur_temps = document.getElementById("popup_resultat_meilleur_temps");
    const resultat_nombre_joueurs = document.getElementById("popup_resultat_nombre_joueur");

    // on met des icônes de chargement en attendant la reponsé du serveur
    resultat_meilleur_temps.innerHTML = '<span class="loading">x</span>';
    resultat_nombre_joueurs.innerHTML = resultat_meilleur_temps.innerHTML;

    // requête sur le back pour obtenir le meilleur temps et le nombre de joueurs
    // et remplir les champs qui dépendent des données distantes
    ApiGetRequest("score", (response) => {
        if (response.game_id == cookie_save.game_id) {
            resultat_meilleur_temps.innerText = response.best_time > 0 ? ParseSeconds(response.best_time) : "-";
            resultat_nombre_joueurs.innerText = response.nb_players;
            if (resultat_meilleur_temps.innerText == resultat_mon_temps.innerText && resultat_mon_temps.innerText != "-") {
                if (animate == true) {
                    setTimeout(() => { triggerFirework() }, 500);
                }
                else {
                    resultat_mon_temps.innerHTML = resultat_mon_temps.innerText + ' <span id="popup_mainstar">★</span><span id="popup_firework"></span>';
                }
            }
        }
        else {
            resultat_meilleur_temps.innerText = "expiré";
            resultat_nombre_joueurs.innerText = "expiré";
        }
    },
        (e) => {
            resultat_meilleur_temps.innerHTML = 'Erreur <input type="image" onclick="FillDistantInfo()" value="↺"/>';
            resultat_nombre_joueurs.innerHTML = resultat_meilleur_temps.innerHTML;
        }
    );
}


async function triggerFirework() {
    const resultat_mon_temps = document.getElementById("popup_resultat_mon_temps");
    resultat_mon_temps.innerHTML = resultat_mon_temps.innerText + ' <span id="popup_mainstar">★</span><span id="popup_firework"></span>';

    const mainstar = document.getElementById('popup_mainstar');
    mainstar.classList.add('animate');

    const container = document.getElementById('popup_firework');
    container.innerHTML = '';

    // Generate n stars in random directions
    for (let i = 0; i < 15; i++) {
        const star = document.createElement('div');
        star.classList.add('popup_littlestar');

        // Random start offset
        const endX = (Math.random() - 0.5) * 150;
        const endY = (Math.random() - 0.5) * 150;
        const rotZ = Math.random() * 360;

        // Apply start and end positions using CSS variables
        star.style.setProperty('--endX', `${endX}px`);
        star.style.setProperty('--endY', `${endY}px`);
        star.style.setProperty('--rotZ', `${endY}deg`);

        // Append star to the container
        container.appendChild(star);

        // Trigger the animation with a delay (if needed)
        setTimeout(() => {
            star.classList.add('animate');
        }, i * 25); 
    }
}