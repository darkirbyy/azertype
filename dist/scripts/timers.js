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

let globalTimer = new GlobalTimer(globalTimerInterval);