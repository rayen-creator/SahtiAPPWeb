<?php

namespace App\Repository;

use App\Entity\Entraineur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Entraineur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Entraineur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entraineur[]    findAll()
 * @method Entraineur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoachRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Entraineur::class);
    }

}