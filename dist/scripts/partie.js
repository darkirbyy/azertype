const Partie = {
    liste_mot: [],
    liste_index: 0,
    nombre_erreur: 0,
    interval_total: -1,
    interval_par_mot: -1,
    seconds_total:"",
    seconds_par_mot: [],
    status: "",
    timer_running: false,
    
    reinit: function () {
        this.liste_mot=[]
        this.liste_index = 0
        this.nombre_erreur = 0
        this.interval_total = 0
        this.interval_par_mot = 0
        this.seconds_total = ""
        this.seconds_par_mot = []
    },

    mot_courant: function () {
        return this.liste_mot[this.liste_index]
    }
}

let partie = Object.create(Partie)

function ChargerPartie() {
    document.getElementById("game_nouvelle").classList.add("active")
    document.getElementById("game_reponse").classList.remove("active")

    const button_texte = document.getElementById("game_nouvelle_bouton")
    button_texte.setAttribute("value", "Chargement...")
    button_texte.setAttribute("disabled", "disabled")

    partie.reinit()
    partie.status = "loading"
    GetDrawFromApi()
}


function ProposerPartie() {
    const button_texte = document.getElementById("game_nouvelle_bouton")
    button_texte.setAttribute("value", "Lancer la partie")
    button_texte.removeAttribute("disabled")

    //document.getElementById("game_nouvelle_bouton").focus()
    partie.status = "asking"
}


function PretePartie() {
    document.getElementById("game_nouvelle").classList.remove("active")
    document.getElementById("game_reponse").classList.add("active")

    document.getElementById("game_mot_actuel").innerText = partie.mot_courant()
    document.getElementById("game_mot_compteur_valeur").innerText = "0"
    document.getElementById("game_mot_compteur_total").innerText = partie.liste_mot.length.toString()

    const reponse_texte = document.getElementById("game_reponse_texte")
    reponse_texte.removeAttribute("disabled")
    reponse_texte.setAttribute("placeholder", "...")
    //reponse_texte.focus()

    partie.status = "readying"
}

function LancerPartie(){
    partie.timer_running = true
    partie.status = "playing"
}

function FinirPartie(){
    partie.timer_running = false
    partie.status = "finishing"
    DisplayPopup();
}

function AttendrePartie() {
    document.getElementById("game_nouvelle").classList.add("active")
    document.getElementById("game_reponse").classList.remove("active")

    document.getElementById("game_mot_actuel").innerText = "???"
    document.getElementById("game_mot_compteur_valeur").innerText = "?"
    document.getElementById("game_mot_compteur_total").innerText = "?"

    const button_texte = document.getElementById("game_nouvelle_bouton")
    button_texte.setAttribute("value", "Prochaine partie dans" + wait_time)
    button_texte.setAttribute("disabled", "disabled")

    partie.status = "waiting"
}