# Taches effectuees - Mobile Money

## Livraison v1 - [date]

### Etudiant 1 - etu004226
cote utilisateur

MODELS:
- [ ok ] UtilisateurModel.php 
- [ ok ] PrefixeModel.php
- [ ok ] TypeOperationModel.php
- [ ok ] TrancheMontantModel.php
- [ ok ] HistoriqueModel.php

CONTROLLERS
- [ ok ] ClientController.php
    - doLogin (verifie le num)
    - login (redirige vers le formulaire)
    - ensureLoggedIn (assurer que l'utilisateur est connecte)
    - logout (deconnexion , destruction de )
    - dashboard (affichage de l'information de l'utilisateur )
    - doOperation (verifie chaque operation que l'utilisateur fait et inserer une historique ainsi que met a jour le solde)
    - operation (affichage des types d'operation)
    - historique (affichage de l'historique)

ROUTES 

VIEWS
- [ ok ] layout.php
- [ ok ] home.php
- [ ok ] client
    - login.php
    - dashboard.php
    - historique.php
    - operation.php

    



### Etudiant 2 - etu004295
cote operateur

### Travaux communs
- [ ok ] Mise en place du schema de base (base.sql)
- [ ok ] Configuration du projet CodeIgniter 4 + SQLite3
- [ ok ] Todolist du projet

MODELS:
- [ ok ] AdminModel.php

CONTROLLERS:
- [ ok ] AdminController.php
    - doLogin(verification par code d'acces)
    - login (redirection ver la formulaire)
    - dashboard (afficher les gains par type d'operation)
    - client (affiche les listes des clients)
    - client details ( affiche les details de chaque client)
    - gestion des tranches (affichage liste, ajout, suppression)
    - configuration des prefixes ( listes, ajout, suppersion)
    - logout(deconnexion)
 
ROUTES: [ ok ]
- /admin/login -> AdminController(doLogin)
- /admin/login -> AdminController(login)
- admin/dashboard -> AdminController(dashboard)
- admin/clients -> AdminController(clients)
- admin/clients/(:num) -> AdminController(clientDetail/$1)

- admin/tranches -> AdminController(tranches)
- admin/tranches/add -> AdminController(addTranche)
- admin/tranches/delete/(:num) -> AdminController(deleteTranche/$1)
- admin/prefixes -> AdminController(prefixes)
- admin/prefixes/add -> AdminController(addPrefixe)
- admin/prefixes/delete/(:num) -> AdminController(deletePrefixe/$1)
- admin/logout -> AdminController(logout)


VIEWS:
- [ ok ] home.php
- [ ok ] layout.php
- [ ok ] admin
    - login.php
    - dashboard.php
    - _sidebar.php
    - client.php
    - client_details.php
    - tranches.php
    - prefixes.php






