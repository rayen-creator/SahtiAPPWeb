<?php

namespace App\Controller;

use App\Entity\Entraineur;
use App\Entity\Nutritioniste;
use App\Repository\ClientRepository;
use App\Repository\CoachRepository;
use App\Repository\NutritionisteRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListavailableController extends AbstractController
{
    /**
     * @Route("/profile/listavailable", name="app_listavailable")
     */
    public function index(): Response
    {
        return $this->render('listavailable/index.html.twig', [
            'controller_name' => 'ListavailableController',
        ]);
    }

    /**
     * @return Response
     * @Route("/profile/listavailablecoach",name ="listavailablecoach")
     */
    public function readE(PaginatorInterface $paginator ,Request $request){
        $listee= $this -> getDoctrine() -> getRepository(Entraineur::class)->findAll();
        $donneeE = $paginator->paginate(
            $listee, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            $request->query->getInt('limit', 6) );
        return $this->render("listavailable/listavailablecoach.html.twig", ['donneeE'=> $donneeE]);
    }

    /**
     * @return Response
     * @Route("/profile/addcoach/{id}",name ="addcoach")
     */
    public function addcoachtoClient($id,ClientRepository $em){
        $foundE= $this -> getDoctrine() -> getRepository(Entraineur::class)->find($id);
        $user=$this->getUser()->getUsername();

        $cb = $em->addcoach($foundE,$user);
        $this->get('session')->getFlashBag()->add(
            'msg',
            'Coach added successfully ready to work ! '
        );
        return $this->redirectToRoute('listavailablecoach');

    }

    //**************************************************************

    /**
     * @return Response
     * @Route("/profile/listavailablenutri",name ="listavailablenutri")
     */
    public function readN(PaginatorInterface $paginator ,Request $request){
        $listen= $this -> getDoctrine() -> getRepository(Nutritioniste::class)->findAll();
        $donneeN = $paginator->paginate(
            $listen, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            $request->query->getInt('limit', 6) );
        return $this->render("listavailable/listavailablenutri.html.twig", ['donneeN'=> $donneeN]);
    }

    /**
     * @return Response
     * @Route("/profile/addnutri/{id}",name ="addnutri")
     */
    public function addnutritoClient($id,ClientRepository $em){
        $foundN= $this -> getDoctrine() -> getRepository(Nutritioniste::class)->find($id);
        $user=$this->getUser()->getUsername();

        $cb = $em->addnutri($foundN,$user);
        $this->get('session')->getFlashBag()->add(
            'msg',
            'Nutritionist added successfully ready to work ! '
        );
        return $this->redirectToRoute('listavailablenutri');

    }

}
