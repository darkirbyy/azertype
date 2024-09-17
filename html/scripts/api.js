function ApiRequest(callback) {
    let xmlHttp = new XMLHttpRequest();
    xmlHttp.open("GET", API_URL+API_URI+'draw', true);
    xmlHttp.timeout = API_TIMEOUT_MS;
    xmlHttp.ontimeout = function(e) {
        // timeout
    }
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
            let response = JSON.parse(xmlHttp.responseText);
            callback(response)
        }
        else{
            // echec
        }
    }
    xmlHttp.send(null);
}




