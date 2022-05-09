<?php

namespace App\Repository;


use App\Entity\Produit;
use App\Entity\Promotion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Promotion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Promotion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Promotion[]    findAll()
 * @method Promotion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

class PromotionRepository  extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }
    public function selectprod($id)
    {

        return $this->createQueryBuilder('p')
                    ->where('p.idProd =: id')
                    ->setParameter('id', $id)
                    ->getQuery()
                    ->getFirstResult();
    }


}
