document.addEventListener('DOMContentLoaded', function () {
    const deleteButton = document.getElementById("supprimer-utilisateur");

    if (deleteButton) {
        deleteButton.addEventListener('click', async function(event) {
            event.preventDefault();

            // Confirmation de la suppression
            if (confirm('Êtes-vous sûr de vouloir supprimer le compte ?')) {
                try {
                    // Exécuter la requête DELETE pour supprimer l'utilisateur
                    const response = await fetch(Routing.generate('supprimerUtilisateur', {
                        "login": deleteButton.dataset.utilisateurLogin
                    }), { method: "DELETE" });

                    // Si la requête DELETE est réussie, rediriger vers 'pages_vertes'
                    if (response.ok) {
                        window.location.href = Routing.generate('pages_vertes');
                    } else {
                        alert("Une erreur est survenue lors de la suppression du compte.");
                    }
                } catch (error) {
                    alert('Une erreur est survenue. Veuillez réessayer.');
                }
            }
        });
    }
});
