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
    name: 'app:add-menu-items',
    description: 'Add more salads and desserts to the menu',
)]
class AddMenuItemsCommand extends Command
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

        // Récupérer les catégories
        $entreesMenu = $this->entityManager->getRepository(Menu::class)->findOneBy(['nomMenu' => 'Entrées']);
        $dessertsMenu = $this->entityManager->getRepository(Menu::class)->findOneBy(['nomMenu' => 'Desserts']);

        if (!$entreesMenu || !$dessertsMenu) {
            $io->error('Catégories Entrées ou Desserts non trouvées');
            return Command::FAILURE;
        }

        // Nouvelles salades pour les entrées
        $newSalads = [
            ['nom' => 'Salade César', 'description' => 'Salade romaine, croûtons, parmesan, sauce césar maison', 'prix' => 42],
            ['nom' => 'Salade de Chèvre Chaud', 'description' => 'Mesclun, tomates cerises, fromage de chèvre grillé, noix', 'prix' => 48],
            ['nom' => 'Salade Niçoise', 'description' => 'Salade verte, thon, œufs, olives, tomates, anchois', 'prix' => 45],
            ['nom' => 'Salade Orientale', 'description' => 'Salade mixte aux épices orientales, menthe fraîche', 'prix' => 38],
            ['nom' => 'Salade de Quinoa', 'description' => 'Quinoa, légumes croquants, avocat, vinaigrette citron', 'prix' => 44],
        ];

        // Nouveaux desserts
        $newDesserts = [
            ['nom' => 'Brownie au Chocolat', 'description' => 'Brownie fondant au chocolat noir, glace vanille', 'prix' => 36],
            ['nom' => 'Cheesecake New York', 'description' => 'Cheesecake crémeux sur biscuit, coulis de fruits rouges', 'prix' => 42],
            ['nom' => 'Brownie aux Noix', 'description' => 'Brownie aux noix de pécan, sauce caramel', 'prix' => 38],
            ['nom' => 'Cheesecake Citron', 'description' => 'Cheesecake au citron vert, zeste de lime', 'prix' => 40],
            ['nom' => 'Fondant au Chocolat', 'description' => 'Moelleux au chocolat, cœur coulant, glace vanille', 'prix' => 44],
        ];

        // Ajouter les salades
        foreach ($newSalads as $saladData) {
            $salad = new ArticleMenu();
            $salad->setNomArticle($saladData['nom']);
            $salad->setDescription($saladData['description']);
            $salad->setPrix($saladData['prix']);
            $salad->setDisponible(true);
            $salad->setMenu($entreesMenu);
            
            $this->entityManager->persist($salad);
            $io->writeln("Ajout de la salade: " . $saladData['nom']);
        }

        // Ajouter les desserts
        foreach ($newDesserts as $dessertData) {
            $dessert = new ArticleMenu();
            $dessert->setNomArticle($dessertData['nom']);
            $dessert->setDescription($dessertData['description']);
            $dessert->setPrix($dessertData['prix']);
            $dessert->setDisponible(true);
            $dessert->setMenu($dessertsMenu);
            
            $this->entityManager->persist($dessert);
            $io->writeln("Ajout du dessert: " . $dessertData['nom']);
        }

        $this->entityManager->flush();

        $io->success('Nouvelles salades et desserts ajoutés avec succès !');
        $io->note('5 nouvelles salades dans les Entrées et 5 nouveaux desserts ajoutés.');

        return Command::SUCCESS;
    }
}