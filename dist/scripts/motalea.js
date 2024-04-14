function TirerMotAlea(partie, reponse = "") {
    if (reponse !== "") {
        let tableau = reponse.replace(/(\[|]|")/gi, '').split(',')
        tableau.forEach((element) => {
            if (regex_mot.test(element) && partie.liste_mot.length < mot_par_partie)
                partie.liste_mot.push(element)
        })
    }
    if (partie.liste_mot.length < mot_par_partie)
        HttpGetAsync(url_alea, partie)
    else
        NouvellePartiePrete(partie)
}

function HttpGetAsync(theUrl, partie) {
    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
            TirerMotAlea(partie, xmlHttp.responseText);
    }
    xmlHttp.open("GET", theUrl, true); 
    xmlHttp.send(null);
}


