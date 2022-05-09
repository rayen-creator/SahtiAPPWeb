<?php

namespace App\Repository;

use App\Entity\Suivieevolution;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Suivieevolution|null find($id, $lockMode = null, $lockVersion = null)
 * @method Suivieevolution|null findOneBy(array $criteria, array $orderBy = null)
 * @method Suivieevolution[]    findAll()
 * @method Suivieevolution[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SuivieevolutionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Suivieevolution::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Suivieevolution $entity, bool $flush = true): void
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
    public function remove(Suivieevolution $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

     /**
      * @return Suivieevolution[] Returns an array of Suivieevolution objects
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
    public function findByIduser($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.iduser LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findByPoids($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.poids LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findByDateDebutProgramme($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.datedebutprogramme LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findByDateEvolution($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.dateevolution LIKE :val')
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
    public function SortByIduser()
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.iduser', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function SortByPoids()
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.poids', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function SortByDateDebutProgramme()
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.datedebutprogramme', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function SortByDateEvolution()
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.dateevolution', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?Suivieevolution
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
