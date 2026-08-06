# Projet-DWWM

**FitBooking** est un site web qui permet la gestion et la réservation de séances de sport. Du debutant au sportif confirmé le site permet aux utilisateurs de planifier leurs entraînements parmis trois disciplines : 
***la musculation, la boxe et le cardio.***

Que ce soit pour des cours collectifs, du coaching privé,
la plateforme s'adresse aux personnes souhaitant pratiquer une activité sportive de manière flexible, 
qu'elles soient débutantes ou expérimentées.
La plateforme répond aux besoins des utilisateurs recherchant une solution simple afin de planifier leurs entraînements et gérer leurs réservations en ligne.

# Fonctionnalités

### Utilisateur

 - *Création de compte utilisateur*
 - *Connexion/Déconnexion*
 - *Accès au catalogue d'activité des trois univers (musculation,boxe,cardio)*
 - *Accès au calendrier des cours*
 - *Réserver un créneau*
 - *Consulter et annuler ss réservations*

### Administrateur

- *Ajouter,modifier et supprimer des cours*
- *Modifier,supprimer les catégories*
- *Consulter les statistiques des reservations*
- *Consulter la liste des participants inscrits par session*
- *Consulter la liste des utilisateurs inscrits sur le site*
- *Desinscrire un utilisateur d'une séance*

# User Stories


### Epic 1 (MVP) : Authentification et gestion des comptes

**User Story 1 : En tant que visiteur, je veux pouvoir créer un compte avec mon email et mon mot de passe afin d'accéder à la plateforme.**

*CA 1 : Le visiteur a accès un formulaire d'inscription*

*CA 2 : Il doit obligatoirement entrer un email unique.*

*CA 3 : Le compte est créé après validation des informations.*

**User Story 2 : En tant que visiteur, je veux pouvoir me connecter avec mes identifiants afin d'accéder à mon espace membre.**

*CA 1 : Le visiteur a accès a un formulaire de connexion est disponible.*

*CA 2 : Lorsque les identifiants sont valides l'utilisateur est connecté.*

*CA 3 : Un message d'erreur est affiché si les identifiants sont incorrects.*

### Epic 2 (MVP) : Consultation et réservation des cours

**User Story 3 : En tant que membre, je veux pouvoir consulter le planning avec tous les cours disponibles afin de choisir une activité.**

*CA 1 : Le membre a accès à la liste des cours à venir.*

*CA 2 : Chaque cours affiche sa date, son horaire et sa capacité maximum.*

*CA 3 : Les cours passés ne sont pas affichés dans le planning principal.*


**User Story 4 : En tant que membre, je veux voir le détail d'un cours afin d'obtenir toutes les informations nécessaires avant de réserver.**

*CA 1 : Le détail du cours affiche le titre du cours, la description, le coach en question et l'horaire.*

*CA 2 : Le nombre de places restantes est affiché.*

*CA 3 : La catégorie du cours est visible.*

*CA 4 : Le nom du coach du cours est visible.*

**User Story 5 : En tant que membre, je veux pouvoir réserver un cours disponible afin de participer à la séance.**

*CA 1 : Le Membre a accès à un bouton de réservation.*

*CA 2 : La réservation est enregistrée si des places sont disponibles.*

*CA 3 : Une confirmation est affichée après réservation.*

**User Story 6 : En tant que membre, je veux pouvoir voir la liste de mes réservations à venir afin de suivre mes inscriptions.**

*CA 1 : Toutes les réservations de l'utilisateur sont affichées.*

*CA 2 : Les informations détaillés du cours sont visibles.*

*CA 3 : Les réservations sont triées par date.*

**User Story 7 : En tant que membre, je veux pouvoir annuler une réservation afin de libérer ma place.**

*CA 1 : Le Membre peut annuler son cours via un bouton d'annulation.*

*CA 2 : La réservation disparaît de la liste après annulation.*

*CA 3 : La place est remise à disposition.*

**User Story 8 : En tant que membre, je veux pouvoir filtrer le planning par catégorie (cardio, musculation, boxe) afin de trouver rapidement un cours.**

*CA 1 : Le Membre a accès à une liste defilante qui filtre les catégories de chaque cours.*

*CA 2 : Le planning est mis à jour selon le filtre sélectionné.*

*CA 3 : Plusieurs changements de filtre sont possibles sans recharger la page.*

**User Story 9 : En tant que membre, je veux voir l'historique de mes cours passés afin de consulter mes activités précédentes.**

*CA 1 : Le Membre peut avoir accès a ses cours déjà suivis.*

*CA 2 : Les cours sont classés du plus récent au plus ancien.*

*CA 3 : Les informations principales du cours sont visibles.*

**User Story 15 : En tant que membre, je veux pouvoir rechercher une séance par son nom afin de la trouver rapidement.**

*CA 1 : Une barre de recherche textuelle est disponible sur la page du planning.*

*CA 2 : Les résultats se mettent à jour dynamiquement en fonction des caractères saisis.*

*CA 3 : Un message s'affiche si aucun cours ne correspond à la recherche.*

**User Story 16 : En tant que membre, je veux pouvoir consulter la liste des coachs ainsi que leur fiche détaillée afin de connaître leurs spécialités.**

*CA 1 : Un onglet ou une page dédiée liste l'ensemble des coachs de la plateforme.*

*CA 2 : Cliquer sur un coach ouvre sa fiche détaillée (photo, biographie, spécialités).*

**User Story 17 : En tant que membre, je veux pouvoir noter un coach et voir sa note globale afin de partager mon avis et consulter la réputation des coachs.**

*CA 1 : Le membre peut attribuer une note (système d'étoiles) à un coach après avoir effectué un cours avec lui.*

*CA 2 : La note globale moyenne est calculée automatiquement et affichée sur la fiche du coach.*

### Epic 3 (Non MVP) : Administration des cours et statistiques

**User Story 10 : En tant qu'administrateur, je veux pouvoir créer un nouveau cours afin d'enrichir le planning.**

*CA 1 : L'administrateur a accès à un formulaire de création.*

*CA 2 : Tous les champs obligatoires doivent être renseignés (Nom du cours, Categorie, Date,Lieu,Place max).*

*CA 3 : Le cours apparaît dans le planning après création.*

*CA 4 : L'administrateur doit choisir une catégorie parmis (Musculation,Boxe,Cardio)*

*CA 5 : L'administrateur peut retrouver le nom de la catégorie grace a une barre de recherche*

**User Story 11 : En tant qu'administrateur, je veux modifier ou supprimer un cours existant afin de maintenir le planning à jour.**

*CA 1 : L'administrateur peut modifier et supprimer les informations d'un cours.*

*CA 2 : Les modifications sont immédiatement visibles.*

*CA 3 : L'administrateur peut modifier la catégorie d'un cours parmi Cardio, Musculation ou Boxe.*

**User Story 12 : En tant qu'administrateur, je veux voir la liste des membres inscrits à un cours afin de gérer les participants.**

*CA 1 : L'administrateur peut avoir accès a la liste des inscrits sur un cours.*

*CA 2 : Les informations des participants sont affichées.*

*CA 3 : La liste est mise à jour après un ajout ou une annulation.*

**User Story 13 : En tant qu'administrateur, je veux voir un dashboard avec le nombre de cours et de réservations de la semaine afin de suivre l'activité.**

*CA 1 : L'administrateur peut voir le nombre total de cours.*

*CA 2 : L'administrateur peut voir le nombre total de réservations.*

**User Story 14 : En tant qu'administrateur, je veux voir le taux de remplissage de chaque cours afin d'analyser leur popularité.**

*CA 1 : Le taux de remplissage est calculé automatiquement.*

*CA 2 : Le pourcentage est affiché pour chaque cours.*

*CA 3 : Les cours peuvent être triés par taux de remplissage.*

**User Story 18 : En tant qu'admin, je veux pouvoir gérer les catégories de cours afin d'en ajouter, modifier ou supprimer.**

*CA 1 : L'administrateur dispose d'une interface de gestion des catégories.*

*CA 2 : L'ajout d'une nouvelle catégorie la rend immédiatement disponible lors de la création d'un cours.*

*CA 3 : La suppression d'une catégorie est bloquée ou demande confirmation si des cours y sont encore associés.*

**User Story 19 : En tant qu'admin, je veux pouvoir désinscrire un membre d'un cours afin de gérer les cas particuliers ou les annulations de dernière minute.**

*CA 1 : Depuis la liste des participants d'un cours, l'administrateur dispose d'un bouton de désinscription à côté de chaque membre.*

*CA 2 : Une confirmation est demandée avant de valider la désinscription.*

*CA 3 : Le membre concerné est retiré de la liste et la place redevient disponible.*
