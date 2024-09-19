/**
 * Génère un code aléatoire à 6 caractères alphanumériques, et le place en value de l'input #randomInput
 * @returns {Promise<void>} La promesse
 */
async function genererCodeAleatoire() {
    let code = "";
    let caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    for (let i = 0; i < 6; i++) {
        code += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
    }
    if (await verifierSiCodeEstDisponible(code)) {
        document.getElementById("utilisateur_code").value = code;
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
document.getElementById("utilisateur_code").addEventListener("input", async function () {
    let code = document.getElementById("utilisateur_code").value;
    if (!await verifierSiCodeEstDisponible(code)) {
        afficherMessageFlash('Ce code est déjà utilisé, veuillez en choisir un autre', 'warning');
        console.log('deja pris !')
    }
});