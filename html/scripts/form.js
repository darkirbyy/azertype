function GererFormulaire() {
    // on récupère les éléments du formulaire
    const form = document.querySelector("#game form")
    const mot_actuel = document.getElementById("game_mot_actuel")
    const mot_compteur_valeur = document.getElementById("game_mot_compteur_valeur")
    const reponse_texte = document.getElementById("game_reponse_texte")
    const action_bouton = document.getElementById("game_action_bouton");


    // gestion du bouton "valider" çàd du formulaire
    form.addEventListener("submit", (event) => {
        event.preventDefault();

        // si la réponse est exacte, on enregistre et réinitialise le temps par mot,
        // et on augmente le compteur de mot de 1
        // et ...
        if (reponse_texte.value === partie.mot_courant()) {
            mot_actuel.style.animation = ''
            setTimeout(function () { mot_actuel.style.animation = 'right_answer 0.15s' }, 0.1);
            partie.seconds_par_mot.push(ParseSeconds(partie.interval_par_mot))
            partie.interval_par_mot = 0
            partie.liste_index++
            mot_compteur_valeur.innerText = partie.liste_index.toString()

            // s'il reste des mots, on affiche le suivant
            if (partie.liste_index < partie.liste_mot.length) {
                mot_actuel.innerText = partie.mot_courant()
                reponse_texte.focus()
            }
            // sinon et on met fin au jeu
            else {
                partie.seconds_total = ParseSeconds(partie.interval_total)
                Deroulement.FinirPartie()
            }
        }

        // sinon on augmente le nombre d'erreur de 1
        else {
            mot_actuel.style.animation = ''
            setTimeout(function () { mot_actuel.style.animation = 'wrong_answer 0.15s' }, 0.1);
            partie.nombre_erreur++
            reponse_texte.focus()
        }

        // on vide dans tous les cas le champ de réponse
        reponse_texte.value = ""

    });

    // gestion de la zone de texte "reponse"
    reponse_texte.addEventListener("input", () => {
        // quand on appuie sur la première lettre, on libère le timer
        if (partie.status == "readying") {
            Deroulement.LancerPartie()
        }
    })

    // gestion du bouton "action"
    action_bouton.addEventListener("click", () => {
        if (partie.status == "asking") {
            Deroulement.PretePartie();
        }

        else if (partie.status == "readying" || partie.status == "playing") {
            reponse_texte.value = ""
            partie.seconds_total = undefined
            Deroulement.FinirPartie();
        }

        else if (partie.status == "waiting") {
            document.activeElement.blur()
            DisplayPopup();
        }
    });
}

