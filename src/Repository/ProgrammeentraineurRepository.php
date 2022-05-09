<?php

namespace App\Repository;

use App\Entity\Programmeentraineur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Programmeentraineur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Programmeentraineur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Programmeentraineur[]    findAll()
 * @method Programmeentraineur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgrammeentraineurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Programmeentraineur::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Programmeentraineur $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Programmeentraineur $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
      * @return Programmeentraineur[] Returns an array of Programmeentraineur objects
      */
    public function findById($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.id LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findByIdExercice($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.idexercice LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findByNomPack($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.nompack LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findByType($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.type LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->getQuery()
            ->getResult()
            ;
    }

    public function SortById()
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function SortByIdExercice()
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.idexercice', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function SortByNomPack()
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.nompack', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function SortByType()
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.type', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Programmeentraineur
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
