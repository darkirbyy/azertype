function ApiRequest(verb, point, callback) {
    let xmlHttp = new XMLHttpRequest();
    xmlHttp.open(verb, API_URL + API_URI + point, true);
    xmlHttp.timeout = API_TIMEOUT_MS;
    xmlHttp.ontimeout = function (e) {
        // timeout
    }
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            let response = JSON.parse(xmlHttp.responseText);
            callback(response)
        }
        else {
            // echec
        }
    }
    xmlHttp.send(null);
}




