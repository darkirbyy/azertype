// durée entre chaque appel du timer global (1000: seconde)
const globalTimerInterval = 1000   

// durée entre chaque appel du timer de tirage (100: dixième de secondes, 10:centième)
const partieTimerSize = 10   
const PartieTimerDigit = Math.log10(1000 / partieTimerSize)

// url de connexion à l'api
const api_url = "http://localhost:8000/"
const api_timeout_ms = 2500
