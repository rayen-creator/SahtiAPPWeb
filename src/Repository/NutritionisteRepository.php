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



    public function findavailablenutri(){
        $query=$this->getEntityManager()
            ->createQuery('select n.nom , n.prenom , n.email , n.adresse , n.bio , n.certification , n.img
             from APP\Entity\nutritioniste n where n.isblocked=:isblocked')
            ->setParameter('isblocked',false );

        return $query->getResult();
    }

    //************************************

    public function findPlanBySujet($sujet){
        return $this->createQueryBuilder('Nutritioniste')
            ->andWhere('Nutritioniste.nom LIKE :sujet or Nutritioniste.prenom LIKE :sujet  ')
            ->setParameter('sujet', '%'.$sujet.'%')
            ->getQuery()
            ->getResult();
    }


//*********************************************************************

    public function updateimg($id,$param){
        $query=$this->getEntityManager()
            ->createQuery('update APP\Entity\nutritioniste n set n.img=:img where n.id=:id')
            ->setParameter('img',$param )
            ->setParameter('id',$id);
        return $query->getResult();
    }

//***************************************************************

    public function searchemail($param){
        $query=$this->getEntityManager()
            ->createQuery('select n from APP\Entity\nutritioniste n where n.email=:email')
            ->setParameter('email',$param);
        return $query->getResult();
    }

    public function updateresetpwd($mail,$param){
        $query=$this->getEntityManager()
            ->createQuery('update APP\Entity\nutritioniste n set n.passwd=:passwd where n.email=:email')
            ->setParameter('passwd',md5($param) )
            ->setParameter('email',$mail);
        return $query->getResult();
    }

    //*********************************************************************

    public function updatelastname($id,$param){
        $query=$this->getEntityManager()
            ->createQuery('update APP\Entity\nutritioniste n set n.nom=:nom where n.id=:id')
            ->setParameter('nom',$param)
            ->setParameter('id',$id);
        return $query->getResult();
    }
    public function updatefirstname($id,$param){
        $query=$this->getEntityManager()
            ->createQuery('update APP\Entity\nutritioniste n set n.prenom=:prenom where n.id=:id')
            ->setParameter('prenom',$param)
            ->setParameter('id',$id);
        return $query->getResult();
    }
    public function updatepwd($id,$param){
        $query=$this->getEntityManager()
            ->createQuery('update APP\Entity\nutritioniste n set n.passwd=:passwd where n.id=:id')
            ->setParameter('passwd',md5($param) )
            ->setParameter('id',$id);
        return $query->getResult();
    }
    public function updatebio($id,$param){
        $query=$this->getEntityManager()
            ->createQuery('update APP\Entity\nutritioniste n set n.bio=:bio where n.id=:id')
            ->setParameter('bio',$param )
            ->setParameter('id',$id);
        return $query->getResult();
    }

    //*******************************************

    public function blocknutri($id){
        $query=$this->getEntityManager()
            ->createQuery('update APP\Entity\nutritioniste n set n.isblocked=:isblocked where n.id=:id')
            ->setParameter('isblocked',1)
            ->setParameter('id',$id);
        return $query->getResult();
    }
    public function unblocknutri($id){
        $query=$this->getEntityManager()
            ->createQuery('update APP\Entity\nutritioniste n set n.isblocked=:isblocked where n.id=:id')
            ->setParameter('isblocked',0)
            ->setParameter('id',$id);
        return $query->getResult();
    }
}