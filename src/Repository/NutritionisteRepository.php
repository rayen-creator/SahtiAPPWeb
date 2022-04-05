<?php

namespace App\Repository;

use App\Entity\Nutritioniste;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Nutritioniste|null find($id, $lockMode = null, $lockVersion = null)
 * @method Nutritioniste|null findOneBy(array $criteria, array $orderBy = null)
 * @method Nutritioniste[]    findAll()
 * @method Nutritioniste[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NutritionisteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Nutritioniste::class);
    }

}