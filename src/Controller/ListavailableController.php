<?php

namespace App\Controller;

use App\Entity\Entraineur;
use App\Entity\Nutritioniste;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListavailableController extends AbstractController
{
    /**
     * @Route("/listavailable", name="app_listavailable")
     */
    public function index(): Response
    {
        return $this->render('listavailable/index.html.twig', [
            'controller_name' => 'ListavailableController',
        ]);
    }

    /**
     * @return Response
     * @Route("/listavailablecoach",name ="listavailablecoach")
     */
    public function readE(){
        $listee= $this -> getDoctrine() -> getRepository(Entraineur::class)->findAll();

        return $this->render("listavailable/listavailablecoach.html.twig", ['listeE'=> $listee]);
    }

    /**
     * @return Response
     * @Route("/addcoach/{id}",name ="addcoach")
     */
    public function addcoachtoClient($id){
        return $this->redirectToRoute('listavailablecoach');

    }

    //**************************************************************

    /**
     * @return Response
     * @Route("/listavailablenutri",name ="listavailablenutri")
     */
    public function readN(){
        $listen= $this -> getDoctrine() -> getRepository(Nutritioniste::class)->findAll();

        return $this->render("listavailable/listavailablenutri.html.twig", ['listeN'=> $listen]);
    }

    /**
     * @return Response
     * @Route("/addnutri/{id}",name ="addnutri")
     */
    public function addnutritoClient($id){
        return $this->redirectToRoute('listavailablenutri');

    }

}
