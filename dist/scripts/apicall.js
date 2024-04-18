function GetDrawFromApi() {
    let retry = 0;
    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
            let response = JSON.parse(xmlHttp.responseText);
            ChoisirStatus(response)
        }
        // else if (retry < max_retry){
        //     retry++;
        //     GetDrawFromApi();
        // }
    }
    xmlHttp.open("GET", api_url+'getDraw', true); 
    xmlHttp.send(null);
}

// returns the cookie with the given name,
// or undefined if not found
function getCookie(name) {
    let matches = document.cookie.match(new RegExp(
      "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
  }


function ChoisirStatus(response){
    let storeGameId = getCookie("game_id")
    if(storeGameId != undefined  && storeGameId == response.game_id){
        AttendrePartie();
    }
    else{
        partie.liste_mot = response.words.split(',');
        ProposerPartie();
    }
}



