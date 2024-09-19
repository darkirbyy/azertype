// durée entre chaque appel du timer global (1000: seconde)
const globalTimerInterval = 1000

// durée entre chaque appel du timer de tirage (100: dixième de secondes, 10:centième)
const gameTimerInterval = 10
const gameTimerDigit = Math.log10(1000 / gameTimerInterval)


// blocs textes à modifier selon la valeur des timers
const temps_valeur = document.getElementById("game_temps_valeur")


class GlobalTimer {
    constructor(interval) {
        this.interval = interval;
    }

    start(intervals) {
        clearInterval(this.timer);
        this.intervals = intervals;
        this.running = true;
        this.timer = setInterval(this.update.bind(this), this.interval)
    }

    update() {
        if (this.intervals > 0) {
            this.intervals--;
            if (partie.status == 'waiting') {
                this.display_now();
            }
        }
        else {
            this.running = false;
            clearInterval(this.timer);
            if (partie.status == 'waiting') {
                Deroulement.ChargerPartie();
            }
        }
    }

    display_now() {
        temps_valeur.innerText = ParseTime(this.intervals);
    }
}

class GameTimer {
    constructor(interval) {
        this.interval = interval;
    }

    start() {
        clearInterval(this.timer);
        this.running = true;
        this.timer = setInterval(this.update.bind(this), this.interval)
    }

    update() {
        partie.interval_total++
        partie.interval_par_mot++
        partie.seconds_total = ParseSeconds(partie.interval_total)
        temps_valeur.innerText = partie.seconds_total
    }

    stop() {
        this.running = false
        clearInterval(this.timer);
    }
}

function ParseTime(seconds) {
    let h = Math.floor(seconds / 3600).toString().padStart(2, "0")
    let i = Math.floor((seconds % 3600) / 60).toString().padStart(2, "0")
    let s = Math.floor(seconds % 60).toString().padStart(2, "0")
    return h + ':' + i + ':' + s
}

function ParseSeconds(interval_number) {
    return Number.parseFloat(interval_number * gameTimerInterval / 1000).toFixed(gameTimerDigit).toString() + "s"
}

let gameTimer = new GameTimer(gameTimerInterval);
let globalTimer = new GlobalTimer(globalTimerInterval);
