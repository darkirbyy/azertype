// durée entre chaque appel du timer (100: dixième de second, 10:centième)
const interval_size = 10   
const number_of_digit = Math.log10(1000 / interval_size)

// nombre de mot par partie et type de mot autorisé
const mot_par_partie = 10
const url_alea2 = "https://random-word-api.herokuapp.com/word?lang=fr&number=" + mot_par_partie *2
const url_alea = "http://localhost:8000/src/api.php"
const regex_mot = new RegExp("^[a-zéèçàù-]{2,15}$")

function ParseSeconds(interval_number) {
    return Number.parseFloat(interval_number * interval_size / 1000).toFixed(number_of_digit).toString() + "s"
}


const Partie = {
    liste_mot: [],
    liste_index: 0,
    nombre_erreur: 0,
    interval_total: -1,
    interval_par_mot: -1,
    seconds_total:"",
    seconds_par_mot: [],
    timer_running: false,
    reinit: function () {
        this.liste_mot=[]
        this.liste_index = 0
        this.nombre_erreur = 0
        this.interval_total = 0
        this.interval_par_mot = 0
        this.seconds_total = ""
        this.seconds_par_mot = []
        this.timer_running = false
        this.estFinie = false
    },

    mot_courant: function () {
        return this.liste_mot[this.liste_index]
    }
}
