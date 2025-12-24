# 💎 Studio Onyx - Suite de Gestion de Projet Premium

![Version](https://img.shields.io/badge/version-1.0.0-c5a059?style=flat-square)
![License](https://img.shields.io/badge/license-Proprietary-black?style=flat-square)
![PHP](https://img.shields.io/badge/backend-PHP%208.0+-777bb4?style=flat-square)
![JS](https://img.shields.io/badge/frontend-ES6%20Modules-yellow?style=flat-square)

**Studio Onyx** est une solution de gestion de projet (PMS) conçue spécifiquement pour les agences créatives exigeantes. Alliant une esthétique "Luxe" (Noir & Or) à une architecture technique modulaire, elle permet une centralisation fluide des opérations : suivi budgétaire, pilotage des délais, répartition de la charge équipe et analyse de la performance.

---

## 📑 Table des Matières

1.  [Philosophie du Design](#-philosophie-du-design-uiux)
2.  [Fonctionnalités Détaillées](#-fonctionnalités-détaillées)
3.  [Architecture Technique](#-architecture-technique)
4.  [Guide d'Installation](#-guide-dinstallation)
5.  [Manuel Utilisateur](#-manuel-utilisateur)
6.  [Structure du Code](#-structure-du-code)
7.  [Évolutions Futures](#-roadmap-évolutions)

---

## 🎨 Philosophie du Design (UI/UX)

L'identité visuelle de **Studio Onyx** repose sur une direction artistique stricte, définie pour favoriser la concentration tout en offrant une expérience utilisateur valorisante.

* **Palette de Couleurs (Onyx & Gold) :**
    * **Couleur Primaire :** `#c5a059` (Premium Gold) - Utilisée pour les actions principales, les états actifs et les mises en avant.
    * **Couleur de Fond :** `#000000` & `#1a1a1a` - Pour une immersion totale et une réduction de la fatigue visuelle (Dark Mode).
    * **Typographie :** *Inter*, choisie pour sa lisibilité sur les interfaces denses.
* **Expérience Fluide (SPA-like) :** Bien que l'application soit multipage (Login / Dashboard), la navigation interne au Dashboard se fait sans rechargement de page grâce à la manipulation dynamique du DOM via JavaScript, offrant une réactivité instantanée.
* **Responsive Design :** L'interface s'adapte du mobile au grand écran, avec un menu latéral (`Sidebar`) qui devient un tiroir escamotable (`Offcanvas`) sur les petits écrans.

---

## ✨ Fonctionnalités Détaillées

### 1. Gestion de Projets (Core)
* **Création Intuitive :** Formulaire modal permettant de définir le nom, le budget, la date limite et une image de couverture (URL).
* **Calcul de Progression Automatique :** L'algorithme calcule le pourcentage d'avancement de chaque projet en temps réel : `(Tâches "Fait" / Total des tâches du projet) * 100`. Si aucune tâche n'est liée, la progression reste à 0%.
* **Indicateurs Visuels :** Badge budgétaire et barre de progression intégrés directement dans les cartes projets ("Cards").

### 2. Pilotage des Tâches (Task Management)
* **Matrice de Priorité :** Classification des tâches selon 4 niveaux d'urgence : *Basse, Moyenne, Haute, Urgent*. Un code couleur spécifique est appliqué (Gris, Jaune, Rouge, Rouge Vif).
* **Détection de Retard (Overdue Logic) :** Le système compare la date d'échéance de la tâche avec la date du jour. Si la tâche n'est pas "Fait" et que la date est passée, la date s'affiche en rouge avec une icône d'alerte ⚠️.
* **Moteur de Recherche & Filtres :** Recherche instantanée par nom ou assignation, et filtrage par niveau de priorité via liste déroulante.
* **Tri Dynamique :** Possibilité de trier le tableau des tâches par colonne (Nom, Priorité, Date) via une fonction de tri générique supportant les chaînes de caractères et les objets Date.

### 3. Dashboard Analytique
* **KPIs en Temps Réel :** Compteurs dynamiques pour le nombre total de projets, de tâches, la taille de l'équipe active (déduite des tâches assignées) et la somme totale des budgets.
* **Visualisation de Données (Data Viz) :**
    * *Doughnut Chart (Chart.js)* : Répartition des statuts des tâches.
    * *Area Chart (Chart.js)* : Analyse de la charge de travail par date d'échéance.

### 4. Administration & Sécurité
* **Authentification PHP :** Protection des routes via `session_start()`. Toute tentative d'accès direct au dashboard sans session redirige vers le login.
* **Mode "Zone de Danger" :** Fonctionnalité critique permettant de purger l'intégralité de la base de données JSON pour repartir à zéro.
* **Système de Support :** Formulaire de contact intégré qui enregistre les requêtes utilisateurs dans un fichier de log serveur (`support_logs.txt`).

---

## 🏗 Architecture Technique

Le projet suit une architecture **MVC simplifiée** (Modèle-Vue-Contrôleur) adaptée à une stack légère sans base de données SQL.

### Backend (PHP & JSON)
* **API (`api.php`) :** Agit comme le contrôleur principal.
    * `GET` : Lit le fichier `data.json` et renvoie les données au format JSON.
    * `POST` : Reçoit le nouvel état complet (Projets + Tâches) et écrase le fichier `data.json` avec un verrouillage de fichier (`LOCK_EX`) pour éviter les conflits d'écriture.
* **Persistance (`data.json`) :** Base de données NoSQL fichier-plat. Structure :
    ```json
    {
      "projects": [ ... ],
      "tasks": [ ... ]
    }
    ```

### Frontend (JavaScript Modules)
Le code JS est modulaire (ES6) pour une meilleure maintenance :
* `main.js` : Point d'entrée. Gère l'état global (`state`), les écouteurs d'événements et la coordination.
* `api.js` : Couche de service responsable uniquement des appels `fetch()` vers le serveur.
* `ui.js` : Responsable du rendu HTML (injection dans le DOM) et de la mise à jour des graphiques Chart.js.
* `utils.js` : Fonctions utilitaires pures (formatage de date, tri, système de notifications "Toasts").

---

## 🚀 Guide d'Installation

### Pré-requis
* Serveur Web (Apache/Nginx) avec **PHP 7.4 ou supérieur**.
* Navigateur moderne (Support des modules ES6).

### Déploiement
1.  **Copie des fichiers :** Placez l'ensemble des fichiers à la racine de votre dossier public (ex: `/var/www/html/` ou `htdocs`).
2.  **Permissions :** Assurez-vous que le serveur web a les droits d'écriture sur le répertoire racine pour pouvoir créer et modifier :
    * `data.json`
    * `support_logs.txt`
3.  **Lancement :** Accédez à `login.php` via votre navigateur.

---

## 📖 Manuel Utilisateur

### 1. Connexion
Utilisez les identifiants administrateur par défaut (configurés dans `auth.php`) :
* **Email :** `admin@elite.com`
* **Mot de passe :** `admin`

### 2. Démarrage Rapide
1.  **Créer un Projet :** Allez dans l'onglet "Projets" > "Nouveau Projet". Remplissez les infos et le budget.
2.  **Ajouter des Tâches :** Depuis le Dashboard ou via le bouton "+" rapide, créez une tâche.
    * *Important :* Sélectionnez le projet parent dans la liste déroulante.
    * Assignez un membre de l'équipe (Alice, Marc, Julie, Thomas, Sarah).
3.  **Suivi :** Observez les graphiques se mettre à jour automatiquement sur le Dashboard.

### 3. Personnalisation (Dark Mode)
Cliquez sur l'icône de Lune/Soleil dans la barre de navigation ou activez le switch dans l'onglet "Paramètres". La préférence est sauvegardée localement dans votre navigateur.

---

## 📂 Structure du Code

```bash
/
├── api.php             # API REST (Endpoint GET/POST)
├── auth.php            # Logique d'authentification serveur
├── dashboard.php       # Vue principale (HTML + Structure)
├── styles.css          # Feuille de style (Variables CSS, Thèmes)
├── login.php           # Page de connexion
├── js/
│   ├── main.js         # Contrôleur frontend principal
│   ├── api.js          # Services HTTP (Fetch)
│   ├── ui.js           # Gestion du DOM & Charts
│   └── utils.js        # Helpers (Date, Sort, Toast)
└── assets/             # (Images externes chargées via URL)