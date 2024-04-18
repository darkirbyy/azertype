// durée entre chaque appel du timer (100: dixième de second, 10:centième)
const interval_size = 10   
const number_of_digit = Math.log10(1000 / interval_size)

// nombre de mot par partie et type de mot autorisé
const api_url = "http://localhost:8000/"
const max_retry = 3


