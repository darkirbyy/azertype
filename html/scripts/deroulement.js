
class Deroulement {

    static action = document.getElementById("game_action");
    static reponse = document.getElementById("game_reponse");
    static action_bouton = document.getElementById("game_action_bouton");
    static reponse_texte = document.getElementById("game_reponse_texte");
    static reponse_bouton = document.getElementById("game_reponse_bouton");
    static mot_actuel = document.getElementById("game_mot_actuel");
    static mot_compteur_valeur = document.getElementById("game_mot_compteur_valeur");
    static mot_compteur_total = document.getElementById("game_mot_compteur_total");
    static temps_valeur = document.getElementById("game_temps_valeur")
    static temps_texte = document.getElementById("game_temps_texte")


    static ChargerPartie() {
        Deroulement.mot_actuel.innerText = "???";
        Deroulement.mot_compteur_valeur.innerText = "?";
        Deroulement.mot_compteur_total.innerText = "?";
        Deroulement.reponse_texte.setAttribute("disabled", "disabled")
        Deroulement.reponse_bouton.setAttribute("disabled", "disabled")
        Deroulement.action_bouton.setAttribute("disabled", "disabled")
        Deroulement.temps_texte.innerText = "Prochaine partie"
        Deroulement.temps_valeur.innerText = "Chargement..."

        partie.reinit()
        partie.status = "loading"
        ApiRequest("GET", "draw", (response) => {
            globalTimer.start(response.wait_time);
            cookie.read();
            if (cookie.game_id == response.game_id && cookie.played == true) {
                Deroulement.AttendrePartie();
            }
            else {
                partie.liste_mot = response.words.split(',');
                cookie.reinit();
                cookie.game_id = response.game_id;
                cookie.liste_mot = partie.liste_mot;
                cookie.write();
                Deroulement.ProposerPartie();
            }
        })
    }

    static ProposerPartie() {
        Deroulement.action_bouton.setAttribute("value", "Jouer")
        Deroulement.action_bouton.removeAttribute("disabled")
        Deroulement.temps_valeur.innerText = "Prête !"

        partie.status = "asking"
    }


    static PretePartie() {
        Deroulement.mot_actuel.innerText = partie.mot_courant()
        Deroulement.mot_compteur_valeur.innerText = "0"
        Deroulement.mot_compteur_total.innerText = partie.liste_mot.length.toString()
        Deroulement.reponse_texte.removeAttribute("disabled")
        Deroulement.reponse_texte.focus()
        Deroulement.action_bouton.setAttribute("value", "Abandonner")
        Deroulement.temps_texte.innerText = "Mon temps"
        Deroulement.temps_valeur.innerText = ParseSeconds(0)

        cookie.played = true;
        cookie.write();

        partie.status = "readying"
    }

    static LancerPartie() {
        Deroulement.reponse_bouton.removeAttribute("disabled")

        gameTimer.start();
        partie.status = "playing"
    }

    static FinirPartie() {
        gameTimer.stop();

        Deroulement.reponse_texte.setAttribute("disabled", "disabled")
        Deroulement.reponse_bouton.setAttribute("disabled", "disabled")
        Deroulement.action_bouton.setAttribute("disabled", "disabled")

        cookie.seconds_total = partie.seconds_total;
        cookie.seconds_par_mot = partie.seconds_par_mot;
        cookie.write();

        partie.status = "finishing"

        DisplayPopup();

        globalTimer.running == true ? Deroulement.AttendrePartie() : Deroulement.ChargerPartie();
    }

    static AttendrePartie() {
        Deroulement.mot_actuel.innerText = "???";
        Deroulement.mot_compteur_valeur.innerText = "?";
        Deroulement.mot_compteur_total.innerText = "?";

        Deroulement.reponse_texte.setAttribute("disabled", "disabled");
        Deroulement.reponse_bouton.setAttribute("disabled", "disabled");

        Deroulement.action_bouton.removeAttribute("disabled");
        Deroulement.action_bouton.setAttribute("value", "Voir la partie précédente");

        Deroulement.temps_texte.innerText = "Prochaine partie";
        globalTimer.display_now();

        partie.status = "waiting";
    }

}