# Projet Autoécole

## Description
Système de gestion d'auto-école développé en PHP avec une architecture MVC (Modèle-Vue-Contrôleur). Permet la gestion des candidats, moniteurs, leçons, véhicules, facturation et planning.

## Fonctionnalités
- Gestion des candidats (inscription, modification, suppression)
- Gestion des moniteurs
- Gestion des leçons de conduite
- Gestion du parc automobile
- Facturation
- Planning des cours
- Espace élève

## Technologies utilisées
- PHP
- MySQL
- HTML5
- CSS3
- JavaScript (si applicable)

## Installation
1. Assurez-vous d'avoir XAMPP installé et démarré (Apache et MySQL).
2. Clonez ou copiez le projet dans le répertoire `htdocs` de XAMPP (par exemple : `C:\xampp\htdocs\projet_autoecole`).
3. Importez les fichiers SQL dans votre base de données MySQL :
   - `sql/auth.sql` pour l'authentification
   - `sql/auto_ecole.sql` pour la structure principale
4. Configurez la connexion à la base de données dans `modele/modele.autoecole.php` si nécessaire.
5. Accédez au projet via votre navigateur : `http://localhost/projet_autoecole`

## Utilisation
- Page d'accueil : `index.php`
- Connexion : `login.php`
- Espace élève : `front/mon_espace.php`
- Interfaces d'administration : via les contrôleurs dans `controleur/`

## Structure du projet
- `Vue/` : Vues de l'application
- `controleur/` : Contrôleurs pour la logique métier
- `modele/` : Modèles pour l'accès aux données
- `sql/` : Scripts SQL pour la base de données
- `assets/` : Ressources statiques (CSS, images)
- `front/` : Pages frontend publiques

## Dépannage
- Vérifiez que Apache et MySQL sont démarrés dans XAMPP.
- Assurez-vous que les permissions des fichiers permettent l'exécution PHP.
- Consultez les logs d'erreur PHP pour les problèmes de base de données.