
class Deroulement{

    static nouvelle = document.getElementById("game_nouvelle");
    static reponse = document.getElementById("game_reponse");
    static nouvelle_bouton = document.getElementById("game_nouvelle_bouton");
    static reponse_texte = document.getElementById("game_reponse_texte");
    static mot_actuel = document.getElementById("game_mot_actuel");
    static mot_compteur_valeur = document.getElementById("game_mot_compteur_valeur");
    static mot_compteur_total = document.getElementById("game_mot_compteur_total");
    static temps_valeur = document.getElementById("game_temps_valeur")


    static ChargerPartie() {
        Deroulement.nouvelle.classList.add("active")
        Deroulement.reponse.classList.remove("active")

        Deroulement.nouvelle_bouton.setAttribute("value", "Chargement...")
        Deroulement.nouvelle_bouton.setAttribute("disabled", "disabled")
        Deroulement.temps_valeur.innerText = ParseSeconds(0)

        partie.reinit()
        partie.status = "loading"
        ApiRequest()
    }

    static ProposerPartie() {
        Deroulement.nouvelle_bouton.setAttribute("value", "Lancer la partie")
        Deroulement.nouvelle_bouton.removeAttribute("disabled")
        Deroulement.nouvelle_bouton.focus()
        
        partie.status = "asking"
    }


    static PretePartie() {
        Deroulement.nouvelle.classList.remove("active")
        Deroulement.reponse.classList.add("active")

        Deroulement.mot_actuel.innerText = partie.mot_courant()
        Deroulement.mot_compteur_valeur.innerText = "0"
        Deroulement.mot_compteur_total.innerText = partie.liste_mot.length.toString()

        Deroulement.reponse_texte.removeAttribute("disabled")
        Deroulement.reponse_texte.setAttribute("placeholder", "...")
        Deroulement.reponse_texte.focus()

        partie.status = "readying"
        Deroulement.LancerPartie()
    }

    static LancerPartie(){
        partie.timer_running = true
        partie.status = "playing"
    }

    static FinirPartie(){
        partie.timer_running = false
        partie.status = "finishing"
        DisplayPopup();
    }

    static AttendrePartie() {
        Deroulement.nouvelle.classList.add("active")
        Deroulement.reponse.classList.remove("active")

        Deroulement.mot_actuel.innerText = "???"
        Deroulement.mot_compteur_valeur.innerText = "?"
        Deroulement.mot_compteur_total.innerText = "?"
        Deroulement.temps_valeur.innerText = ParseSeconds(0)

        Deroulement.nouvelle_bouton.setAttribute("disabled", "disabled")
        Deroulement.nouvelle_bouton.setAttribute("value", "Chargement...")

        partie.status = "waiting"

        if(globalTimer.running == true){
            globalTimer.callbackUpdate = Deroulement.AttendrePartieUpdate;
            globalTimer.callbackFinish = Deroulement.ChargerPartie;
        }
        else{
            Deroulement.ChargerPartie();
        }
    }

    static AttendrePartieUpdate(seconds){
        Deroulement.nouvelle_bouton.setAttribute("value", "Prochaine partie dans " + ParseTime(seconds))
    }

}