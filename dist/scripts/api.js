function ApiRequest() {
    let xmlHttp = new XMLHttpRequest();
    xmlHttp.open("GET", api_url+'draw', true);
    xmlHttp.timeout = api_timeout_ms;
    xmlHttp.ontimeout = function(e) {
        // timeout
    }
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
            let response = JSON.parse(xmlHttp.responseText);
            ChoisirStatus(response)
        }
        else{
            // echec
        }
    }
    xmlHttp.send(null);
}


function GetCookie(name) {
    let matches = document.cookie.match(new RegExp(
      "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}

function SetCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; SameSite=Strict; path=/";
}


function ChoisirStatus(response){
    globalTimer.start(response.wait_time);
    let storeGameId = GetCookie("gameId")
    if(storeGameId != undefined  && storeGameId == response.game_id){
        Deroulement.AttendrePartie();
    }
    else{
        SetCookie("gameId", response.game_id, 1);
        partie.liste_mot = response.words.split(',');
        Deroulement.ProposerPartie();
    }
}



