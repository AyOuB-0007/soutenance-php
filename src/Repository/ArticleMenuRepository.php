<?php

namespace App\Repository;

use App\Entity\ArticleMenu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ArticleMenu>
 *
 * @method ArticleMenu|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticleMenu|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticleMenu[]    findAll()
 * @method ArticleMenu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleMenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArticleMenu::class);
    }

    /**
     * @return ArticleMenu[] Returns an array of ArticleMenu objects randomly
     */
    public function findRandomArticleMenus(int $limit = 5): array
    {
        $ids = $this->createQueryBuilder('a')
            ->select('a.id')
            ->getQuery()
            ->getArrayResult();

        $shuffledIds = array_column($ids, 'id');
        shuffle($shuffledIds);

        $randomIds = array_slice($shuffledIds, 0, $limit);

        if (empty($randomIds)) {
            return [];
        }

        return $this->createQueryBuilder('a')
            ->where($this->createQueryBuilder('a')->expr()->in('a.id', ':randomIds'))
            ->setParameter('randomIds', $randomIds)
            ->getQuery()
            ->getResult();
    }
}
