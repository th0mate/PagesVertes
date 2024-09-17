/**
 * Affiche le bandeau sur l'écran après un click sur la balise <nav>
 */
function afficherMenu() {
    document.querySelector(".bandeau").style.display = "flex";
    document.querySelector(".close").style.display = "block";
}

/**
 * Cache le bandeau sur l'écran après un click sur la croix dans le bandeau
 */
function cacherMenu() {
    document.querySelector(".bandeau").style.display = "none";
    document.querySelector(".close").style.display = "none";
}