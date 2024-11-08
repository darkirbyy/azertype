
function GetCookie(name) {
    let matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}

function SetCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; SameSite=Strict; path=" + API_COOKIE_PATH;
}

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
        const cookieValue = GetCookie('azertype');
        const myArray = cookieValue != undefined ? JSON.parse(cookieValue) : [0, false, [], undefined, []];
        this.game_id = myArray[0];
        this.played = myArray[1];
        this.liste_mot = myArray[2];
        this.seconds_total = myArray[3];
        this.seconds_par_mot = myArray[4];
    },

    write: function () {
        const myArray = [this.game_id, this.played, this.liste_mot, this.seconds_total, this.seconds_par_mot];
        SetCookie('azertype', JSON.stringify(myArray), 1);
    }
}

let cookie = Object.create(Cookie);
let cookie_save = cookie;
