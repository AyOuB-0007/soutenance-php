# RAPPORT DE PROJET

## Système de Gestion de Restaurant

### Application Web Complète développée avec Symfony

---

**Projet réalisé dans le cadre de :**  
Formation Développement Web

**Date de soutenance :**  
Janvier 2025

**Membres du groupe :**
- **Ghali KHARMOUDY**
- **Ayoub OUHDACH**
- **Oussama HARKATY**

**Encadrant :**  
Dr. Zineb HIDILA

---

## Table des matières

1. [Introduction](#1-introduction)
2. [Contexte et Objectifs](#2-contexte-et-objectifs)
3. [Analyse des Besoins](#3-analyse-des-besoins)
4. [Architecture Technique](#4-architecture-technique)
5. [Fonctionnalités Développées](#5-fonctionnalités-développées)
6. [Modèle de Données](#6-modèle-de-données)
7. [Sécurité et Authentification](#7-sécurité-et-authentification)
8. [Interface Utilisateur](#8-interface-utilisateur)
9. [Difficultés Rencontrées](#9-difficultés-rencontrées)
10. [Perspectives d'Évolution](#10-perspectives-dévolution)
11. [Conclusion](#11-conclusion)

---

## 1. Introduction

Ce rapport présente le développement d'un système complet de gestion de restaurant, conçu pour moderniser et digitaliser les opérations quotidiennes d'un établissement de restauration. Le projet a été réalisé en équipe de trois étudiants sur une période de plusieurs semaines.

### 1.1 Problématique

Les restaurants traditionnels font face à plusieurs défis :
- Gestion manuelle des réservations (téléphone, papier)
- Absence de système de commande en ligne
- Difficulté à gérer le personnel et les produits
- Manque de visibilité sur les statistiques

### 1.2 Solution Proposée

Développement d'une application web complète permettant :
- La gestion automatisée des réservations
- Un système de boutique en ligne
- Un dashboard administrateur pour la gestion globale
- Une interface moderne et responsive

---

## 2. Contexte et Objectifs

### 2.1 Contexte du Projet

Dans un contexte de digitalisation croissante du secteur de la restauration, notre projet vise à fournir une solution complète et accessible pour les restaurants de taille moyenne souhaitant moderniser leur gestion.

### 2.2 Objectifs Principaux

**Objectifs fonctionnels :**
- Permettre aux clients de réserver en ligne
- Offrir une boutique pour commander des produits
- Fournir un outil de gestion pour les administrateurs
- Assurer une expérience utilisateur fluide et intuitive

**Objectifs techniques :**
- Utiliser un framework professionnel (Symfony)
- Implémenter une architecture MVC propre
- Garantir la sécurité des données
- Assurer la scalabilité de l'application

**Objectifs pédagogiques :**
- Maîtriser le développement web full-stack
- Comprendre les patterns de conception
- Travailler en équipe sur un projet réel
- Gérer un projet de A à Z

---

## 3. Analyse des Besoins

### 3.1 Besoins Fonctionnels

#### Pour les Clients
- Consulter le menu du restaurant
- Effectuer une réservation en ligne
- Commander des produits via la boutique
- Gérer son profil et ses réservations
- Visualiser l'historique de ses commandes

#### Pour les Administrateurs
- Gérer les réservations (CRUD complet)
- Gérer les produits et catégories
- Gérer les employés
- Visualiser les statistiques
- Accéder à un dashboard centralisé

### 3.2 Besoins Non-Fonctionnels

**Performance :**
- Temps de chargement < 2 secondes
- Support de 100+ utilisateurs simultanés

**Sécurité :**
- Authentification sécurisée
- Protection contre les injections SQL
- Hashage des mots de passe
- Protection CSRF

**Ergonomie :**
- Interface intuitive
- Design responsive (mobile, tablette, desktop)
- Navigation fluide

**Maintenabilité :**
- Code structuré et commenté
- Architecture modulaire
- Documentation complète

---

## 4. Architecture Technique

### 4.1 Stack Technologique

**Backend :**
- **Framework :** Symfony 6.4
- **Langage :** PHP 8.2
- **Base de données :** MySQL 8.0
- **ORM :** Doctrine

**Frontend :**
- **Moteur de templates :** Twig
- **Styles :** CSS3 (Flexbox, Grid)
- **JavaScript :** ES6 (Vanilla JS)
- **Icônes :** Font Awesome

**Outils de développement :**
- **Gestionnaire de dépendances :** Composer
- **Contrôle de version :** Git / GitHub
- **Serveur local :** Symfony CLI

### 4.2 Architecture MVC

Notre application suit le pattern **Model-View-Controller** :

**Model (Modèle) :**
- Entités Doctrine représentant les tables
- Repositories pour les requêtes personnalisées
- Logique métier encapsulée

**View (Vue) :**
- Templates Twig pour le rendu HTML
- Séparation claire présentation/logique
- Héritage de templates (base.html.twig)

**Controller (Contrôleur) :**
- Gestion des routes et requêtes HTTP
- Orchestration entre Model et View
- Validation des données

### 4.3 Structure du Projet

```
gestion-restaurant/
├── config/              # Configuration Symfony
│   ├── packages/        # Configuration des bundles
│   └── routes.yaml      # Définition des routes
├── migrations/          # Migrations de base de données
├── public/              # Fichiers publics (CSS, JS, images)
├── src/
│   ├── Controller/      # Contrôleurs
│   ├── Entity/          # Entités Doctrine
│   ├── Repository/      # Repositories
│   ├── Command/         # Commandes console
│   └── Form/            # Formulaires
├── templates/           # Templates Twig
│   ├── base.html.twig   # Template de base
│   ├── home/            # Pages publiques
│   ├── admin/           # Dashboard admin
│   └── security/        # Authentification
└── var/                 # Cache et logs
```

---

## 5. Fonctionnalités Développées

### 5.1 Page d'Accueil

**Description :**  
Page d'accueil attractive présentant le restaurant avec :
- Carrousel de produits vedettes
- Statistiques en temps réel (réservations du jour, tables disponibles)
- Grille de catégories cliquables
- Formulaire de réservation intégré

**Technologies utilisées :**
- Twig pour le rendu dynamique
- CSS Grid pour la mise en page
- JavaScript pour les animations

### 5.2 Système d'Authentification

**Fonctionnalités :**
- Inscription avec validation des données
- Connexion sécurisée
- Gestion de session
- Système de rôles (ROLE_USER, ROLE_ADMIN)
- Modal de connexion/inscription (pas de redirection)

**Sécurité :**
- Hashage des mots de passe avec algorithme bcrypt
- Protection CSRF sur les formulaires
- Validation côté serveur
- Gestion des erreurs d'authentification

**Code exemple :**
```php
// Création d'un utilisateur
$user = new User();
$user->setEmail($email);
$user->setRoles(['ROLE_USER']);
$hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
$user->setPassword($hashedPassword);
```

### 5.3 Gestion des Réservations

**Fonctionnalités côté client :**
- Formulaire de réservation (date, heure, nombre de personnes)
- Validation en temps réel
- Confirmation visuelle
- Historique des réservations personnelles

**Fonctionnalités côté admin :**
- Vue d'ensemble de toutes les réservations
- Filtrage et recherche
- Modification et suppression
- Attribution de tables

**Processus technique :**
1. Soumission du formulaire
2. Validation des données (date future, nombre de personnes valide)
3. Création de l'entité Reservation
4. Liaison avec l'utilisateur connecté (si authentifié)
5. Persistance en base de données
6. Confirmation à l'utilisateur

### 5.4 Boutique en Ligne

**Catalogue de produits :**
- Affichage par catégories
- Filtrage dynamique
- Images et descriptions détaillées
- Prix en Dirham marocain (DH)

**Système de panier :**
- Ajout/suppression de produits
- Modification des quantités
- Calcul automatique du total
- Stockage en session PHP

**Processus de commande :**
1. Ajout de produits au panier
2. Visualisation du panier
3. Validation de la commande
4. Création d'une entité Commande
5. Liaison avec les articles commandés

### 5.5 Dashboard Administrateur

**Architecture :**
- Interface à onglets (Produits, Catégories, Employés, Réservations)
- Navigation JavaScript sans rechargement
- Cartes statistiques en temps réel

**Gestion des Produits :**
- Liste complète avec images
- Création/modification/suppression (CRUD)
- Organisation par catégories
- Upload d'images via URL

**Gestion des Catégories :**
- Création de nouvelles catégories
- Modification des catégories existantes
- Suppression (avec vérification des produits liés)

**Gestion des Employés :**
- Ajout d'employés avec informations complètes
- Organisation par catégories (Cuisine, Service, Bar...)
- Modification et suppression

**Gestion des Réservations :**
- Vue d'ensemble chronologique
- Modification des détails
- Attribution de tables
- Suppression avec confirmation

### 5.6 Profil Utilisateur

**Fonctionnalités :**
- Affichage des informations personnelles
- Historique des réservations
- Modification/annulation de réservations
- Gestion du compte

**Interface :**
- Modal "Mon Profil" avec toutes les informations
- Modal "Mes Réservations" avec actions possibles
- Design cohérent avec le reste de l'application

---

## 6. Modèle de Données

### 6.1 Schéma Relationnel

Notre base de données comprend 8 entités principales :

#### User (Utilisateurs)
- `id` : Identifiant unique
- `email` : Email (unique)
- `password` : Mot de passe hashé
- `roles` : Rôles JSON (ROLE_USER, ROLE_ADMIN)

#### Reservation (Réservations)
- `id` : Identifiant unique
- `dateHeure` : Date et heure de la réservation
- `nomClient` : Nom du client
- `nombrePersonnes` : Nombre de personnes
- `telephone` : Numéro de téléphone
- `notes` : Notes spéciales
- `user_id` : Référence vers User (nullable)
- `id_table` : Référence vers Table (nullable)

#### Menu (Catégories de produits)
- `id` : Identifiant unique
- `nomMenu` : Nom de la catégorie

#### ArticleMenu (Produits)
- `id` : Identifiant unique
- `nomArticle` : Nom du produit
- `description` : Description
- `prix` : Prix en DH
- `imageUrl` : URL de l'image
- `menu_id` : Référence vers Menu

#### Personnel (Employés)
- `id` : Identifiant unique
- `nom` : Nom complet
- `poste` : Poste occupé
- `email` : Email
- `telephone` : Téléphone

#### Commande (Commandes)
- `id` : Identifiant unique
- `dateCommande` : Date de la commande
- `montantTotal` : Montant total
- `statut` : Statut (en cours, livrée, annulée)

#### ArticleCommande (Articles dans une commande)
- `id` : Identifiant unique
- `quantite` : Quantité commandée
- `prixUnitaire` : Prix au moment de la commande
- `commande_id` : Référence vers Commande
- `article_id` : Référence vers ArticleMenu

#### Table (Tables du restaurant)
- `id` : Identifiant unique
- `numero` : Numéro de la table
- `capacite` : Nombre de places
- `statut` : Disponible/Occupée/Réservée

### 6.2 Relations entre Entités

**Relations OneToMany :**
- User → Reservation (Un utilisateur peut avoir plusieurs réservations)
- Menu → ArticleMenu (Une catégorie contient plusieurs produits)
- Commande → ArticleCommande (Une commande contient plusieurs articles)

**Relations ManyToOne :**
- Reservation → Table (Plusieurs réservations pour une table)
- ArticleMenu → Menu (Plusieurs produits dans une catégorie)

### 6.3 Migrations

Nous avons utilisé le système de migrations de Doctrine pour gérer l'évolution du schéma :

**Migrations créées :**
- `Version20260106103840` : Création des tables initiales
- `Version20260108093940` : Ajout de la relation User-Reservation
- `Version20260111130504` : Ajout du champ imageUrl
- `Version20260114171734` : Modifications finales

**Commandes utilisées :**
```bash
# Créer une migration
php bin/console make:migration

# Appliquer les migrations
php bin/console doctrine:migrations:migrate
```

---

## 7. Sécurité et Authentification

### 7.1 Système d'Authentification

**Composants Symfony utilisés :**
- `Security Bundle` : Gestion globale de la sécurité
- `PasswordHasher` : Hashage sécurisé des mots de passe
- `UserRepository` : Chargement des utilisateurs

**Configuration (security.yaml) :**
```yaml
security:
    password_hashers:
        App\Entity\User:
            algorithm: auto
    
    providers:
        app_user_provider:
            entity:
                class: App\Entity\User
                property: email
    
    firewalls:
        main:
            lazy: true
            provider: app_user_provider
            form_login:
                login_path: app_login
                check_path: app_login
            logout:
                path: app_logout
```

### 7.2 Contrôle d'Accès

**Protection des routes :**
```php
// Dans le contrôleur
public function dashboard(): Response
{
    if (!$this->isGranted('ROLE_ADMIN')) {
        throw $this->createAccessDeniedException('Accès refusé');
    }
    // ...
}
```

**Vérification dans les templates :**
```twig
{% if is_granted('ROLE_ADMIN') %}
    <a href="{{ path('app_dashboard') }}">Dashboard</a>
{% endif %}
```

### 7.3 Protection CSRF

Tous les formulaires incluent un token CSRF pour prévenir les attaques :
```twig
<input type="hidden" name="_csrf_token" value="{{ csrf_token('authenticate') }}">
```

### 7.4 Validation des Données

**Validation côté serveur :**
- Vérification des types de données
- Validation des formats (email, téléphone)
- Contrôle des valeurs (dates futures, nombres positifs)

**Validation côté client :**
- Attributs HTML5 (required, type="email", min, max)
- JavaScript pour validation en temps réel

### 7.5 Sécurité des Requêtes AJAX

**Protection :**
- Vérification de l'authentification
- Validation des permissions
- Retour de réponses JSON sécurisées

**Exemple :**
```php
public function deleteProduct(int $id): Response
{
    // Vérifier l'authentification
    if (!$this->getUser()) {
        return $this->json(['success' => false, 'error' => 'Non authentifié']);
    }
    
    // Vérifier les permissions
    if (!$this->isGranted('ROLE_ADMIN')) {
        return $this->json(['success' => false, 'error' => 'Non autorisé']);
    }
    
    // Traitement...
}
```

---

## 8. Interface Utilisateur

### 8.1 Design et Ergonomie

**Principes de design appliqués :**
- **Simplicité** : Interface épurée et intuitive
- **Cohérence** : Palette de couleurs et typographie uniformes
- **Feedback visuel** : Animations et transitions fluides
- **Accessibilité** : Contrastes suffisants, tailles de texte adaptées

**Palette de couleurs :**
- Primaire : Orange (#ff8c00) - Chaleur et convivialité
- Secondaire : Bleu (#3b82f6) - Confiance et professionnalisme
- Succès : Vert (#10b981)
- Erreur : Rouge (#ef4444)
- Neutre : Gris (#6b7280)

### 8.2 Responsive Design

**Approche Mobile-First :**
- Design optimisé pour mobile d'abord
- Adaptation progressive pour tablettes et desktop
- Media queries CSS pour les différents breakpoints

**Breakpoints utilisés :**
```css
/* Mobile : < 768px (par défaut) */
/* Tablette : 768px - 1024px */
@media (min-width: 768px) { ... }

/* Desktop : > 1024px */
@media (min-width: 1024px) { ... }
```

**Techniques utilisées :**
- Flexbox pour les layouts flexibles
- CSS Grid pour les grilles de produits
- Images responsives avec max-width: 100%
- Navigation adaptative (menu hamburger sur mobile)

### 8.3 Expérience Utilisateur (UX)

**Parcours utilisateur optimisé :**

1. **Arrivée sur le site :**
   - Carrousel attractif
   - Informations clés visibles
   - Call-to-action évident (Réserver)

2. **Réservation :**
   - Formulaire simple et clair
   - Validation en temps réel
   - Confirmation immédiate

3. **Boutique :**
   - Navigation par catégories
   - Ajout au panier fluide
   - Panier toujours accessible

4. **Espace personnel :**
   - Accès rapide via modal
   - Gestion simplifiée des réservations
   - Historique clair

**Éléments d'interaction :**
- Boutons avec états hover/active
- Modals pour les actions importantes
- Animations de transition (0.3s ease)
- Feedback visuel sur les actions (loading, succès, erreur)

### 8.4 Composants Réutilisables

**Modals :**
- Structure HTML commune
- Animations d'ouverture/fermeture
- Overlay cliquable pour fermer
- Bouton de fermeture (×)

**Cartes (Cards) :**
- Design uniforme avec box-shadow
- Hover effects
- Structure flexible (image, titre, description, actions)

**Boutons :**
- Styles cohérents (primary, secondary, danger)
- États interactifs
- Icônes Font Awesome

---

## 9. Difficultés Rencontrées

### 9.1 Difficultés Techniques

#### Gestion des Relations Doctrine
**Problème :**  
Confusion entre les entités User et Personnel pour les réservations.

**Solution :**  
Ajout d'une vérification du type d'utilisateur avant de lier une réservation :
```php
if ($currentUser instanceof User) {
    $reservation->setUser($currentUser);
}
```

#### Conflits JavaScript
**Problème :**  
Fonctions JavaScript définies plusieurs fois, causant des comportements imprévisibles.

**Solution :**  
- Nettoyage du code
- Déclaration unique des variables globales
- Suppression des doublons

#### Routes de Suppression
**Problème :**  
Routes avec annotations non chargées automatiquement.

**Solution :**  
Définition explicite des routes dans `config/routes.yaml` :
```yaml
dashboard_delete_product:
    path: /dashboard/delete-product/{id}
    controller: App\Controller\HomeController::deleteProduct
    methods: [POST]
```

### 9.2 Difficultés Organisationnelles

#### Coordination d'Équipe
**Défi :**  
Travail simultané sur les mêmes fichiers.

**Solution :**
- Utilisation de Git avec branches séparées
- Réunions régulières pour synchronisation
- Répartition claire des tâches

#### Gestion du Temps
**Défi :**  
Estimation incorrecte de la durée de certaines tâches.

**Solution :**
- Priorisation des fonctionnalités essentielles
- Développement itératif
- Tests réguliers

### 9.3 Apprentissages

**Compétences acquises :**
- Maîtrise de Symfony et Doctrine
- Gestion de projet en équipe
- Résolution de problèmes complexes
- Débogage méthodique
- Architecture logicielle

**Bonnes pratiques appliquées :**
- Code commenté et structuré
- Commits Git descriptifs
- Tests réguliers
- Documentation continue

---

## 10. Perspectives d'Évolution

### 10.1 Améliorations Fonctionnelles

**Court terme (1-3 mois) :**
- ✨ Système de paiement en ligne (Stripe, PayPal)
- 📧 Notifications par email (confirmation de réservation, commande)
- 📊 Statistiques avancées pour les administrateurs
- 🔍 Recherche avancée de produits
- ⭐ Système d'avis et notes

**Moyen terme (3-6 mois) :**
- 📱 Application mobile (React Native / Flutter)
- 🎁 Programme de fidélité
- 📅 Calendrier de disponibilité en temps réel
- 🖨️ Génération de factures PDF
- 🌐 Multi-langue (Français, Arabe, Anglais)

**Long terme (6-12 mois) :**
- 🤖 Chatbot pour assistance client
- 📈 Analytics et Business Intelligence
- 🔗 Intégration avec systèmes de caisse
- 🚚 Système de livraison avec tracking
- 🎯 Marketing automation

### 10.2 Améliorations Techniques

**Performance :**
- Mise en cache Redis pour les données fréquentes
- Optimisation des requêtes SQL (eager loading)
- Compression des assets (CSS, JS)
- CDN pour les images

**Sécurité :**
- Authentification à deux facteurs (2FA)
- Rate limiting sur les API
- Audit de sécurité régulier
- Logs d'activité détaillés

**Architecture :**
- API REST pour découplage frontend/backend
- Microservices pour scalabilité
- Tests automatisés (PHPUnit, Behat)
- CI/CD avec GitHub Actions

### 10.3 Évolutions UX/UI

**Design :**
- Mode sombre (dark mode)
- Personnalisation du thème
- Animations plus fluides
- Accessibilité WCAG 2.1

**Fonctionnalités :**
- Drag & drop pour réorganiser
- Raccourcis clavier
- Tutoriels interactifs
- Onboarding pour nouveaux utilisateurs

---

## 11. Conclusion

### 11.1 Bilan du Projet

Ce projet de système de gestion de restaurant a été une expérience enrichissante qui nous a permis de mettre en pratique nos connaissances en développement web full-stack. Nous avons réussi à créer une application fonctionnelle et professionnelle qui répond aux besoins réels d'un restaurant moderne.

**Objectifs atteints :**
✅ Application web complète et fonctionnelle  
✅ Interface utilisateur moderne et responsive  
✅ Système d'authentification sécurisé  
✅ Dashboard administrateur complet  
✅ Gestion des réservations et commandes  
✅ Architecture MVC propre et maintenable  

### 11.2 Compétences Développées

**Techniques :**
- Maîtrise de Symfony et de son écosystème
- Conception de bases de données relationnelles
- Développement frontend moderne (HTML5, CSS3, JavaScript ES6)
- Gestion de la sécurité web
- Utilisation de Git pour le travail collaboratif

**Transversales :**
- Travail en équipe
- Gestion de projet
- Résolution de problèmes
- Communication technique
- Autonomie et recherche

### 11.3 Remerciements

Nous tenons à remercier :
- **Dr. Zineb HIDILA**, notre encadrante, pour ses conseils et son suivi
- **L'établissement**, pour les ressources mises à disposition
- **Nos camarades**, pour leurs retours et suggestions

### 11.4 Mot de Fin

Ce projet nous a permis de comprendre concrètement les enjeux du développement d'une application web professionnelle. Les difficultés rencontrées nous ont appris à persévérer et à trouver des solutions créatives. Nous sommes fiers du résultat obtenu et confiants dans notre capacité à mener à bien des projets similaires dans le futur.

---

## Annexes

### Annexe A : Commandes Utiles

**Symfony :**
```bash
# Démarrer le serveur
symfony server:start

# Créer une entité
php bin/console make:entity

# Créer une migration
php bin/console make:migration

# Appliquer les migrations
php bin/console doctrine:migrations:migrate

# Vider le cache
php bin/console cache:clear
```

**Git :**
```bash
# Cloner le repository
git clone https://github.com/AyOuB-0007/soutenance-php.git

# Créer une branche
git checkout -b feature/nom-feature

# Commit et push
git add .
git commit -m "Description du commit"
git push origin feature/nom-feature
```

### Annexe B : Configuration Requise

**Serveur :**
- PHP 8.2 ou supérieur
- MySQL 8.0 ou supérieur
- Composer 2.x
- Symfony CLI

**Extensions PHP requises :**
- pdo_mysql
- intl
- json
- mbstring
- xml

### Annexe C : Liens Utiles

**Documentation :**
- Symfony : https://symfony.com/doc/current/index.html
- Doctrine : https://www.doctrine-project.org/
- Twig : https://twig.symfony.com/

**Repository GitHub :**
- https://github.com/AyOuB-0007/soutenance-php

---

**Fin du rapport**

*Réalisé par : Ghali KHARMOUDY, Ayoub OUHDACH, Oussama HARKATY*  
*Date : Janvier 2025*
