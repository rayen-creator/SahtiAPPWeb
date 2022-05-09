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



    public function findavailablecoach(){
        $query=$this->getEntityManager()
            ->createQuery('select e.nom , e.prenom , e.email , e.adresse , e.bio , e.certification , e.img
             from APP\Entity\entraineur e where e.isblocked=:isblocked')
            ->setParameter('isblocked',false );

        return $query->getResult();
    }
    //**************

    public function findPlanBySujet($sujet){
        return $this->createQueryBuilder('Entraineur')
            ->andWhere('Entraineur.nom LIKE :sujet or Entraineur.prenom LIKE :sujet  ')
            ->setParameter('sujet', '%'.$sujet.'%')
            ->getQuery()
            ->getResult();
    }
    //***********************************
    public function updateimg($id,$param){
        $query=$this->getEntityManager()
            ->createQuery('update APP\Entity\entraineur e set e.img=:img where e.id=:id')
            ->setParameter('img',$param )
            ->setParameter('id',$id);
        return $query->getResult();
    }
    //***************************************************

    public function searchemail($param){
        $query=$this->getEntityManager()
            ->createQuery('select e from APP\Entity\entraineur e where e.email=:email')
            ->setParameter('email',$param);
        return $query->getResult();
    }

    public function updateresetpwd($mail,$param){
        $query=$this->getEntityManager()
            ->createQuery('update APP\Entity\entraineur e set e.passwd=:passwd where e.email=:email')
            ->setParameter('passwd',md5($param) )
            ->setParameter('email',$mail);
        return $query->getResult();
    }

//*********************************************************
    public function updatelastname($id,$param){
        $query=$this->getEntityManager()
            ->createQuery('update APP\Entity\entraineur e set e.nom=:nom where e.id=:id')
            ->setParameter('nom',$param)
            ->setParameter('id',$id);
        return $query->getResult();
    }
    public function updatefirstname($id,$param){
        $query=$this->getEntityManager()
            ->createQuery('update APP\Entity\entraineur e set e.prenom=:prenom where e.id=:id')
            ->setParameter('prenom',$param)
            ->setParameter('id',$id);
        return $query->getResult();
    }
    public function updatepwd($id,$param){
        $query=$this->getEntityManager()
            ->createQuery('update APP\Entity\entraineur e set e.passwd=:passwd where e.id=:id')
            ->setParameter('passwd',md5($param) )
            ->setParameter('id',$id);
        return $query->getResult();
    }
    public function updatebio($id,$param){
        $query=$this->getEntityManager()
            ->createQuery('update APP\Entity\entraineur e set e.bio=:bio where e.id=:id')
            ->setParameter('bio',$param )
            ->setParameter('id',$id);
        return $query->getResult();
    }


    //***************************************************

    public function blockcoach($id){
        $query=$this->getEntityManager()
            ->createQuery('update APP\Entity\entraineur e set e.isblocked=:isblocked where e.id=:id')
            ->setParameter('isblocked',1)
            ->setParameter('id',$id);
        return $query->getResult();
    }
    public function unblockcoach($id){
        $query=$this->getEntityManager()
            ->createQuery('update APP\Entity\entraineur e set e.isblocked=:isblocked where e.id=:id')
            ->setParameter('isblocked',0)
            ->setParameter('id',$id);
        return $query->getResult();
    }


}