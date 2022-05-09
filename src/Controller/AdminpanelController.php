<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Entraineur;
use App\Entity\Nutritioniste;
use App\Repository\ClientRepository;
use App\Repository\CoachRepository;
use App\Repository\NutritionisteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface; // Nous appelons le bundle KNP Paginator


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
     * @Route("/adminpanel/listclient",name ="listclient")
     */
    public function read(PaginatorInterface $paginator ,Request $request){
        $listec= $this -> getDoctrine() -> getRepository(Client::class)->findAll();
        $donneeE = $paginator->paginate(
            $listec, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            $request->query->getInt('limit', 6) ); // Nombre de résultats par page
        return $this->render("adminpanel/listclient.html.twig", ['donneeE'=> $donneeE]);
    }

    /**
     * @Route("/adminpanel/searchclient", name="searchclient")
     */
    public function searchclient(Request $request)
    {
        $repository = $this -> getDoctrine() -> getRepository(Client::class);
        $requestString = $request->get('searchValue');
        $donneeE = $repository->findByPrenom($requestString);
        return $this->render('adminpanel/utilajaxclient.html.twig', [
            'donneeE' => $donneeE,
        ]);
    }

    /**
     * @Route("/adminpanel/searchactive", name="searchactive")
     */
    function searchactive (PaginatorInterface $paginator ,ClientRepository $repository,Request $request){
//        $data=$repository->findBy(array('isblocked'=>[0]),null,null,0);

        $ban=$repository->findBy(['isblocked'=>0]);
        $donneeE = $paginator->paginate(
            $ban, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            $request->query->getInt('limit', 6) ); // Nombre de résultats par page
        return $this->render('adminpanel/listclient.html.twig', [
            'donneeE' => $donneeE,
        ]);
    }

    /**
     * @Route("/adminpanel/searchblocked", name="searchblocked")
     */
    function searchblocked (PaginatorInterface $paginator ,ClientRepository $repository,Request $request){
//        $data=$repository->findBy(array('isblocked'=>[0]),null,null,0);

        $ban=$repository->findBy(['isblocked'=>1]);
        $donneeE = $paginator->paginate(
            $ban, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            $request->query->getInt('limit', 6) ); // Nombre de résultats par page
        return $this->render('adminpanel/listclient.html.twig', [
            'donneeE' => $donneeE,
        ]);
    }

    /**
     * @return Response
     * @Route("/adminpanel/deleteclient/{id}",name ="deleteclient")
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
     * @Route("/adminpanel/blockclient/{id}",name ="blockclient")
     */
    public function block($id , ClientRepository $em){
        $foundclient= $this -> getDoctrine() -> getRepository(Client::class)->find($id);
        $cb = $em->blockclient($foundclient);
        return $this->redirectToRoute('listclient');
    }
    /**
     * @return Response
     * @Route("/adminpanel/unblockclient/{id}",name ="unblockclient")
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
     * @Route("/adminpanel/listcoach",name ="listcoach")
     */
    public function readE(PaginatorInterface $paginator ,Request $request){
        $listee= $this -> getDoctrine() -> getRepository(Entraineur::class)->findAll();
        $donneeC = $paginator->paginate(
            $listee, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            $request->query->getInt('limit', 6) );

        return $this->render("adminpanel/listcoach.html.twig", ['donneeC'=> $donneeC]);
    }


    /**
     * @Route("/adminpanel/searchcoach", name="searchcoachlive")
     */
    public function searchcoach(Request $request)
    {
        $repository = $this -> getDoctrine() -> getRepository(Entraineur::class);
        $requestString = $request->get('searchValue');
        $donneeC = $repository->findPlanBySujet($requestString);
        return $this->render('adminpanel/utilajaxcoach.html.twig', [
            'donneeC' => $donneeC,
        ]);
    }

    /**
     * @Route("/adminpanel/searchactivecoach", name="searchcoach")
     */
    function searchactivecoach (PaginatorInterface $paginator ,CoachRepository $repository,Request $request){

        $ban=$repository->findBy(['isblocked'=>0]);
        $donneeC = $paginator->paginate(
            $ban, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            $request->query->getInt('limit', 6) ); // Nombre de résultats par page
        return $this->render('adminpanel/listcoach.html.twig', [
            'donneeC' => $donneeC,
        ]);
    }

    /**
     * @Route("/adminpanel/searchblockedcoach", name="searchblockedcoach")
     */
    function searchblockedcoach (PaginatorInterface $paginator ,CoachRepository $repository,Request $request){

        $ban=$repository->findBy(['isblocked'=>1]);
        $donneeC = $paginator->paginate(
            $ban, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            $request->query->getInt('limit', 6) ); // Nombre de résultats par page
        return $this->render('adminpanel/listcoach.html.twig', [
            'donneeC' => $donneeC,
        ]);
    }

    /**
     * @return Response
     * @Route("/adminpanel/deletecoach/{id}",name ="deletecoach")
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
     * @Route("/adminpanel/blockcoach/{id}",name ="blockcoach")
     */
    public function blockE($id , CoachRepository $em){
        $foundE= $this -> getDoctrine() -> getRepository(Entraineur::class)->find($id);
        $cb = $em->blockcoach($foundE);
        return $this->redirectToRoute('listcoach');
    }
    /**
     * @return Response
     * @Route("/adminpanel/unblockcoach/{id}",name ="unblockcoach")
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
     * @Route("/adminpanel/listnutri",name ="listnutri")
     */
    public function readN(PaginatorInterface $paginator ,Request $request){
        $listen= $this -> getDoctrine() -> getRepository(Nutritioniste::class)->findAll();
        $donneeN = $paginator->paginate(
            $listen, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            $request->query->getInt('limit', 6) );
        return $this->render("adminpanel/listnutri.html.twig", ['donneeN'=> $donneeN]);
    }

    /**
     * @Route("/adminpanel/searchactivenutri", name="searchactivenutri")
     */
    function searchactivenutri (PaginatorInterface $paginator ,NutritionisteRepository $repository,Request $request){

        $ban=$repository->findBy(['isblocked'=>0]);
        $donneeN = $paginator->paginate(
            $ban, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            $request->query->getInt('limit', 6) ); // Nombre de résultats par page
        return $this->render('adminpanel/listnutri.html.twig', [
            'donneeN' => $donneeN,
        ]);
    }

    /**
     * @Route("/adminpanel/searchblockednutri", name="searchblockednutri")
     */
    function searchblockednutri(PaginatorInterface $paginator ,NutritionisteRepository $repository,Request $request){

        $ban=$repository->findBy(['isblocked'=>1]);
        $donneeN = $paginator->paginate(
            $ban, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            $request->query->getInt('limit', 6) ); // Nombre de résultats par page
        return $this->render('adminpanel/listnutri.html.twig', [
            'donneeN' => $donneeN,
        ]);
    }

    /**
     * @Route("/adminpanel/searchnutri", name="searchnutri")
     */
    public function searchnutri(Request $request)
    {
        $repository = $this -> getDoctrine() -> getRepository(Nutritioniste::class);
        $requestString = $request->get('searchValue');
        $donneeN = $repository->findPlanBySujet($requestString);
        return $this->render('adminpanel/utilajaxnutri.html.twig', [
            'donneeN' => $donneeN,
        ]);
    }

    /**
     * @return Response
     * @Route("/adminpanel/deletenutri/{id}",name ="deletenutri")
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
     * @Route("/adminpanel/blocknutri/{id}",name ="blocknutri")
     */
    public function blockN($id , NutritionisteRepository $em){
        $foundN= $this -> getDoctrine() -> getRepository(Nutritioniste::class)->find($id);
        $cb = $em->blocknutri($foundN);
        return $this->redirectToRoute('listnutri');
    }
    /**
     * @return Response
     * @Route("/adminpanel/unblocknutri/{id}",name ="unblocknutri")
     */
    public function unblockN($id , NutritionisteRepository $em){
        $foundN= $this -> getDoctrine() -> getRepository(Nutritioniste::class)->find($id);
        $cb = $em->unblocknutri($foundN);
        return $this->redirectToRoute('listnutri');
    }

    //**************************************

}
