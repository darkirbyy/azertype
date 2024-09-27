const Partie = {
    liste_mot: [],
    liste_index: 0,
    nombre_erreur: 0,
    interval_total: -1,
    interval_par_mot: -1,
    seconds_total: "",
    seconds_par_mot: [],
    status: "",

    reinit: function () {
        this.liste_mot = []
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
