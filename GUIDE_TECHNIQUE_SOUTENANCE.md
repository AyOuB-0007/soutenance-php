# Guide Technique pour la Soutenance

## 📋 Vue d'ensemble du projet

**Nom** : Système de Gestion de Restaurant  
**Framework** : Symfony 6.4 (PHP)  
**Base de données** : MySQL avec Doctrine ORM  
**Frontend** : Twig, CSS3, JavaScript ES6  

---

## 🔐 1. AUTHENTIFICATION ET SÉCURITÉ

### Comment fonctionne l'authentification ?

**Composants utilisés :**
- `Symfony Security Bundle` pour la gestion de la sécurité
- `UserRepository` pour charger les utilisateurs depuis la base de données
- Hashage des mots de passe avec l'algorithme `auto` de Symfony

**Processus de connexion :**
1. L'utilisateur clique sur "Connexion" → Ouvre une modal (popup)
2. Il entre son email et mot de passe
3. Le formulaire est envoyé à `/login` (route `app_login`)
4. `SecurityController::login()` vérifie les identifiants
5. Symfony compare le mot de passe hashé avec celui en base de données
6. Si correct → Session créée, utilisateur connecté
7. Si incorrect → Message d'erreur affiché

**Code clé (SecurityController.php) :**
```php
#[Route('/login', name: 'app_login')]
public function login(AuthenticationUtils $authenticationUtils): Response
{
    $error = $authenticationUtils->getLastAuthenticationError();
    $lastUsername = $authenticationUtils->getLastUsername();
    
    return $this->render('security/login.html.twig', [
        'last_username' => $lastUsername,
        'error' => $error,
    ]);
}
```

### Système de rôles

**Deux types d'utilisateurs :**
- `ROLE_USER` : Utilisateurs normaux (clients)
- `ROLE_ADMIN` : Administrateurs (accès au dashboard)

**Protection des routes :**
```php
// Dans le contrôleur
if (!$this->isGranted('ROLE_ADMIN')) {
    throw $this->createAccessDeniedException('Accès refusé');
}
```

---

## 🎯 2. GESTION DES RÉSERVATIONS

### Comment créer une réservation ?

**Processus :**
1. Utilisateur remplit le formulaire (date, heure, nombre de personnes, téléphone)
2. Formulaire envoyé en POST à `/` (route `app_home`)
3. `HomeController::index()` récupère les données
4. Création d'une nouvelle entité `Reservation`
5. Si l'utilisateur est connecté → Liaison avec son compte (`setUser()`)
6. Sauvegarde en base de données avec `EntityManager`

**Code clé :**
```php
$reservation = new Reservation();
$reservation->setNomClient($clientName);
$reservation->setTelephone($phone);
$reservation->setNombrePersonnes($persons);
$reservation->setDateHeure(new \DateTime($date . ' ' . $time));

// Lier à l'utilisateur connecté
if ($currentUser instanceof User) {
    $reservation->setUser($currentUser);
}

$entityManager->persist($reservation);
$entityManager->flush();
```

### Gestion des réservations utilisateur

**Fonctionnalités :**
- Voir ses réservations : Route `/my-reservations`
- Modifier une réservation : Route `/update-my-reservation`
- Supprimer une réservation : Route `/delete-my-reservation`

**Sécurité :** On vérifie que l'utilisateur connecté est bien le propriétaire de la réservation avant toute modification.

---

## 🛒 3. SYSTÈME DE BOUTIQUE ET PANIER

### Architecture

**Entités principales :**
- `Menu` : Catégories de produits (Entrées, Plats, Desserts...)
- `ArticleMenu` : Produits individuels (nom, description, prix, image)
- `Commande` : Commandes passées
- `ArticleCommande` : Produits dans une commande (relation many-to-many)

### Comment fonctionne le panier ?

**Stockage :** Session PHP (pas de base de données pour le panier temporaire)

**Processus :**
1. Utilisateur clique sur "Ajouter au panier"
2. JavaScript envoie une requête AJAX
3. Le produit est ajouté à `$_SESSION['cart']`
4. Le compteur du panier est mis à jour dynamiquement
5. Page panier affiche les produits depuis la session

---

## 🎨 4. DASHBOARD ADMINISTRATEUR

### Comment fonctionne le dashboard ?

**Navigation par sections :**
- 4 cartes cliquables : Produits, Catégories, Employés, Réservations
- JavaScript change la section active avec `showSection()`
- Une seule page, contenu dynamique

**Code JavaScript clé :**
```javascript
function showSection(sectionName) {
    // Retirer la classe 'active' de toutes les sections
    document.querySelectorAll('.section-content').forEach(s => {
        s.classList.remove('active');
    });
    
    // Ajouter 'active' à la section cliquée
    document.getElementById(sectionName + '-section').classList.add('active');
}
```

### Suppression d'éléments

**Processus :**
1. Clic sur bouton "Supprimer" → Appelle `deleteProduct(id, name)`
2. Ouvre une modal de confirmation
3. Clic sur "Supprimer" dans la modal → Appelle `confirmDelete()`
4. Requête AJAX POST vers `/dashboard/delete-product/{id}`
5. Contrôleur supprime l'élément de la base de données
6. Réponse JSON : `{success: true}` ou `{success: false, error: "..."}`
7. Page rechargée pour afficher les changements

**Code JavaScript :**
```javascript
function confirmDelete() {
    let url = `/dashboard/delete-product/${deleteItemId}`;
    
    fetch(url, { method: 'POST' })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        });
}
```

**Code PHP (Contrôleur) :**
```php
public function deleteProduct(int $id, ArticleMenuRepository $repo, EntityManagerInterface $em): Response
{
    $product = $repo->find($id);
    
    if (!$product) {
        return $this->json(['success' => false, 'error' => 'Produit introuvable']);
    }
    
    $em->remove($product);
    $em->flush();
    
    return $this->json(['success' => true]);
}
```

---

## 🗄️ 5. BASE DE DONNÉES (Doctrine ORM)

### Qu'est-ce que Doctrine ORM ?

**ORM** = Object-Relational Mapping  
Permet de manipuler la base de données avec des objets PHP au lieu de SQL.

**Exemple :**
```php
// Au lieu de : SELECT * FROM article_menu WHERE id = 5
$product = $articleMenuRepository->find(5);

// Au lieu de : INSERT INTO article_menu ...
$product = new ArticleMenu();
$product->setNomArticle('Pizza');
$entityManager->persist($product);
$entityManager->flush();
```

### Migrations

**Qu'est-ce qu'une migration ?**  
Un fichier PHP qui décrit les changements à apporter à la base de données.

**Commandes utilisées :**
```bash
# Créer une migration
php bin/console make:migration

# Appliquer les migrations
php bin/console doctrine:migrations:migrate
```

### Relations entre entités

**Exemples dans le projet :**
- `User` ↔ `Reservation` (OneToMany) : Un utilisateur peut avoir plusieurs réservations
- `Menu` ↔ `ArticleMenu` (OneToMany) : Une catégorie contient plusieurs produits
- `Commande` ↔ `ArticleCommande` (OneToMany) : Une commande contient plusieurs articles

---

## 🎭 6. FRONTEND (Twig + JavaScript)

### Twig (Moteur de templates)

**Syntaxe de base :**
```twig
{# Afficher une variable #}
{{ product.nomArticle }}

{# Boucle #}
{% for product in products %}
    <div>{{ product.nomArticle }}</div>
{% endfor %}

{# Condition #}
{% if app.user %}
    Bonjour {{ app.user.email }}
{% endif %}
```

### JavaScript moderne (ES6)

**Fonctionnalités utilisées :**
- `fetch()` pour les requêtes AJAX
- `addEventListener()` pour les événements
- Template literals : `` `Bonjour ${name}` ``
- Arrow functions : `() => {}`

---

## 📊 7. ARCHITECTURE MVC

**MVC** = Model-View-Controller

### Dans notre projet :

**Model (Modèle)** : Entités dans `src/Entity/`
- `User.php`, `Reservation.php`, `ArticleMenu.php`...
- Représentent les tables de la base de données

**View (Vue)** : Templates Twig dans `templates/`
- `base.html.twig`, `home/index.html.twig`, `admin/dashboard.html.twig`...
- Affichent les données à l'utilisateur

**Controller (Contrôleur)** : Classes dans `src/Controller/`
- `HomeController.php`, `SecurityController.php`...
- Gèrent la logique métier et font le lien entre Model et View

**Flux de données :**
1. Utilisateur fait une requête → Route
2. Route appelle un Contrôleur
3. Contrôleur récupère des données (Model)
4. Contrôleur passe les données à une Vue (Twig)
5. Vue génère le HTML
6. HTML envoyé au navigateur

---

## 🚀 8. POINTS TECHNIQUES IMPORTANTS

### Sécurité

✅ **Hashage des mots de passe** : Jamais en clair dans la base  
✅ **Protection CSRF** : Tokens sur les formulaires  
✅ **Validation des données** : Côté serveur ET client  
✅ **Contrôle d'accès** : Vérification des rôles (ROLE_ADMIN)  

### Performance

✅ **AJAX** : Pas de rechargement complet de la page  
✅ **Session** : Panier stocké en session (rapide)  
✅ **Cache Symfony** : Améliore les performances  

### Responsive Design

✅ **CSS Flexbox/Grid** : Adaptation mobile/tablette/desktop  
✅ **Media queries** : Styles différents selon la taille d'écran  

---

## 💡 RÉPONSES AUX QUESTIONS FRÉQUENTES

### "Comment avez-vous géré l'authentification ?"
> Nous avons utilisé Symfony Security Bundle qui gère automatiquement le hashage des mots de passe, la création de sessions et la vérification des identifiants. Nous avons créé une entité User avec un système de rôles (ROLE_USER et ROLE_ADMIN) pour différencier les clients des administrateurs.

### "Comment fonctionne la suppression d'un produit ?"
> Quand l'utilisateur clique sur "Supprimer", une modal de confirmation s'ouvre. Si confirmé, une requête AJAX est envoyée au contrôleur qui supprime l'entité de la base de données via Doctrine ORM, puis retourne une réponse JSON. La page est ensuite rechargée pour afficher les changements.

### "Pourquoi utiliser Symfony ?"
> Symfony est un framework PHP robuste et professionnel qui offre de nombreux composants réutilisables (sécurité, routing, ORM). Il suit les bonnes pratiques (MVC, SOLID) et facilite la maintenance du code. C'est un standard dans l'industrie.

### "Comment gérez-vous les erreurs ?"
> Nous utilisons des blocs try-catch pour capturer les exceptions, nous validons les données côté serveur, et nous retournons des messages d'erreur clairs à l'utilisateur. Les erreurs sont aussi loguées dans la console pour le débogage.

### "Quelles améliorations futures ?"
> - Système de paiement en ligne (Stripe/PayPal)
> - Notifications par email pour les réservations
      - API REST pour une application mobile
> - Système de fidélité client
> - Analytics et statistiques avancées

---

## 📝 CONSEILS POUR LA SOUTENANCE

1. **Sois confiant** : Tu connais ton projet, tu l'as développé
2. **Parle simplement** : Pas besoin de jargon compliqué
3. **Montre le code** : Si on te demande, montre un exemple concret
4. **Admets si tu ne sais pas** : "C'est une bonne question, je devrais approfondir ce point"
5. **Mets en avant les difficultés surmontées** : Montre que tu as résolu des problèmes

**Bonne chance ! 🍀**
