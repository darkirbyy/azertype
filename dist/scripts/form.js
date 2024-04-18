function GererFormulaire() {
    // on récupère les éléments du formulaire
    const form = document.querySelector("#game form")
    const mot_actuel = document.getElementById("game_mot_actuel")
    const mot_compteur_valeur = document.getElementById("game_mot_compteur_valeur")
    const mot_compteur_total = document.getElementById("game_mot_compteur_total")
    const reponse_texte = document.getElementById("game_reponse_texte")
    const temps_valeur = document.getElementById("game_temps_valeur")

    // création des variables et du timer qui enregistre les paramètres d'une partie
    let partie = Object.create(Partie)
    let timer = setInterval(() => {
        if (partie.timer_running) {
            partie.interval_total++
            partie.interval_par_mot++
            partie.seconds_total = ParseSeconds(partie.interval_total)
            temps_valeur.innerText = partie.seconds_total
        }
    }, interval_size)

    temps_valeur.innerText = ParseSeconds(0)
    PropoposerNouvellePartie(partie)

    // quand on valide le formulaire...
    form.addEventListener("submit", (event) => {
        event.preventDefault();

        if (["loading", "ready", "finish", "waiting"].includes(partie.status)){
            
        }
        // si la partie précédente est finie, on lance une nouvelle partie
        // en réinitalisant l'objet, le premier mot, le compteur de mot et le temps
        else if (partie.status == "asking") {
            partie.status == "loading"
            ChargerNouvellePartie(partie)
            temps_valeur.innerText = ParseSeconds(0)
        }


        // sinon on vient de valider un mot
        else {
            // si la réponse est exacte, on enregistre et réinitialise le temps par mot,
            // et on augmente le compteur de mot de 1
            // et ...
            if (reponse_texte.value === partie.mot_courant()) {
                mot_actuel.style.animation = ''
                setTimeout(function () { mot_actuel.style.animation = 'right_answer 0.15s' }, 0);
                partie.seconds_par_mot.push(ParseSeconds(partie.interval_par_mot))
                partie.interval_par_mot = 0
                partie.liste_index++
                mot_compteur_valeur.innerText = partie.liste_index.toString()

                // s'il reste des mots, on affiche le suivant
                if (partie.liste_index < partie.liste_mot.length) {
                    mot_actuel.innerText = partie.mot_courant()
                }
                // sinon on arrête le timer, et on met fin au jeu
                else {
                    partie.timer_running = false
                    partie.status = "finish"
                    partie.seconds_total = ParseSeconds(partie.interval_total)
                    DisplayPopup(partie);
                }
            }

            // sinon on augmente le nombre d'erreur de 1
            else {
                mot_actuel.style.animation = ''
                setTimeout(function () {mot_actuel.style.animation = 'wrong_answer 0.15s' }, 0);
                partie.nombre_erreur++
            }
        }

        // on vide dans tous les cas le champ de réponse
        reponse_texte.value = ""
    });

    // on libère le timer quand on appui sur la première lettre
    reponse_texte.addEventListener("input", () => {
        if(partie.status == "ready"){
            partie.timer_running = true
            partie.status = "playing"
        }
    })
}


function PropoposerNouvellePartie(partie) {
    document.getElementById("game_reponse").classList.remove("active")
    document.getElementById("game_nouvelle").classList.add("active")
    //document.getElementById("game_nouvelle_bouton").focus()

    partie.status = "asking"
}


function ChargerNouvellePartie(partie) {
    document.getElementById("game_nouvelle").classList.remove("active")
    document.getElementById("game_reponse").classList.add("active")

    const reponse_texte = document.getElementById("game_reponse_texte")
    reponse_texte.setAttribute("placeholder", "Chargement...")
    reponse_texte.setAttribute("disabled", "disabled")

    document.getElementById("game_mot_actuel").innerText = "???"
    document.getElementById("game_mot_compteur_valeur").innerText = "?"
    document.getElementById("game_mot_compteur_total").innerText = "?"

    partie.reinit()
    partie.status = "loading"
    GetDrawFromApi(partie)
}

function NouvellePartiePrete(partie) {
    document.getElementById("game_mot_actuel").innerText = partie.mot_courant()
    document.getElementById("game_mot_compteur_valeur").innerText = "0"
    document.getElementById("game_mot_compteur_total").innerText = partie.liste_mot.length.toString()

    const reponse_texte = document.getElementById("game_reponse_texte")
    reponse_texte.removeAttribute("disabled")
    reponse_texte.setAttribute("placeholder", "...")
    reponse_texte.focus()
    partie.status = "ready"
}


