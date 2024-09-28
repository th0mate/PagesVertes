document.addEventListener('DOMContentLoaded', function() {
    const button = document.querySelector('.submit.boutonRecherche');
    if (button) {
        var img = document.createElement('img');
        img.src = imgDroite;
        img.alt = "";
        button.appendChild(img);
    }
});