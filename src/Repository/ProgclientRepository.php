<?php

namespace App\Repository;

use App\Entity\Progclient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Progclient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Progclient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Progclient[]    findAll()
 * @method Progclient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgclientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Progclient::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Progclient $entity, bool $flush = true): void
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
    public function remove(Progclient $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
      * @return Progclient[] Returns an array of Progclient objects
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
    public function findByIdProg($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.idprog LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findByIdUser($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.iduser LIKE :val')
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
    public function SortByIdProg()
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.idprog', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function SortByIdUser()
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.iduser', 'ASC')
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
    public function findOneBySomeField($value): ?Progclient
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
