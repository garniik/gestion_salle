## Gestionaire de salle dans le but d'un projet de stage 


## objectifs:
- creation evenement
    - nom de l'evenement
    - date
    - place disponible

- affichage card d'evements en grille cliquable :
    -  nom de l'evenement
    - date
    - place disponible 


- affichage de la salle et les place disponible
    - affichage des places reservé
    - cliquer sur une place
        - nom prenom reservation
    - barre de recherche place (par nom/prenom ou N° place)




## methodes possibles :
- web (php model mvc)
    - base de donnée


## basse de donnée : 
- evenement
    - id
    - nom
    - date
    - place disponible

- place
    - id
    - N° place
    - nom
    - prenom
    - id evenement


## soucis a reglé :
- bug connexion champ vides
- responsive pas correcte
    - revoir affichage liste reservation
- liste reservation 
    - si chercher place alors pas de resultat
    - n'affiche que les places afficher dans la liste (si recherche une personne une seul place visible)
        - possibilité de reservé 10x la meme place avec ce bug
    