const Cookie = {
    game_id: 0,
    played: false,
    liste_mot: [],
    seconds_total: undefined,
    seconds_par_mot: [],

    reinit: function () {
        this.game_id = 0,
        this.played = false,
        this.liste_mot = [],
        this.seconds_total = undefined,
        this.seconds_par_mot = []
    },

    read: function () {
        const storageValue = localStorage.getItem('azertype/last_game');
        const myArray = storageValue != undefined ? JSON.parse(storageValue) : [0, false, [], undefined, []];
        this.game_id = myArray[0];
        this.played = myArray[1];
        this.liste_mot = myArray[2];
        this.seconds_total = myArray[3];
        this.seconds_par_mot = myArray[4];
    },

    write: function () {
        const myArray = [this.game_id, this.played, this.liste_mot, this.seconds_total, this.seconds_par_mot];
        localStorage.setItem('azertype/last_game', JSON.stringify(myArray));
    }
}

let cookie = Object.create(Cookie);
let cookie_save = cookie;
