<?php

namespace App\Controller;

use App\Repository\ArticleMenuRepository;
use App\Repository\MenuRepository;
use App\Repository\PersonnelRepository;
use App\Repository\ReservationRepository;
use App\Repository\TableRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        ArticleMenuRepository $articleMenuRepository,
        MenuRepository $menuRepository,
        ReservationRepository $reservationRepository,
        TableRepository $tableRepository
    ): Response
    {
        $randomProducts = $articleMenuRepository->findRandomArticleMenus(5);
        $reservationsToday = $reservationRepository->countReservationsForToday();
        $availableTables = $tableRepository->count(['statut' => 'Disponible']);
        $totalTables = $tableRepository->count([]);
        
        // Récupérer les 6 premières catégories
        $categories = $menuRepository->findBy([], ['id' => 'ASC'], 6);

        return $this->render('home/index.html.twig', [
            'randomProducts' => $randomProducts,
            'reservationsToday' => $reservationsToday,
            'availableTables' => $availableTables,
            'totalTables' => $totalTables,
            'categories' => $categories,
        ]);
    }

    #[Route('/dashboard/create-category', name: 'dashboard_create_category', methods: ['POST'])]
    public function createCategoryFromDashboard(Request $request, EntityManagerInterface $entityManager): Response
    {
        try {
            $nom = $request->request->get('nom');
            
            if (empty(trim($nom))) {
                return $this->redirectToRoute('app_dashboard');
            }
            
            // Créer une nouvelle catégorie (Menu)
            $menu = new \App\Entity\Menu();
            $menu->setNomMenu(trim($nom));
            
            // Sauvegarder en base
            $entityManager->persist($menu);
            $entityManager->flush();
            
        } catch (\Exception $e) {
            // En cas d'erreur, continuer quand même
        }
        
        return $this->redirectToRoute('app_dashboard');
    }

    #[Route('/api/categories', name: 'api_create_category', methods: ['POST'])]
    public function createCategory(Request $request, EntityManagerInterface $entityManager): Response
    {
        try {
            $data = json_decode($request->getContent(), true);
            
            if (!isset($data['nom']) || empty(trim($data['nom']))) {
                return $this->json(['success' => false, 'error' => 'Nom requis']);
            }
            
            // Créer une nouvelle catégorie (Menu)
            $menu = new \App\Entity\Menu();
            $menu->setNomMenu($data['nom']); // Utiliser nomMenu au lieu de nom
            
            // Sauvegarder en base
            $entityManager->persist($menu);
            $entityManager->flush();
            
            return $this->json(['success' => true, 'id' => $menu->getId()]);
            
        } catch (\Exception $e) {
            return $this->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    #[Route('/dashboard', name: 'app_dashboard', methods: ['GET', 'POST'])]
    public function dashboard(
        Request $request,
        SessionInterface $session,
        ArticleMenuRepository $articleMenuRepository,
        MenuRepository $menuRepository,
        PersonnelRepository $personnelRepository,
        ReservationRepository $reservationRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        // Vérifier que l'utilisateur est connecté
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Vérifier que l'utilisateur a le rôle ROLE_ADMIN
        if (!$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('error', 'Accès refusé. Vous devez être administrateur pour accéder au dashboard.');
            return $this->redirectToRoute('app_home');
        }

        // Gérer la création/modification de produit
        if ($request->isMethod('POST') && $request->request->has('product_action')) {
            $action = $request->request->get('product_action');
            $productId = $request->request->get('product_id');
            $name = $request->request->get('product_name');
            $description = $request->request->get('product_description');
            $price = $request->request->get('product_price');
            $categoryId = $request->request->get('product_category');
            $imageUrl = $request->request->get('product_image_url');
            
            file_put_contents('product_debug.log', date('Y-m-d H:i:s') . " - Action: $action, ID: $productId, Name: $name, Price: $price, Category: $categoryId\n", FILE_APPEND);
            
            try {
                if ($action === 'create' && !empty(trim($name)) && !empty($price) && !empty($categoryId)) {
                    $product = new \App\Entity\ArticleMenu();
                    $product->setNomArticle(trim($name));
                    $product->setDescription(trim($description));
                    $product->setPrix((float)$price);
                    $product->setDisponible(true);
                    
                    if (!empty(trim($imageUrl))) {
                        $product->setImageUrl(trim($imageUrl));
                    }
                    
                    $category = $menuRepository->find($categoryId);
                    if ($category) {
                        $product->setMenu($category);
                        $entityManager->persist($product);
                        $entityManager->flush();
                        file_put_contents('product_debug.log', date('Y-m-d H:i:s') . " - Product created successfully\n", FILE_APPEND);
                        $this->addFlash('success', 'Produit créé avec succès');
                    } else {
                        file_put_contents('product_debug.log', date('Y-m-d H:i:s') . " - Category not found: $categoryId\n", FILE_APPEND);
                        $this->addFlash('error', 'Catégorie introuvable');
                    }
                } elseif ($action === 'edit' && !empty($productId) && !empty(trim($name)) && !empty($price) && !empty($categoryId)) {
                    $product = $articleMenuRepository->find($productId);
                    if ($product) {
                        file_put_contents('product_debug.log', date('Y-m-d H:i:s') . " - Product found, updating...\n", FILE_APPEND);
                        
                        $product->setNomArticle(trim($name));
                        $product->setDescription(trim($description));
                        $product->setPrix((float)$price);
                        
                        if (!empty(trim($imageUrl))) {
                            $product->setImageUrl(trim($imageUrl));
                        } else {
                            $product->setImageUrl(null);
                        }
                        
                        $category = $menuRepository->find($categoryId);
                        if ($category) {
                            $product->setMenu($category);
                            $entityManager->flush();
                            file_put_contents('product_debug.log', date('Y-m-d H:i:s') . " - Product updated successfully\n", FILE_APPEND);
                            $this->addFlash('success', 'Produit modifié avec succès');
                        } else {
                            file_put_contents('product_debug.log', date('Y-m-d H:i:s') . " - Category not found: $categoryId\n", FILE_APPEND);
                            $this->addFlash('error', 'Catégorie introuvable');
                        }
                    } else {
                        file_put_contents('product_debug.log', date('Y-m-d H:i:s') . " - Product not found: $productId\n", FILE_APPEND);
                        $this->addFlash('error', 'Produit introuvable');
                    }
                } else {
                    file_put_contents('product_debug.log', date('Y-m-d H:i:s') . " - Validation failed - Action: $action, Name: $name, Price: $price, Category: $categoryId, ProductId: $productId\n", FILE_APPEND);
                    $this->addFlash('error', 'Données invalides');
                }
            } catch (\Exception $e) {
                file_put_contents('product_debug.log', date('Y-m-d H:i:s') . " - Error: " . $e->getMessage() . "\n", FILE_APPEND);
                $this->addFlash('error', 'Erreur: ' . $e->getMessage());
            }
            
            return $this->redirectToRoute('app_dashboard');
        }

        // Gérer la suppression de produit
        if ($request->isMethod('POST') && $request->request->has('delete_product')) {
            $productId = $request->request->get('product_id');
            
            file_put_contents('product_debug.log', date('Y-m-d H:i:s') . " - DELETE REQUEST - Product ID: $productId\n", FILE_APPEND);
            
            try {
                if (!empty($productId)) {
                    $product = $articleMenuRepository->find($productId);
                    if ($product) {
                        file_put_contents('product_debug.log', date('Y-m-d H:i:s') . " - Product found, deleting: " . $product->getNomArticle() . "\n", FILE_APPEND);
                        $entityManager->remove($product);
                        $entityManager->flush();
                        file_put_contents('product_debug.log', date('Y-m-d H:i:s') . " - Product deleted successfully\n", FILE_APPEND);
                        $this->addFlash('success', 'Produit supprimé avec succès');
                    } else {
                        file_put_contents('product_debug.log', date('Y-m-d H:i:s') . " - Product not found\n", FILE_APPEND);
                        $this->addFlash('error', 'Produit introuvable');
                    }
                } else {
                    file_put_contents('product_debug.log', date('Y-m-d H:i:s') . " - Product ID is empty\n", FILE_APPEND);
                }
            } catch (\Exception $e) {
                file_put_contents('product_debug.log', date('Y-m-d H:i:s') . " - Error: " . $e->getMessage() . "\n", FILE_APPEND);
                $this->addFlash('error', 'Erreur lors de la suppression: ' . $e->getMessage());
            }
            
            return $this->redirectToRoute('app_dashboard');
        }

        // Gérer la suppression d'employé
        if ($request->isMethod('POST') && $request->request->has('delete_employee')) {
            $employeeId = $request->request->get('employee_id');
            
            try {
                if (!empty($employeeId)) {
                    // Supprimer l'employé
                    $personnel = $personnelRepository->find($employeeId);
                    if ($personnel) {
                        $entityManager->remove($personnel);
                        $entityManager->flush();
                    }
                }
            } catch (\Exception $e) {
                // Continuer même en cas d'erreur
            }
            
            // Rediriger pour éviter la resoumission
            return $this->redirectToRoute('app_dashboard');
        }

        // Gérer la création de réservation
        if ($request->isMethod('POST') && $request->request->has('create_reservation')) {
            $clientName = $request->request->get('client_name');
            $phone = $request->request->get('phone');
            $date = $request->request->get('date');
            $time = $request->request->get('time');
            $persons = $request->request->get('persons');
            $tableId = $request->request->get('table_id');
            $notes = $request->request->get('notes');
            
            try {
                if (!empty(trim($clientName)) && !empty(trim($phone)) && !empty($date) && !empty($time)) {
                    // Créer une nouvelle réservation
                    $reservation = new \App\Entity\Reservation();
                    $reservation->setNomClient(trim($clientName));
                    $reservation->setTelephone(trim($phone));
                    $reservation->setNombrePersonnes((int)$persons ?: 2);
                    
                    // Lier à l'utilisateur connecté si disponible
                    $currentUser = $this->getUser();
                    if ($currentUser instanceof \App\Entity\User) {
                        $reservation->setUser($currentUser);
                    }
                    // Si c'est un Personnel (admin), on ne lie pas à l'entité User
                    
                    // Combiner date et heure
                    $dateTime = new \DateTime($date . ' ' . $time);
                    $reservation->setDateHeure($dateTime);
                    
                    if (!empty(trim($notes))) {
                        $reservation->setNotes(trim($notes));
                    }
                    
                    // Associer une table si spécifiée (pour l'instant on ignore, pas d'entité Table complète)
                    // TODO: Implémenter la gestion des tables plus tard
                    
                    // Sauvegarder en base
                    $entityManager->persist($reservation);
                    $entityManager->flush();
                }
            } catch (\Exception $e) {
                // Continuer même en cas d'erreur
            }
            
            // Rediriger pour éviter la resoumission
            return $this->redirectToRoute('app_dashboard');
        }

        // Gérer la modification de réservation
        if ($request->isMethod('POST') && $request->request->has('update_reservation')) {
            $reservationId = $request->request->get('reservation_id');
            $clientName = $request->request->get('client_name');
            $phone = $request->request->get('phone');
            $date = $request->request->get('date');
            $time = $request->request->get('time');
            $persons = $request->request->get('persons');
            $tableId = $request->request->get('table_id');
            $notes = $request->request->get('notes');
            
            try {
                if (!empty($reservationId) && !empty(trim($clientName)) && !empty(trim($phone)) && !empty($date) && !empty($time)) {
                    // Modifier la réservation existante
                    $reservation = $reservationRepository->find($reservationId);
                    if ($reservation) {
                        $reservation->setNomClient(trim($clientName));
                        $reservation->setTelephone(trim($phone));
                        $reservation->setNombrePersonnes((int)$persons ?: 2);
                        
                        // Combiner date et heure
                        $dateTime = new \DateTime($date . ' ' . $time);
                        $reservation->setDateHeure($dateTime);
                        
                        if (!empty(trim($notes))) {
                            $reservation->setNotes(trim($notes));
                        } else {
                            $reservation->setNotes(null);
                        }
                        
                        // Sauvegarder en base
                        $entityManager->flush();
                    }
                }
            } catch (\Exception $e) {
                // Continuer même en cas d'erreur
            }
            
            // Rediriger pour éviter la resoumission
            return $this->redirectToRoute('app_dashboard');
        }

        // Gérer la suppression de réservation
        if ($request->isMethod('POST') && $request->request->has('delete_reservation')) {
            $reservationId = $request->request->get('reservation_id');
            
            try {
                if (!empty($reservationId)) {
                    // Supprimer la réservation
                    $reservation = $reservationRepository->find($reservationId);
                    if ($reservation) {
                        $entityManager->remove($reservation);
                        $entityManager->flush();
                    }
                }
            } catch (\Exception $e) {
                // Continuer même en cas d'erreur
            }
            
            // Rediriger pour éviter la resoumission
            return $this->redirectToRoute('app_dashboard');
        }

        // Gérer la modification d'employé
        if ($request->isMethod('POST') && $request->request->has('update_employee')) {
            $employeeId = $request->request->get('employee_id');
            $nom = $request->request->get('nom');
            $poste = $request->request->get('poste');
            $email = $request->request->get('email');
            $telephone = $request->request->get('telephone');
            
            try {
                if (!empty($employeeId) && !empty(trim($nom)) && !empty(trim($poste))) {
                    // Modifier l'employé existant
                    $personnel = $personnelRepository->find($employeeId);
                    if ($personnel) {
                        $personnel->setNom(trim($nom));
                        $personnel->setPoste(trim($poste));
                        
                        if (!empty(trim($email))) {
                            $personnel->setEmail(trim($email));
                        } else {
                            $personnel->setEmail(null);
                        }
                        
                        if (!empty(trim($telephone))) {
                            $personnel->setTelephone(trim($telephone));
                        } else {
                            $personnel->setTelephone(null);
                        }
                        
                        // Sauvegarder en base
                        $entityManager->flush();
                    }
                }
            } catch (\Exception $e) {
                // Continuer même en cas d'erreur
            }
            
            // Rediriger pour éviter la resoumission
            return $this->redirectToRoute('app_dashboard');
        }

        // Gérer la création d'employé
        if ($request->isMethod('POST') && $request->request->has('create_employee')) {
            $nom = $request->request->get('nom');
            $poste = $request->request->get('poste');
            $email = $request->request->get('email');
            $telephone = $request->request->get('telephone');
            
            try {
                if (!empty(trim($nom)) && !empty(trim($poste))) {
                    // Créer un nouvel employé
                    $personnel = new \App\Entity\Personnel();
                    $personnel->setNom(trim($nom));
                    $personnel->setPoste(trim($poste));
                    
                    if (!empty(trim($email))) {
                        $personnel->setEmail(trim($email));
                    }
                    
                    if (!empty(trim($telephone))) {
                        $personnel->setTelephone(trim($telephone));
                    }
                    
                    // Générer un login unique basé sur le nom
                    $login = strtolower(str_replace(' ', '.', trim($nom)));
                    $counter = 1;
                    $originalLogin = $login;
                    
                    // Vérifier si le login existe déjà
                    while ($personnelRepository->findOneBy(['login' => $login])) {
                        $login = $originalLogin . $counter;
                        $counter++;
                    }
                    
                    $personnel->setLogin($login);
                    
                    // Mot de passe par défaut (à changer lors de la première connexion)
                    $personnel->setPassword(password_hash('123456', PASSWORD_DEFAULT));
                    
                    // Rôle par défaut
                    $personnel->setRoles(['ROLE_USER']);
                    
                    // Sauvegarder en base
                    $entityManager->persist($personnel);
                    $entityManager->flush();
                }
            } catch (\Exception $e) {
                // Continuer même en cas d'erreur
            }
            
            // Rediriger pour éviter la resoumission
            return $this->redirectToRoute('app_dashboard');
        }

        // Gérer la modification de catégorie
        if ($request->isMethod('POST') && $request->request->has('update_category')) {
            $categoryId = $request->request->get('category_id');
            $categoryType = $request->request->get('category_type', 'product');
            $newName = $request->request->get('nom');
            
            try {
                if ($categoryType === 'product' && !empty($categoryId) && !empty(trim($newName))) {
                    // Modifier une catégorie de produit (Menu)
                    $menu = $menuRepository->find($categoryId);
                    if ($menu) {
                        $menu->setNomMenu(trim($newName));
                        $entityManager->flush();
                    }
                } elseif ($categoryType === 'employee' && !empty($categoryId) && !empty(trim($newName))) {
                    // Modifier une catégorie d'employé dans la session
                    $employeeCategories = $session->get('employee_categories', []);
                    foreach ($employeeCategories as &$category) {
                        if ($category['id'] == $categoryId) {
                            $category['nom'] = trim($newName);
                            break;
                        }
                    }
                    $session->set('employee_categories', $employeeCategories);
                }
            } catch (\Exception $e) {
                // Continuer même en cas d'erreur
            }
            
            // Rediriger pour éviter la resoumission
            return $this->redirectToRoute('app_dashboard');
        }

        // Gérer la suppression de catégorie
        if ($request->isMethod('POST') && $request->request->has('delete_category')) {
            $categoryId = $request->request->get('category_id');
            $categoryType = $request->request->get('category_type', 'product');
            
            try {
                if ($categoryType === 'product' && !empty($categoryId)) {
                    // Supprimer une catégorie de produit (Menu)
                    $menu = $menuRepository->find($categoryId);
                    if ($menu) {
                        $entityManager->remove($menu);
                        $entityManager->flush();
                    }
                } elseif ($categoryType === 'employee' && !empty($categoryId)) {
                    // Supprimer une catégorie d'employé de la session
                    $employeeCategories = $session->get('employee_categories', []);
                    $employeeCategories = array_filter($employeeCategories, function($cat) use ($categoryId) {
                        return $cat['id'] != $categoryId;
                    });
                    $session->set('employee_categories', array_values($employeeCategories));
                }
            } catch (\Exception $e) {
                // Continuer même en cas d'erreur
            }
            
            // Rediriger pour éviter la resoumission
            return $this->redirectToRoute('app_dashboard');
        }

        // Gérer la création de catégorie si c'est une requête POST
        if ($request->isMethod('POST') && $request->request->has('create_category')) {
            $nom = $request->request->get('nom');
            $type = $request->request->get('type', 'product'); // product ou employee
            
            if (!empty(trim($nom))) {
                try {
                    if ($type === 'product') {
                        // Créer une catégorie de produit (Menu)
                        $menu = new \App\Entity\Menu();
                        $menu->setNomMenu(trim($nom));
                        $entityManager->persist($menu);
                        $entityManager->flush();
                    } elseif ($type === 'employee') {
                        // Stocker les catégories d'employés en session
                        $employeeCategories = $session->get('employee_categories', []);
                        $newId = count($employeeCategories) + 7; // Commencer après les 6 catégories par défaut
                        $employeeCategories[] = [
                            'id' => $newId,
                            'nom' => trim($nom),
                            'icon' => 'fas fa-users',
                            'description' => 'Catégorie personnalisée',
                            'count' => 0
                        ];
                        $session->set('employee_categories', $employeeCategories);
                    }
                } catch (\Exception $e) {
                    // Continuer même en cas d'erreur
                }
            }
            // Rediriger pour éviter la resoumission
            return $this->redirectToRoute('app_dashboard');
        }

        try {
            // Récupérer les données réelles
            $products = $articleMenuRepository->findAll();
            $categories = $menuRepository->findAll();
            $employees = $personnelRepository->findAll();
            $reservations = $reservationRepository->findAll();
            
            // Récupérer les catégories d'employés personnalisées
            $customEmployeeCategories = $session->get('employee_categories', []);
            
            return $this->render('admin/dashboard.html.twig', [
                'products' => $products,
                'categories' => $categories,
                'employees' => $employees,
                'reservations' => $reservations,
                'customEmployeeCategories' => $customEmployeeCategories,
            ]);
        } catch (\Exception $e) {
            // En cas d'erreur, afficher un dashboard avec des valeurs par défaut
            return $this->render('admin/dashboard.html.twig', [
                'products' => [],
                'categories' => [],
                'employees' => [],
                'reservations' => [],
                'customEmployeeCategories' => [],
            ]);
        }
    }

    #[Route('/notre-carte', name: 'app_restaurant_menu')]
    public function displayMenu(
        ArticleMenuRepository $articleMenuRepository,
        MenuRepository $menuRepository
    ): Response
    {
        try {
            $products = $articleMenuRepository->findAll();
            $categories = $menuRepository->findAll();
            
            return $this->render('home/restaurant_menu.html.twig', [
                'products' => $products,
                'categories' => $categories,
            ]);
        } catch (\Exception $e) {
            return $this->render('home/restaurant_menu.html.twig', [
                'products' => [],
                'categories' => [],
            ]);
        }
    }

    #[Route('/a-propos', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('home/about.html.twig');
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('home/contact.html.twig');
    }

    #[Route('/reservation-publique', name: 'app_public_reservation', methods: ['POST'])]
    public function publicReservation(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Vérifier que l'utilisateur est connecté
        if (!$this->getUser()) {
            // Rediriger vers la page de connexion avec un message
            $this->addFlash('warning', 'Vous devez créer un compte ou vous connecter pour réserver une table.');
            return $this->redirectToRoute('app_login');
        }

        $clientName = $request->request->get('client_name');
        $phone = $request->request->get('phone');
        $date = $request->request->get('date');
        $time = $request->request->get('time');
        $persons = $request->request->get('persons');
        $notes = $request->request->get('notes');
        
        try {
            if (!empty(trim($clientName)) && !empty(trim($phone)) && !empty($date) && !empty($time)) {
                // Créer une nouvelle réservation
                $reservation = new \App\Entity\Reservation();
                $reservation->setNomClient(trim($clientName));
                $reservation->setTelephone(trim($phone));
                $reservation->setNombrePersonnes((int)$persons ?: 2);
                
                // Lier à l'utilisateur connecté si disponible
                $currentUser = $this->getUser();
                if ($currentUser instanceof \App\Entity\User) {
                    $reservation->setUser($currentUser);
                }
                // Si c'est un Personnel (admin), on ne lie pas à l'entité User
                
                // Combiner date et heure
                $dateTime = new \DateTime($date . ' ' . $time);
                $reservation->setDateHeure($dateTime);
                
                if (!empty(trim($notes))) {
                    $reservation->setNotes(trim($notes));
                }
                
                // Sauvegarder en base
                $entityManager->persist($reservation);
                $entityManager->flush();
                
                $this->addFlash('success', 'Votre réservation a été enregistrée avec succès !');
            }
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue lors de la réservation.');
        }
        
        // Rediriger vers la page d'accueil
        return $this->redirectToRoute('app_home');
    }

    public function deleteProduct(int $id, ArticleMenuRepository $articleMenuRepository, EntityManagerInterface $entityManager): Response
    {
        try {
            $product = $articleMenuRepository->find($id);
            
            if (!$product) {
                return $this->json(['success' => false, 'error' => 'Produit introuvable']);
            }
            
            $entityManager->remove($product);
            $entityManager->flush();
            
            return $this->json(['success' => true, 'message' => 'Produit supprimé avec succès']);
            
        } catch (\Exception $e) {
            return $this->json(['success' => false, 'error' => 'Erreur lors de la suppression: ' . $e->getMessage()]);
        }
    }

    public function deleteCategory(int $id, MenuRepository $menuRepository, EntityManagerInterface $entityManager): Response
    {
        try {
            $category = $menuRepository->find($id);
            
            if (!$category) {
                return $this->json(['success' => false, 'error' => 'Catégorie introuvable']);
            }
            
            // Vérifier s'il y a des produits dans cette catégorie
            if ($category->getArticleMenus()->count() > 0) {
                return $this->json(['success' => false, 'error' => 'Impossible de supprimer une catégorie qui contient des produits']);
            }
            
            $entityManager->remove($category);
            $entityManager->flush();
            
            return $this->json(['success' => true, 'message' => 'Catégorie supprimée avec succès']);
            
        } catch (\Exception $e) {
            return $this->json(['success' => false, 'error' => 'Erreur lors de la suppression: ' . $e->getMessage()]);
        }
    }

    public function deleteEmployee(int $id, PersonnelRepository $personnelRepository, EntityManagerInterface $entityManager): Response
    {
        try {
            $employee = $personnelRepository->find($id);
            
            if (!$employee) {
                return $this->json(['success' => false, 'error' => 'Employé introuvable']);
            }
            
            $entityManager->remove($employee);
            $entityManager->flush();
            
            return $this->json(['success' => true, 'message' => 'Employé supprimé avec succès']);
            
        } catch (\Exception $e) {
            return $this->json(['success' => false, 'error' => 'Erreur lors de la suppression: ' . $e->getMessage()]);
        }
    }
}
