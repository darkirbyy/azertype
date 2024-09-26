function ApiGetRequest(point, success, error) {
    let xmlHttp = new XMLHttpRequest();
    xmlHttp.open("GET", API_URL + API_URI + point, true);
    xmlHttp.timeout = API_TIMEOUT_MS;
    xmlHttp.ontimeout = function () {
        error("timeout");
    };
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState != 4 || xmlHttp.status < 100)
            return;
        if (xmlHttp.status >= 200 && xmlHttp.status < 300) {
            let response = JSON.parse(xmlHttp.responseText);
            success(response);
        }
        else if (xmlHttp.status >= 400 && xmlHttp.status < 600){
            let response = JSON.parse(xmlHttp.responseText);
            error(response.error);
        }
        else{
            error('unkown');
        }

    }
    xmlHttp.send(null);
}

function ApiPostRequest(point, body, success, error) {
    let xmlHttp = new XMLHttpRequest();
    xmlHttp.open("POST", API_URL + API_URI + point, true);
    xmlHttp.timeout = API_TIMEOUT_MS;
    xmlHttp.ontimeout = function () {
        error("timeout");
    };
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState != 4|| xmlHttp.status < 100)
            return;
        if (xmlHttp.status >= 200 && xmlHttp.status < 300) {
            success();
        }
        else if (xmlHttp.status >= 400 && xmlHttp.status < 600){
            let response = JSON.parse(xmlHttp.responseText);
            error(response.error);
        }
        else{
            error('unkown');
        }
    }
    xmlHttp.send(JSON.stringify(body));
}




