<?php

namespace App\Repository;

use App\Entity\Aliment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Aliment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Aliment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Aliment[]    findAll()
 * @method Aliment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlimentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Aliment::class);
    }
    public function findBycalories($value)
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.idAliment', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function find_Nb_Rec_Par_Status($type)
    {

        $em = $this->getEntityManager();

        $query = $em->createQuery(
            'SELECT DISTINCT  count(r.idAliment) FROM   App\Entity\Aliment r  where r.type = :type  '
        );
        $query->setParameter('type', $type);
        return $query->getResult();
    }

    public function findByName()
    {
        return $this->createQueryBuilder('aliment')
            ->orderBy(' user.nom','DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findTeamwithNumber($num){
        return $this->createQueryBuilder('Aliment')
            ->where('Aliment.nom LIKE :nom')
            ->setParameter('nom', '%'.$num.'%')
            ->getQuery()
            ->getResult();
    }



    public function DescReclamationSearch($order){
        $em = $this->getEntityManager();

        $query = $em->createQuery('SELECT e FROM App\Entity\Aliment e order by e.idAliment DESC ');
        return $query->getResult();
    }

    public function AscReclamationSearch($order){
        $em = $this->getEntityManager();

        $query = $em->createQuery('SELECT e FROM App\Entity\Aliment e order by e.idAliment ASC  ');
        return $query->getResult();
    }


}