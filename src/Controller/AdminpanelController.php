<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Entraineur;
use App\Entity\Nutritioniste;
use App\Repository\ClientRepository;
use App\Repository\CoachRepository;
use App\Repository\NutritionisteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminpanelController extends AbstractController
{
    /**
     * @Route("/adminpanel", name="app_adminpanel")
     */
    public function index(): Response
    {
        return $this->render('adminpanel/index.html.twig', [
            'controller_name' => 'AdminpanelController',
        ]);
    }

    /**
     * @return Response
     * @Route("/listclient",name ="listclient")
     */
    public function read(){
        $listec= $this -> getDoctrine() -> getRepository(Client::class)->findAll();

        return $this->render("adminpanel/listclient.html.twig", ['listeC'=> $listec]);
    }
    /**
     * @return Response
     * @Route("/deleteclient/{id}",name ="deleteclient")
     */
    public function delete($id){
        $listec= $this -> getDoctrine() -> getRepository(Client::class)->find($id);
        $em=$this -> getDoctrine()->getManager();
        $em->remove($listec);
        $em->flush();
        return $this->redirectToRoute('listclient');
    }
    /**
     * @return Response
     * @Route("/blockclient/{id}",name ="blockclient")
     */
    public function block($id , ClientRepository $em){
        $foundclient= $this -> getDoctrine() -> getRepository(Client::class)->find($id);
        $cb = $em->blockclient($foundclient);
        return $this->redirectToRoute('listclient');
    }
    /**
     * @return Response
     * @Route("/unblockclient/{id}",name ="unblockclient")
     */
    public function unblock($id , ClientRepository $em){
        $foundclient= $this -> getDoctrine() -> getRepository(Client::class)->find($id);
        $cb = $em->unblockclient($foundclient);
        return $this->redirectToRoute('listclient');
    }
    //****************************************

    //************Coach*********************

    /**
     * @return Response
     * @Route("/listcoach",name ="listcoach")
     */
    public function readE(){
        $listee= $this -> getDoctrine() -> getRepository(Entraineur::class)->findAll();

        return $this->render("adminpanel/listcoach.html.twig", ['listeE'=> $listee]);
    }
    /**
     * @return Response
     * @Route("/deletecoach/{id}",name ="deletecoach")
     */
    public function deleteE($id){
        $listee= $this -> getDoctrine() -> getRepository(Client::class)->find($id);
        $em=$this -> getDoctrine()->getManager();
        $em->remove($listee);
        $em->flush();
        return $this->redirectToRoute('listcoach');
    }
    /**
     * @return Response
     * @Route("/blockcoach/{id}",name ="blockcoach")
     */
    public function blockE($id , CoachRepository $em){
        $foundE= $this -> getDoctrine() -> getRepository(Entraineur::class)->find($id);
        $cb = $em->blockcoach($foundE);
        return $this->redirectToRoute('listcoach');
    }
    /**
     * @return Response
     * @Route("/unblockcoach/{id}",name ="unblockcoach")
     */
    public function unblockE($id , CoachRepository $em){
        $foundE= $this -> getDoctrine() -> getRepository(Entraineur::class)->find($id);
        $cb = $em->unblockcoach($foundE);
        return $this->redirectToRoute('listcoach');
    }

    //**************************************

    //********Nutristionist*****************
    /**
     * @return Response
     * @Route("/listnutri",name ="listnutri")
     */
    public function readN(){
        $listen= $this -> getDoctrine() -> getRepository(Nutritioniste::class)->findAll();

        return $this->render("adminpanel/listnutri.html.twig", ['listeN'=> $listen]);
    }
    /**
     * @return Response
     * @Route("/deletenutri/{id}",name ="deletenutri")
     */
    public function deleteN($id){
        $listen= $this -> getDoctrine() -> getRepository(Nutritioniste::class)->find($id);
        $em=$this -> getDoctrine()->getManager();
        $em->remove($listen);
        $em->flush();
        return $this->redirectToRoute('listnutri');
    }
    /**
     * @return Response
     * @Route("/blocknutri/{id}",name ="blocknutri")
     */
    public function blockN($id , NutritionisteRepository $em){
        $foundN= $this -> getDoctrine() -> getRepository(Nutritioniste::class)->find($id);
        $cb = $em->blocknutri($foundN);
        return $this->redirectToRoute('listnutri');
    }
    /**
     * @return Response
     * @Route("/unblocknutri/{id}",name ="unblocknutri")
     */
    public function unblockN($id , NutritionisteRepository $em){
        $foundN= $this -> getDoctrine() -> getRepository(Nutritioniste::class)->find($id);
        $cb = $em->unblocknutri($foundN);
        return $this->redirectToRoute('listnutri');
    }

    //**************************************

}
