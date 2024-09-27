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
    const popup = document.getElementById("popup")

    // lecture des données locales depuis le cookie et sauvegarde, car le cookie peut
    // se faire écraser si une nouvelle partie est chargée
    cookie.read();
    cookie_save = structuredClone(cookie);

    FillLocalInfo();
    FillDistantInfo();

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


function FillDistantInfo() {
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
            if (resultat_mon_temps.innerText != "-" && resultat_meilleur_temps.innerText == resultat_mon_temps.innerText) {
                resultat_mon_temps.innerText += ' ★';
                console.log("lol");
                triggerFirework();
            }
        }
        else {
            resultat_meilleur_temps.innerText = "expiré";
            resultat_nombre_joueurs.innerText = "expiré";
        }
    },
        (e) => {
            // console.log("GET score : " + e);
            resultat_meilleur_temps.innerHTML = 'Erreur <input type="image" onclick="FillDistantInfo()" value="↺"/>';
            resultat_nombre_joueurs.innerHTML = resultat_meilleur_temps.innerHTML;
        }
    );
}


function triggerFirework() {
    const container = document.getElementById('popup_firework');

    // Remove existing stars
    container.innerHTML = '';

    const star = document.createElement('div');
    star.classList.add('popup_star');

    // Generate n stars in random directions
    for (let i = 0; i < 20; i++) {
        const star = document.createElement('div');
        star.classList.add('popup_star');

        // Random start offset
        const startX = (Math.random() - 0.5) * 150;
        const startY = (Math.random() - 0.5) * 60;

        // Random direction for the star)
        const endX = startX * 2;
        const endY = startY * 2;

        // Apply start and end positions using CSS variables
        star.style.setProperty('--startX', `${startX}px`);
        star.style.setProperty('--startY', `${startY}px`);
        star.style.setProperty('--endX', `${endX}px`);
        star.style.setProperty('--endY', `${endY}px`);

        // Append star to the container
        container.appendChild(star);

        // Trigger the animation with a delay (if needed)
        setTimeout(() => {
            star.classList.add('animate');
        }, i * 30); // Stagger the animation slightly for each star

        setTimeout(() => {
            container.innerHTML = '';
        }, 2000);
    }
}