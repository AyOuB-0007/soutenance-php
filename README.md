# 🍽️ Système de Gestion de Restaurant

[![Symfony](https://img.shields.io/badge/Symfony-6.4-000000?style=for-the-badge&logo=symfony)](https://symfony.com/)
[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php)](https://php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql)](https://mysql.com/)

> Application web complète pour la gestion moderne d'un restaurant - Réservations, boutique en ligne, dashboard administrateur

## 📋 Table des matières

- [🎯 Aperçu du projet](#-aperçu-du-projet)
- [✨ Fonctionnalités](#-fonctionnalités)
- [🛠️ Technologies utilisées](#️-technologies-utilisées)
- [🚀 Installation](#-installation)
- [📱 Captures d'écran](#-captures-décran)
- [👥 Équipe](#-équipe)
- [📄 Documentation](#-documentation)

## 🎯 Aperçu du projet

Ce projet est un **système complet de gestion de restaurant** développé avec Symfony. Il permet aux restaurants de moderniser leurs opérations en offrant :

- 🏠 **Site vitrine** avec présentation du restaurant
- 📅 **Système de réservation** en ligne
- 🛒 **Boutique** pour commander des produits
- 👤 **Gestion des profils** utilisateur
- ⚙️ **Dashboard administrateur** complet
- 📊 **Statistiques** en temps réel

## ✨ Fonctionnalités

### 🌟 Côté Client
- **Page d'accueil attractive** avec carrousel de produits
- **Réservation en ligne** avec validation en temps réel
- **Boutique interactive** avec panier de commande
- **Authentification sécurisée** (inscription/connexion)
- **Profil utilisateur** avec gestion des réservations
- **Interface responsive** (mobile, tablette, desktop)

### 🔧 Côté Administrateur
- **Dashboard centralisé** avec statistiques
- **Gestion des produits** (CRUD complet)
- **Gestion des catégories** de produits
- **Gestion des employés** par département
- **Gestion des réservations** avec attribution de tables
- **Interface intuitive** avec navigation par onglets

### 🔐 Sécurité
- **Authentification Symfony Security**
- **Hashage des mots de passe** (bcrypt)
- **Système de rôles** (USER/ADMIN)
- **Protection CSRF** sur tous les formulaires
- **Validation des données** côté serveur et client

## 🛠️ Technologies utilisées

### Backend
- **Framework :** Symfony 6.4
- **Langage :** PHP 8.2
- **Base de données :** MySQL 8.0
- **ORM :** Doctrine
- **Authentification :** Symfony Security Bundle

### Frontend
- **Templates :** Twig
- **Styles :** CSS3 (Flexbox, Grid)
- **JavaScript :** ES6 (Vanilla JS)
- **Icônes :** Font Awesome
- **Design :** Responsive, Mobile-First

### Outils
- **Gestionnaire de dépendances :** Composer
- **Contrôle de version :** Git
- **Serveur de développement :** Symfony CLI

## 🚀 Installation

### Prérequis
- PHP 8.2 ou supérieur
- Composer 2.x
- MySQL 8.0 ou supérieur
- Symfony CLI (optionnel)

### Étapes d'installation

1. **Cloner le repository**
```bash
git clone https://github.com/AyOuB-0007/soutenance-php.git
cd soutenance-php
```

2. **Installer les dépendances**
```bash
composer install
```

3. **Configurer la base de données**
```bash
# Copier le fichier d'environnement
cp .env .env.local

# Modifier DATABASE_URL dans .env.local
DATABASE_URL="mysql://username:password@127.0.0.1:3306/restaurant_db"
```

4. **Créer la base de données**
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

5. **Charger les données de test (optionnel)**
```bash
php bin/console doctrine:fixtures:load
```

6. **Démarrer le serveur**
```bash
# Avec Symfony CLI
symfony server:start

# Ou avec PHP
php -S localhost:8000 -t public/
```

7. **Créer un utilisateur administrateur**
```bash
php bin/console app:promote-user admin@restaurant.com ROLE_ADMIN
```

### 🌐 Accès à l'application
- **Site public :** http://localhost:8000
- **Dashboard admin :** http://localhost:8000/dashboard (nécessite ROLE_ADMIN)

## 📱 Captures d'écran

### 🏠 Page d'accueil
- Carrousel de produits vedettes
- Formulaire de réservation intégré
- Grille de catégories interactive
- Statistiques en temps réel

### 🛒 Boutique
- Catalogue de produits par catégories
- Système de panier dynamique
- Interface de commande fluide

### ⚙️ Dashboard Administrateur
- Vue d'ensemble avec statistiques
- Gestion complète des produits
- Interface de gestion des réservations
- Outils d'administration

## 👥 Équipe

Ce projet a été réalisé par une équipe de 3 étudiants :

| Nom | Rôle | Contributions principales |
|-----|------|---------------------------|
| **Ghali KHARMOUDY** | Lead Developer | Architecture, Backend, Sécurité |
| **Ayoub OUHDACH** | Frontend Developer | Interface utilisateur, Design |
| **Oussama HARKATY** | Full-Stack Developer | Fonctionnalités, Tests |

**Encadrant :** Dr. Zineb HIDILA

## 📄 Documentation

### 📚 Documents disponibles
- **[Guide technique](GUIDE_TECHNIQUE_SOUTENANCE.md)** - Explications détaillées pour la soutenance
- **[Rapport de projet](RAPPORT_PROJET_RESTAURANT.md)** - Rapport complet (25+ pages)

### 🗂️ Structure du projet
```
gestion-restaurant/
├── config/              # Configuration Symfony
├── migrations/          # Migrations de base de données
├── public/              # Fichiers publics (CSS, JS, images)
├── src/
│   ├── Controller/      # Contrôleurs
│   ├── Entity/          # Entités Doctrine
│   ├── Repository/      # Repositories
│   └── Command/         # Commandes console
├── templates/           # Templates Twig
└── var/                 # Cache et logs
```

### 🔗 Liens utiles
- **[Documentation Symfony](https://symfony.com/doc/current/index.html)**
- **[Documentation Doctrine](https://www.doctrine-project.org/)**
- **[Documentation Twig](https://twig.symfony.com/)**

## 🚀 Fonctionnalités avancées

### 🎨 Interface utilisateur
- Design moderne et épuré
- Animations CSS fluides
- Modals interactives
- Feedback visuel en temps réel

### 📊 Dashboard administrateur
- Statistiques dynamiques
- Gestion CRUD complète
- Interface par onglets
- Actions en AJAX

### 🔒 Sécurité renforcée
- Authentification robuste
- Contrôle d'accès granulaire
- Protection contre les attaques courantes
- Validation stricte des données

## 🎯 Perspectives d'évolution

### Court terme
- 💳 Système de paiement en ligne
- 📧 Notifications par email
- 📊 Statistiques avancées

### Moyen terme
- 📱 Application mobile
- 🎁 Programme de fidélité
- 🌐 Multi-langue

### Long terme
- 🤖 Intelligence artificielle
- 📈 Business Intelligence
- 🔗 Intégrations tierces

## 📞 Contact

Pour toute question concernant ce projet :

- **Email :** kharmoudy.ghali@gmail.com
- **GitHub :** [@AyOuB-0007](https://github.com/AyOuB-0007)

---

## 📜 Licence

Ce projet est développé dans un cadre éducatif. Tous droits réservés.

---

**⭐ Si ce projet vous plaît, n'hésitez pas à lui donner une étoile !**

*Développé avec ❤️ par l'équipe de développement*