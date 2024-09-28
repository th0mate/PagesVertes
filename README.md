
![Logo du projet](public/img/logo-fondnoir.png)


# Projet Web septembre 2024 G2 - Pages Vertes
https://gitlabinfo.iutmontp.univ-montp2.fr/projetweb1/annuairesymfony

## Membres du groupe :
- Lorick VERGNES
- Thibaut AUDOUY
- Thomas LOYE


## Réparition des tâches :
- Lorick :
    - Création du mode maintenance
    - Modification du mot de passe
    - Commande Symfony pour la création d'un utilisateur
    - Inscription d'un utilisateur


- Thibaut :
    - Role ADMIN
    - Voters
    - Suppression d'un utilisateur
    - Modification d'un utilisateur
    - Données des utilisateurs (utilisateurs dans la page "Parcourir")
    - Données des utilisateurs en JSON
    - Dates de dernière modification et connexion
    - Modification de profil
    - Barre de recherche



- Thomas :
    - Set up du projet et de la BD
    - vues TWIG
    - init de la classe Utilisateur
    - Connexion utilisateur
    - JS Asynchrone pour la génération de code aléatoire et la vérification du code
    - Styles CSS du site
    - Responsive
    - Formulaires de connexion et de changement de mot de passe (PHP + TWIG)
    - Barre de recherche (formulaire initial et style)
    - Navigation et messages flash
    - JavaScript (messages flash, visuels, code)


## Accéder au JSON des utilisateurs :
```/utilisateurs/{login}``` \
Accéder avec la méthode "DELETE" via POSTMAN

## Utiliser la commande Symfony pour créer un utilisateur :
Dans le bash du projet, taper la commande :\
```php bin/console create:user --login=<login> --password=<password> --email=<email> --code=<code>```

Pour la visibility et le role admin en true, rajouter : ```--visibility --admin```

Sinon pour qu'ils soient en false : ```--no-visibility --no-admin```

### En mode interactif :
Si vous vous êtes trompé lors de la création d'un utilisateur, en entrant une valeur non conforme aux contraintes,
ou que vous avez entré aucune valeur, vous pourrez entrer ces valeurs en suivant les indications de la console.

Information supplémentaire concernant le code. Si celui-ci n'est pas entré ou incorrect lors de la création de l'utilisateur,
la console vous demandera si vous souhaitez le créer vous-même ou le générer automatiquement.
Le choix de génération automatique vous permettra de générer un code aléatoire de 6 caractères, comme lorsque vous créez
votre compte utilisateur depuis le site.

## Activer / Désactiver le mode maintenance :
Aller dans le fichier .env et changer la valeur de la variable ```MAINTENANCE``` à true ou false.

## Utilisation du site :

Vous pouvez accéder à toutes les pages du site via la barre de navigation sur le côté gauche de l'écran.
Toutes les informations supplémentaires sont directement placées sur les pages concernées.
Les crédits du site se trouvent dans l'onglet dédié.

ATTENTION ! Si une erreur 500 est levée sur toutes les pages, faites comme suit (source : https://symfony-com.translate.goog/doc/3.x/setup/file_permissions.html?_x_tr_sl=en&_x_tr_tl=fr&_x_tr_hl=fr&_x_tr_pto=sc#1-use-the-same-user-for-the-cli-and-the-web-server) :
- Ouvrez un terminal sur le page de votre conteneur Docker web
- Tapez la commande suivante : ```HTTPDUSER=$(ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1)```
- Puis tapez : ```sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:$(whoami):rwX var```
- Enfin : ```sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:$(whoami):rwX var```

Note : si les 'sudo' ne marchent pas, ne les mettez pas, cela fonctionnera tout de même. Toutefois, si cela ne fonctionne toujours pas après avoir fait toutes ces commandes, modifiez le fichier .env et passer la variable APP_ENV=prod à APP_ENV=dev 

