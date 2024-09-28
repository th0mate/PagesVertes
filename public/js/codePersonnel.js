/**
 * Génère un code aléatoire à 6 caractères alphanumériques, et le place en value de l'input #randomInput
 * @returns {Promise<void>} La promesse
 */
async function genererCodeAleatoire() {
    let code = "";
    let caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    const element = getCodeElement();
    if (!element) {
        return;
    }
    for (let i = 0; i < 6; i++) {
        code += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
    }
    if (await verifierSiCodeEstDisponible(code)) {
        element.value = code;
        element.setCustomValidity("");
        document.querySelector('.submit').disabled = false;
    } else {
        await genererCodeAleatoire();
    }
}


/**
 * Vérifie si le code passé en paramètre est disponible, c'est-à-dire qu'il n'existe pas déjà dans la BD
 * @param code Le code à vérifier
 * @returns {Promise<boolean>} True si le code est disponible, false sinon
 */
async function verifierSiCodeEstDisponible(code) {
    let URL = Routing.generate('verifierCode', {"code": code});
    const response = await fetch(URL, {method: "POST"});
    return await response.json();
}


/**
 * Dès que #utilisateur_code est modifié, donc que l'utilisateur tape un caractère dans l'input, on vérifie si le code est disponible
 */
const element = getCodeElement();
if (element) {
    element.addEventListener("input", async function () {
        const element = getCodeElement();
        if (!element) {
            return;
        }
        let code = element.value;
        if (code.length !== 6) {
            return;
        }
        if (!await verifierSiCodeEstDisponible(code)) {
            element.setCustomValidity("Ce code est déjà utilisé, veuillez en choisir un autre");
            document.querySelector('.submit').disabled = true;
            afficherMessageFlash('Ce code est déjà utilisé, veuillez en choisir un autre', 'warning');
        } else {
            element.setCustomValidity("");
            document.querySelector('.submit').disabled = false;
        }
    });
}


/**
 * Retourne l'élément input contenant le code
 * @returns {HTMLElement} L'élément input avec l'id 'utilisateur_code' ou 'edit_utilisateur_code' si 'utilisateur_code' n'existe pas
 */
function getCodeElement() {
    let element = document.getElementById("utilisateur_code");
    if (element === null) {
        element = document.getElementById("edit_utilisateur_code");
    }
    return element;
}


/**
 * Copie dans le presse-papiers le code passé en paramètre
 * @param code Le code à copier
 */
async function copierCode(code) {
    if (document.hasFocus()) {
        await navigator.clipboard.writeText(code);
        afficherMessageFlash('Code copié dans le presse-papiers', 'success');
    } else {
        afficherMessageFlash('Impossible de copier le code : le document n\'est pas focalisé', 'danger');
    }
}