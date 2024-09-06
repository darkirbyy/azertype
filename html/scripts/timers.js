// durée entre chaque appel du timer global (1000: seconde)
const globalTimerInterval = 1000   

// durée entre chaque appel du timer de tirage (100: dixième de secondes, 10:centième)
const gameTimerInterval = 10   
const gameTimerDigit = Math.log10(1000 / gameTimerInterval)


class GlobalTimer{
    constructor(interval){
        this.interval = interval
    }

    start(intervals){
        clearInterval(this.timer);
        this.callbackUpdate = function(intervals){};
        this.callbackFinish = function(){};
        this.intervals = intervals
        this.running = true
        this.timer = setInterval(this.update.bind(this), this.interval)
    }

    update(){
        if(this.intervals > 0){
            this.callbackUpdate(this.intervals);
            this.intervals -= 1;
        }
        else{
            clearInterval(this.timer);
            this.callbackFinish();
            this.running = false;
        }
    }
}

class GameTimer{
    constructor(interval){
        this.interval = interval
        this.callbackUpdate = function(){}
    }

    timer = setInterval(() => {
        if (partie.timer_running) {
            partie.interval_total++
            partie.interval_par_mot++
            partie.seconds_total = ParseSeconds(partie.interval_total)
            this.callbackUpdate()
        }
    }, this.interval)
}

function ParseTime(seconds){
    let h = Math.floor(seconds / 3600).toString().padStart(2,"0")
    let i = Math.floor((seconds % 3600) / 60).toString().padStart(2,"0")
    let s = Math.floor(seconds % 60).toString().padStart(2,"0")
    return h + ':' + i + ':' + s
}

function ParseSeconds(interval_number) {
    return Number.parseFloat(interval_number * gameTimerInterval / 1000).toFixed(gameTimerDigit).toString() + "s"
}

let gameTimer = new GameTimer(gameTimerInterval);
let globalTimer = new GlobalTimer(globalTimerInterval);
