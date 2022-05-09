<?php

namespace App\Repository;
use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    // /**
    //  * @return Produit[] Returns an array of Produit objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Produit
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function OrderByPrice()
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('select m from App\Entity\Produit m order by m.nom DESC');
        return $query->getResult();
    }
    public function Order()
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('select m from App\Entity\Produit m order by m.prix ASC');
        return $query->getResult();
    }
    public function findTeamwithNumber($num){
        return $this->createQueryBuilder('Produit')
            ->where('Produit.nom LIKE :nom')
            ->setParameter('nom', '%'.$num.'%')
            ->getQuery()
            ->getResult();
    }
    public function repture(){
        $em = $this->getEntityManager();
        $query = $em->createQuery('select m from App\Entity\Produit m where m.quantite < :qte')
                    ->setParameter('qte', 5);
        return $query->getResult();
    }
    public function DescProdSearch($order){
        $em = $this->getEntityManager();

        $query = $em->createQuery('SELECT e FROM App\Entity\Produit e order by e.prix DESC ');
        return $query->getResult();
    }

    public function AscProdSearch($order){
        $em = $this->getEntityManager();

        $query = $em->createQuery('SELECT e FROM App\Entity\Produit e order by e.prix ASC  ');
        return $query->getResult();
    }
    public function Filter($prix)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('select m from App\Entity\Produit m where m.prix <:prix')
            ->setParameter('prix', $prix);
        return $query->getResult();
    }



}
