<?php

namespace App\Repository;

use App\Entity\Regime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Regime|null find($id, $lockMode = null, $lockVersion = null)
 * @method Regime|null findOneBy(array $criteria, array $orderBy = null)
 * @method Regime[]    findAll()
 * @method Regime[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegimeRepository extends  ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Regime::class);
    }


    public function statisti()
    {
        $qb = $this->createQueryBuilder('v')
            ->select('COUNT(v.objectif) AS rec, SUBSTRING(v.objectif, 1, 100000) AS objectif')
            ->groupBy('objectif');
        return $qb->getQuery()
            ->getResult();

    }

}