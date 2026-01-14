<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 *
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function countReservationsForToday(): int
    {
        $qb = $this->createQueryBuilder('r');
        $qb->select('count(r.id)');
        $qb->where('r.dateHeure >= :start');
        $qb->andWhere('r.dateHeure <= :end');
        $qb->setParameter('start', new \DateTime('today'));
        $qb->setParameter('end', new \DateTime('tomorrow'));

        return (int) $qb->getQuery()->getSingleScalarResult();
    }
}
