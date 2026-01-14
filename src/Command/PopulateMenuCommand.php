<?php

namespace App\Command;

use App\Entity\Menu;
use App\Entity\ArticleMenu;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:populate-menu',
    description: 'Populate the menu with categories and articles',
)]
class PopulateMenuCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Vider les tables existantes
        $this->entityManager->createQuery('DELETE FROM App\Entity\ArticleMenu')->execute();
        $this->entityManager->createQuery('DELETE FROM App\Entity\Menu')->execute();

        $menuData = [
            'Entrées' => [
                ['nom' => 'Salade Marocaine', 'description' => 'Salade fraîche aux tomates, concombres, oignons et herbes', 'prix' => 35],
                ['nom' => 'Briouates aux Crevettes', 'description' => 'Feuilletés croustillants farcis aux crevettes et épices', 'prix' => 45],
                ['nom' => 'Zaalouk', 'description' => 'Caviar d\'aubergines aux tomates et épices marocaines', 'prix' => 30],
                ['nom' => 'Pastilla au Poisson', 'description' => 'Feuilleté traditionnel au poisson et aux amandes', 'prix' => 55],
                ['nom' => 'Soupe Harira', 'description' => 'Soupe traditionnelle aux lentilles, tomates et épices', 'prix' => 25],
            ],
            'Plats Principaux' => [
                ['nom' => 'Tajine d\'Agneau aux Pruneaux', 'description' => 'Agneau mijoté aux pruneaux, amandes et épices douces', 'prix' => 120],
                ['nom' => 'Couscous Royal', 'description' => 'Couscous aux légumes avec agneau, poulet et merguez', 'prix' => 110],
                ['nom' => 'Pastilla au Poulet', 'description' => 'Feuilleté sucré-salé au poulet, amandes et cannelle', 'prix' => 95],
                ['nom' => 'Tajine de Poisson', 'description' => 'Poisson frais aux légumes et sauce chermoula', 'prix' => 100],
                ['nom' => 'Méchoui d\'Agneau', 'description' => 'Épaule d\'agneau rôtie aux épices berbères', 'prix' => 130],
                ['nom' => 'Kefta aux Œufs', 'description' => 'Boulettes de viande hachée aux œufs et tomates', 'prix' => 85],
            ],
            'Grillades' => [
                ['nom' => 'Brochettes d\'Agneau', 'description' => 'Brochettes marinées aux épices, servies avec légumes grillés', 'prix' => 115],
                ['nom' => 'Poisson Grillé du Jour', 'description' => 'Poisson frais grillé, sauce vierge aux herbes', 'prix' => 120],
                ['nom' => 'Côtelettes d\'Agneau', 'description' => 'Côtelettes grillées aux herbes de Provence', 'prix' => 140],
                ['nom' => 'Brochettes de Poulet', 'description' => 'Blanc de poulet mariné au citron et thym', 'prix' => 90],
                ['nom' => 'Crevettes Grillées', 'description' => 'Grosses crevettes grillées à l\'ail et persil', 'prix' => 135],
            ],
            'Desserts' => [
                ['nom' => 'Pâtisseries Marocaines', 'description' => 'Assortiment de cornes de gazelle, chebakia et makroudh', 'prix' => 40],
                ['nom' => 'Mousse au Chocolat', 'description' => 'Mousse onctueuse au chocolat noir, chantilly', 'prix' => 35],
                ['nom' => 'Tarte aux Figues', 'description' => 'Tarte sablée aux figues fraîches et miel', 'prix' => 38],
                ['nom' => 'Crème Brûlée à la Fleur d\'Oranger', 'description' => 'Crème brûlée parfumée à la fleur d\'oranger', 'prix' => 42],
                ['nom' => 'Salade de Fruits Frais', 'description' => 'Fruits de saison, coulis de fruits rouges', 'prix' => 30],
            ],
            'Boissons Chaudes' => [
                ['nom' => 'Thé à la Menthe', 'description' => 'Thé vert traditionnel à la menthe fraîche', 'prix' => 15],
                ['nom' => 'Café Espresso', 'description' => 'Café italien corsé', 'prix' => 12],
                ['nom' => 'Café Américain', 'description' => 'Café long, doux et aromatique', 'prix' => 15],
                ['nom' => 'Cappuccino', 'description' => 'Espresso avec mousse de lait onctueuse', 'prix' => 18],
                ['nom' => 'Thé Noir aux Épices', 'description' => 'Thé noir parfumé à la cannelle et cardamome', 'prix' => 16],
            ],
            'Boissons Fraîches' => [
                ['nom' => 'Jus d\'Orange Frais', 'description' => 'Jus d\'orange pressé minute', 'prix' => 20],
                ['nom' => 'Citronnade Maison', 'description' => 'Citrons frais, eau pétillante et menthe', 'prix' => 18],
                ['nom' => 'Smoothie aux Fruits', 'description' => 'Mélange de fruits frais de saison', 'prix' => 25],
                ['nom' => 'Eau Minérale', 'description' => 'Eau minérale plate ou gazeuse (50cl)', 'prix' => 10],
                ['nom' => 'Coca-Cola', 'description' => 'Boisson gazeuse (33cl)', 'prix' => 12],
                ['nom' => 'Jus de Pomme', 'description' => 'Jus de pomme naturel (25cl)', 'prix' => 15],
            ],
        ];

        foreach ($menuData as $categoryName => $articles) {
            $io->writeln("Création de la catégorie: $categoryName");
            
            // Créer la catégorie
            $menu = new Menu();
            $menu->setNomMenu($categoryName);
            $this->entityManager->persist($menu);

            // Créer les articles pour cette catégorie
            foreach ($articles as $articleData) {
                $article = new ArticleMenu();
                $article->setNomArticle($articleData['nom']);
                $article->setDescription($articleData['description']);
                $article->setPrix($articleData['prix']);
                $article->setDisponible(true);
                $article->setMenu($menu);
                
                $this->entityManager->persist($article);
                $io->writeln("  - Ajout de l'article: " . $articleData['nom']);
            }
        }

        $this->entityManager->flush();

        $io->success('Menu populé avec succès ! Toutes les catégories et articles ont été ajoutés.');
        $io->note('Vous pouvez maintenant voir la carte complète sur votre site web.');

        return Command::SUCCESS;
    }
}