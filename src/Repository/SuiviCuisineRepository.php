<?php

namespace App\Repository;

use App\Entity\SuiviCuisine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SuiviCuisine>
 *
 * @method SuiviCuisine|null find($id, $lockMode = null, $lockVersion = null)
 * @method SuiviCuisine|null findOneBy(array $criteria, array $orderBy = null)
 * @method SuiviCuisine[]    findAll()
 * @method SuiviCuisine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SuiviCuisineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SuiviCuisine::class);
    }
}
