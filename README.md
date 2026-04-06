<div align="center">
    <h2>🎓 Plateforme Web de Gestion de Stages et Encadrement</h2>
    <p>Une application web moderne pour g&eacute;rer les stages, le suivi des stagiaires, les entreprises et les &eacute;valuations avec des fonctionnalit&eacute;s avanc&eacute;es d'export et de tableau de bord.</p>

<p align="center">
    <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
    <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
    <img src="https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
    <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
</p>
</div>

---

## 🌟 Aperçu du Projet

Ce projet a été conçu pour simplifier et digitaliser le processus d'encadrement des stagiaires au sein d'une organisation. Grâce à une interface intuitive et des rôles bien définis, les différents acteurs (Administrateurs, Encadrants et Stagiaires) peuvent interagir efficacement et suivre l'avancement des stages en temps réel.

## ✨ Fonctionnalités Clés

- **🏠 Tableaux de Bord Dynamiques et Rôle-spécifiques :**
  - **Stagiaires** : Suivi de leur stage en cours, jours restants, notes d'évaluation, et statistiques de jour.
  - **Encadrants** : Vue d'ensemble sur les stagiaires assignés, stages actifs, visites à venir, et journaux récents.
  - **Administrateurs** : Statistiques globales du système (nombre total de stages, entreprises, visites prévues).

- **👥 Gestion Complète (CRUD) :**
  - Stagiaires & Encadrants
  - Entreprises d'accueil
  - Stages (Sujet, Dates, Types)

- **📝 Suivi Continu :**
  - **Journaux de Stage** : Les stagiaires peuvent remplir leurs avancées régulières.
  - **Visites d'Évaluation** : Planification et suivi de visites sur site par les encadrants.
  - **Évaluations** :  Saisie des notes et commentaires.

- **📄 Exports et Imports Avancés :**
  - Génération de **PDFs** (Attestations de stage, Rapports d'évaluation) via `barryvdh/laravel-dompdf`.
  - Import et Export en masse vers/depuis **Excel** pour les Stagiaires, Stages et Entreprises via `maatwebsite/excel`.

## 🛠️ Stack Technologique

- **Backend:** Laravel 12.0 (PHP ^8.2)
- **Frontend:** Blade Templating, Vite
- **Base de Données:** MySQL / MariaDB (ou SQLite pour le dev)
- **Authentification:** Laravel Breeze / Sanctum
- **Outils tiers:** DomPDF, Laravel Excel

## 🚀 Installation & Lancement Rapide

Suivez ces étapes pour exécuter le projet localement :

1. **Cloner le dépôt**
   ```bash
   git clone https://github.com/aminelabzae/EncadrementDuStage.git
   cd EncadrementDuStage
   ```

2. **Installer les dépendances Backend**
   ```bash
   composer install
   ```

3. **Installer les dépendances Frontend**
   ```bash
   npm install
   ```

4. **Configurer l'environnement**
   Copiez le fichier d'environnement et générez une clé d'application :
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   > N'oubliez pas de configurer les informations d'accès à votre base de données dans le fichier `.env`.

5. **Exécuter les Migrations et Seeders**
   ```bash
   php artisan migrate --seed
   ```

6. **Lancer le serveur de développement**
   Ouvrez deux terminaux séparés et exécutez les deux commandes suivantes :
   ```bash
   php artisan serve
   ```
   ```bash
   npm run dev
   ```

7. **Accéder à l'application**
   Rendez-vous sur [http://localhost:8000](http://localhost:8000).

## 🔒 Règles de Contribution

- Les fichiers d'environnement (`.env`) et autres fichiers temporaires (`.bkp`, `.py`) doivent rester non-suivis dans Git.
- Les rapports d'erreur de la BDD et le cache doivent être ignorés.

---

<p align="center">
  Développé pour l'optimisation du processus de suivi et d'encadrement des stages.
</p>
