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

function ParseTime(seconds){
    let h = Math.floor(seconds / 3600).toString().padStart(2,"0")
    let i = Math.floor((seconds % 3600) / 60).toString().padStart(2,"0")
    let s = Math.floor(seconds % 60).toString().padStart(2,"0")
    return h + ':' + i + ':' + s
}

function ParseSeconds(interval_number) {
    return Number.parseFloat(interval_number * partieTimerSize / 1000).toFixed(PartieTimerDigit).toString() + "s"
}


let globalTimer = new GlobalTimer(globalTimerInterval);
