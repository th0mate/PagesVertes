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

/**
 * Gestion des boutons pour modifier le thème du site
 */
function change_period2(period) {
    var monthly = document.getElementById("monthly2");
    var semester = document.getElementById("semester2");
    var annual = document.getElementById("annual2");
    var selector = document.getElementById("selector");
    if (period === "clair") {
        selector.style.left = 0;
        selector.style.width = monthly.clientWidth + "px";
        selector.style.backgroundColor = "#4CAF50";
        selector.innerHTML = `<img src="${soleilImageURL}" alt="">`;
        document.querySelector('.dark').classList.remove('dark');


    } else if (period === "auto") {
        selector.style.left = monthly.clientWidth + "px";
        selector.style.width = semester.clientWidth + "px";
        selector.innerHTML = `<img src="${autoImageURL}" alt="">`;
        selector.style.backgroundColor = "#4CAF50";

        if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.querySelector('html').classList.add('dark');
        }
    } else {
        selector.style.left = monthly.clientWidth + semester.clientWidth + 1 + "px";
        selector.style.width = annual.clientWidth + "px";
        selector.innerHTML = `<img src="${luneImageURL}" alt="">`;
        selector.style.backgroundColor = "#4CAF50";
        document.querySelector('html').classList.add('dark');
    }
}

change_period2("auto");