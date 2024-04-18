function GetDrawFromApi(partie) {
    let retry = 0;
    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
            let response = JSON.parse(xmlHttp.responseText);
            partie.liste_mot = response.words.split(',');
            NouvellePartiePrete(partie)
        }
        // else if (retry < max_retry){
        //     retry++;
        //     GetDrawFromApi(partie);
        // }
    }
    xmlHttp.open("GET", api_url+'getDraw', true); 
    xmlHttp.send(null);
}


