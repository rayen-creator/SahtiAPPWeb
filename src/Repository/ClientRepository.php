<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }


    public function clientisblocked($mail,$pwd){

        $query=$this->getEntityManager()
            ->createQuery('select c.isblocked from APP\Entity\Client c where c.email=:email and c.passwd=:passwd')
            ->setParameter('email',$mail)
            ->setParameter('passwd',$pwd);

        return $query->execute();
    }
//********************************
    /**
     * @return Client[]
     */
    public function findByPrenom($sujet){
        return $this->createQueryBuilder('Client')
            ->andWhere('Client.adresse LIKE :sujet or Client.prenom LIKE :sujet  ')
            ->setParameter('sujet', '%'.$sujet.'%')
            ->getQuery()
            ->getResult();
    }


    //***************ABDO's TASK**************************

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Client $entity, bool $flush = true): void
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
    public function remove(Client $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return Client[] Returns an array of Client objects
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
    public function findByNom($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.nom LIKE :val')
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
    public function SortByNom()
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.nom', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    //***************ABDO's TASK**************************





    //*************************

    public function addcoach($id,$email){
        $query=$this->getEntityManager()
            ->createQuery('update APP\Entity\client c set c.idCoach=:idCoach where c.email=:email')
            ->setParameter('idCoach',$id)
            ->setParameter('email',$email);
        return $query->getResult();
    }

    public function addnutri($id,$email){
        $query=$this->getEntityManager()
            ->createQuery('update APP\Entity\client c set c.idNutri=:idNutri where c.email=:email')
            ->setParameter('idNutri',$id)
            ->setParameter('email',$email);
        return $query->getResult();
    }

    //*************************************************************

    public function searchimg($param){
        $query=$this->getEntityManager()
            ->createQuery('select c.img from APP\Entity\client c where c.id=:id')
            ->setParameter('id',$param);
        return $query->getResult();
    }

    public function updateimg($id,$param){
        $query=$this->getEntityManager()
            ->createQuery('update APP\Entity\client c set c.img=:img where c.id=:id')
            ->setParameter('img',$param )
            ->setParameter('id',$id);
        return $query->getResult();
    }

    //***********************************************************************

    public function searchemail($param){
        $query=$this->getEntityManager()
            ->createQuery('select c from APP\Entity\client c where c.email=:email')
            ->setParameter('email',$param);
        return $query->getResult();
    }

    public function updateresetpwd($mail,$param){
        $query=$this->getEntityManager()
            ->createQuery('update APP\Entity\client c set c.passwd=:passwd where c.email=:email')
            ->setParameter('passwd',md5($param) )
            ->setParameter('email',$mail);
        return $query->getResult();
    }

    //************************************************

    public function updatelastname($id,$param){
        $query=$this->getEntityManager()
            ->createQuery('update APP\Entity\client c set c.nom=:nom where c.id=:id')
            ->setParameter('nom',$param)
            ->setParameter('id',$id);
        return $query->getResult();
    }
    public function updatefirstname($id,$param){
        $query=$this->getEntityManager()
            ->createQuery('update APP\Entity\client c set c.prenom=:prenom where c.id=:id')
            ->setParameter('prenom',$param)
            ->setParameter('id',$id);
        return $query->getResult();
    }
    public function updatepwd($id,$param){
        $query=$this->getEntityManager()
            ->createQuery('update APP\Entity\client c set c.passwd=:passwd where c.id=:id')
            ->setParameter('passwd',md5($param) )
            ->setParameter('id',$id);
        return $query->getResult();
    }

    //*******************************************************************

    public function blockclient($id){
        $query=$this->getEntityManager()
                    ->createQuery('update APP\Entity\client c set c.isblocked=:isblocked where c.id=:id')
                    ->setParameter('isblocked',1)
                    ->setParameter('id',$id);
        return $query->getResult();
    }
    public function unblockclient($id){
        $query=$this->getEntityManager()
            ->createQuery('update APP\Entity\client c set c.isblocked=:isblocked where c.id=:id')
            ->setParameter('isblocked',0)
            ->setParameter('id',$id);
        return $query->getResult();
    }

}