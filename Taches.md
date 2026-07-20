# Taches effectuees - Mobile Money

## Livraison v1

### Etudiant 1 - etu004226
cote client

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
    - logout (deconnexion, destruction de session)
    - dashboard (affichage de l'information de l'utilisateur)
    - doOperation (verifie chaque operation que l'utilisateur fait et inserer une historique ainsi que met a jour le solde)
    - operation (affichage des types d'operation)
    - historique (affichage de l'historique)

VIEWS
- [ ok ] layout.php
- [ ok ] home.php
- [ ok ] client/login.php
- [ ok ] client/dashboard.php
- [ ok ] client/historique.php
- [ ok ] client/operation.php

### Etudiant 2 - etu004295
cote operateur

MODELS:
- [ ok ] AdminModel.php

CONTROLLERS:
- [ ok ] AdminController.php
    - doLogin (verification par code d'acces)
    - login (redirection vers le formulaire)
    - dashboard (afficher les gains par type d'operation)
    - clients (affiche les listes des clients)
    - clientDetail (affiche les details de chaque client)
    - tranches (affichage liste, ajout, suppression)
    - prefixes (listes, ajout, suppression)
    - logout (deconnexion)

VIEWS:
- [ ok ] admin/login.php
- [ ok ] admin/dashboard.php
- [ ok ] admin/_sidebar.php
- [ ok ] admin/clients.php
- [ ok ] admin/client_detail.php
- [ ok ] admin/tranches.php
- [ ok ] admin/prefixes.php

### Travaux communs
- [ ok ] Mise en place du schema de base (base.sql)
- [ ok ] Configuration du projet CodeIgniter 4 + SQLite3

Version 2:

MODEL:
-  [ ok ] modification prefixe
    - ajout champ operateur
    - methode getOPerateurByteTelephone
- [ ok ] Commissions
    - CommisssionsINterOperateurModel.php
CONTROLLER:
-  [ ok ] modification prefixe
    - ajout updat() dans prefixe Controller
- [ ok ] Commissions
    - commissions()
    -  addCommission()
    - updateCommission(int $id)
    - deleteCommission(int $id)
VIEW:
-  [ ok ] modification prefixe
    - admin/prefixes/index
    - admin/prefixes/form
- [ ok ] Commissions
    -commissions.php
Route: 
-  [ ok ] modification prefixe
    - admin/prefixes/add -> AdminController (addPrefixe)
- [ ok ] Commissions
    - admin/commissions -> AdminController(commissions)
    -  admin/commissions/add -> AdminController(addCommission)
    - admin/commissions/update/(:num) -> AdminController(updateCommission/$1)
    - admin/commissions/delete/(:num) -> AdminController(deleteCommission/$1)

---

## Livraison v2 - Version 2 (Tag v2)

### Etudiant 1 - etu004226
cote client - V2

NOUVEAUX MODELS:
- [ ok ] CommissionInterOperateurModel.php
    - getCommission(operateur) : retourne le taux de commission inter-operateur
- [ ok ] SoldeOperateurModel.php
    - ajouterMontant(operateur, montant) : ajoute le montant du a un operateur
    - getMontant(operateur) : retourne le montant du a un operateur

MODELS MODIFIES:
- [ ok ] PrefixeModel.php
    - getOperateurByTelephone(telephone) : retourne l'operateur d'un numero
    - isOwnOperator(telephone, monOperateur) : verifie si un numero est du meme operateur
    - allowedFields ajoute : 'operateur'
- [ ok ] HistoriqueModel.php
    - allowedFields ajoutes : commission, operateur_destinataire, montant_recu, montant_debit

CONTROLLER MODIFIE:
- [ ok ] ClientController.php
    - traiterTransfertSimple() : logique frais_inclus pour meme/autre operateur
    - traiterEnvoiMultiple() : envoi multiple avec calcul individuel par destinataire
    - Logique inter-operateur :
        - Meme operateur + frais_inclus = true  -> total = montant, destinataire recoit montant - frais
        - Meme operateur + frais_inclus = false -> total = montant + frais, destinataire recoit montant
        - Autre operateur + frais_inclus = true  -> total = montant + frais, destinataire recoit montant + commission
        - Autre operateur + frais_inclus = false -> total = montant + frais + commission, destinataire recoit montant + commission
    - Enregistrement dans historique : commission, operateur_destinataire, montant_recu, montant_debit
    - Mise a jour solde_operateur pour transferts vers autres operateurs

VUES MODIFIEES:
- [ ok ] client/operation.php
    - Checkbox "Inclure les frais" pour retrait ET transfert
    - Detection automatique de l'operateur destinataire (badge visuel)
    - Resume des frais complet avant validation (JS)
    - Affichage : montant, frais, commission, total debite, montant recu
    - Envoi multiple avec tableau detaille par destinataire
- [ ok ] client/historique.php
    - Nouvelles colonnes : Commission, Operateur destinataire, Montant recu, Total debite
    - Badges colores par operateur (vert = meme, jaune = autre)
    - Badge "Multiple" pour les envois multiples

BASE DE DONNEES:
- [ ok ] base.sql (schema V2 integre directement)
    - Table historique_client avec colonnes : commission, operateur_destinataire, montant_recu, montant_debit
    - Table prefixe avec colonne operateur
    - Table commission_inter_operateur (1% pour airtel)
    - Table solde_operateur
    - Vue vue_gains_par_type_v2
    - Donnees de test : 4 prefixes (033,037 = telma; 032 = orange; 031 = airtel)
    - Clients de test Airtel (031) avec solde 500 000 Ar et 5 000 Ar

BUG FIXES V2:
- [ ok ] Correction critique : verfication solvabilite AVANT les transferts dans envoi multiple
- [ ok ] Correction : montant negatif quand frais > montant (meme operateur + frais_inclus)
- [ ok ] Correction : variable JS nbAutre -> nbAutres (typo)
- [ ok ] Correction : transfert inter-operateur ne doit pas credit dans table utilisateur ( argent va dans solde_operateur )
- [ ok ] Correction : login restreint aux clients Airtel (031) uniquement
- [ ok ] Correction : operateur own = airtel (pas telma)
- [ ok ] Correction : service('db') non disponible dans vues -> passe via controller
- [ ok ] Correction : .env database path override supprime (utilise WRITEPATH par defaut)
- [ ok ] Correction : tous les Modeles rendus defensifs (is_array + isset)

VUES MODIFIEES (DESIGN V2):
- [ ok ] layout.php : Google Fonts Inter, CSS variables, navbar gradient, flash auto-dismiss
- [ ok ] _sidebar.php : partial partage client/operateur
- [ ok ] home.php : design hero moderne
- [ ok ] client/login.php : design ameliore + placeholder 031
- [ ok ] client/dashboard.php : solde anime gradient, quick actions cards
- [ ok ] client/operation.php : formulaire stylise, switch toggle, resume anime
- [ ok ] client/historique.php : tableau ameliore, badges operateur
- [ ok ] client/profil.php : design coherant avec sidebar

OPERATEUR V2 (ETUDIANT 1):
- [ ok ] admin/dashboard.php : gains par type + gains par operateur + soldes operateurs
- [ ok ] AdminController.php : donnees V2 (gainsParOperateur, soldesOperateurs)
- [ ok ] HistoriqueModel.php : totalGainsParOperateur()
