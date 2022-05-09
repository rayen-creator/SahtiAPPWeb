<?php

namespace App\Repository;

use App\Entity\Repas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method Repas|null find($id, $lockMode = null, $lockVersion = null)
 * @method Repas|null findOneBy(array $criteria, array $orderBy = null)
 * @method Repas[]    findAll()
 * @method Repas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

class RepasRepository extends ServiceEntityRepository

{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Repas::class);
    }
    public function findByNamer()
    {
        return $this->createQueryBuilder('repas')
            ->orderBy('repas.nomRep','DESC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findByNameascr()
    {
        return $this->createQueryBuilder('repas')
            ->orderBy(' repas.nomRep','ASC')
            ->getQuery()
            ->getResult()
            ;
    }

}