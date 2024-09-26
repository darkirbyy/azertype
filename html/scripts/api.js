function ApiRequest(verb, point, success, error) {
    let xmlHttp = new XMLHttpRequest();
    xmlHttp.open(verb, API_URL + API_URI + point, true);
    xmlHttp.timeout = API_TIMEOUT_MS;
    xmlHttp.ontimeout = function () {
        error();
    };
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            let response = JSON.parse(xmlHttp.responseText);
            success(response);
        }
        else {
            error();
        }
    }
    xmlHttp.send(null);
}




