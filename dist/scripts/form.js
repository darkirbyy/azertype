function GererFormulaire() {
    // on récupère les éléments du formulaire
    const form = document.querySelector("#game form")
    const mot_actuel = document.getElementById("game_mot_actuel")
    const mot_compteur_valeur = document.getElementById("game_mot_compteur_valeur")
    const reponse_texte = document.getElementById("game_reponse_texte")
    const temps_valeur = document.getElementById("game_temps_valeur")

    function timerCallback(interval){
        temps_valeur.innerText = partie.seconds_total
    }

    // quand on valide le formulaire...
    form.addEventListener("submit", (event) => {
        event.preventDefault();
        gameTimer.callbackUpdate = timerCallback;

        // sinon on vient de valider un mot
        if(partie.status == "playing") {
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
                setTimeout(function () {mot_actuel.style.animation = 'wrong_answer 0.15s' }, 0.1);
                partie.nombre_erreur++
            }
            
            // on vide dans tous les cas le champ de réponse
            reponse_texte.value = ""
        }

        // si la partie suivante est prête à être chargéee, on charge une nouvelle partie
        else if (partie.status == "asking") {
            Deroulement.PretePartie()
            temps_valeur.innerText = ParseSeconds(0)
        }

        // dans les autres cas ("loading", "readying", "finishing", "waiting") on ne fait rien
        else {
        }

    });

   /* // on libère le timer quand on appui sur la première lettre
    reponse_texte.addEventListener("input", () => {
        if(partie.status == "readying"){
            Deroulement.LancerPartie()
        }
    })*/
}

