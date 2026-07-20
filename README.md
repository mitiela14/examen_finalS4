# VINA-AKOHO - Mobile Money Simulator (CodeIgniter 4 + SQLite3)

Squelette de depart pour l'examen S4 - Version 1.

## Prerequis

- PHP >= 8.1, avec les extensions `sqlite3` et `intl` activees
- Pas besoin de Composer pour demarrer (le squelette fonctionne avec
  l'autoloader natif de CodeIgniter). Vous pouvez lancer `composer install`
  plus tard si vous ajoutez des packages tiers.

## Installation

1. Copier ce dossier / cloner le repo.
2. Verifier le fichier `.env` a la racine (deja configure pour SQLite3) :

   ```
   database.default.DBDriver = SQLite3
   database.default.database = database.db
   ```

3. Creer la base de donnees a partir de `base.sql` :

   ```bash
   mkdir -p writable/database
   sqlite3 writable/database/database.db < base.sql
   ```

4. Lancer le serveur de developpement :

   ```bash
   php spark serve
   ```

5. Ouvrir http://localhost:8080

## Comptes de test (voir base.sql)

- **Client** : n'importe quel numero commencant par `033` ou `037`
  (ex: `0331234567`, deja existant avec un solde de 15000 Ar)
- **Operateur** : code d'acces `admin123`

## Structure du projet

```
app/Controllers/ClientController.php   -> espace client
app/Controllers/AdminController.php    -> espace operateur
app/Models/                            -> UtilisateurModel, AdminModel,
                                           PrefixeModel, TypeOperationModel,
                                           TrancheMontantModel, HistoriqueModel
app/Views/client/                      -> vues client (Bootstrap)
app/Views/admin/                       -> vues operateur (Bootstrap)
base.sql                               -> schema complet + donnees de test
Taches.md                              -> suivi des taches par etudiant
```

## Regles de l'examen a ne pas oublier

- Un seul fichier `base.sql` a la racine, tenu a jour a chaque livraison.
- Mettre a jour `Taches.md` a chaque tag.
- Tag Git `v1`, `v2`, `v3` selon les livraisons, sur la branche `main`.
