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
        this.timer_running = false
    },

    mot_courant: function () {
        return this.liste_mot[this.liste_index]
    }
}