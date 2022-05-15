<?php

namespace App\Controller;

use App\Entity\Livraison;
use App\Entity\Client;
use App\Form\LivraisonType;
use App\From\passerLivraisonType;
use App\Repository\LivraisonRepository;
use App\Repository\ClientRepository;
use App\Repository\CommandesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;




/**
 * @Route("/livraison")
 */
class LivraisonController extends AbstractController
{
    /**
     * @Route("/", name="app_livraison_index", methods={"GET"})
     */
    public function index(LivraisonRepository $livraisonRepository, Request $request, PaginatorInterface $paginator,ClientRepository $usrRep): Response
    {
         ###
         
         $client = new Client();
         
         $user=$this->getUser()->getUsername();
         $currentuser=$usrRep->findOneBy(array('email'=>$user));
         $id= $currentuser->getId();
         if($user == "Admin@sahti.com")
            $livraisons = $livraisonRepository->findAll();
        else
            $livraisons = $livraisonRepository->findBy(array("client"=>$id));
        $livraisons = $paginator->paginate($livraisons,$request->query->getInt('page',1) ,6);
         ####
        return $this->render('livraison/index.html.twig', [
            'livraisons' => $livraisons
        ]);
    }
    
    /**
     * @Route("/livree/{id}", name="passer_livraison", methods={"GET", "POST"})
     */
    public function passerLivraison(Request $request, ClientRepository $clientRepository,LivraisonRepository $livraisonRepository, CommandesRepository $commandeRepository): Response
    {
    
        $id = $request->get('id');
        //dd($email);
        $commande = $commandeRepository->findOneBy(array('id'=>$id));
        $idClient = $commande->getIdClient();
       // $client = $clientRepository->findOneBy(array("id"=>$idClient));
        $livraison = new Livraison();
        $livraison->setCommande($commande);
        
        //dd($livraison);
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $livraison->setCommande($commande);
            $livraison->setIdClient($idClient);
            $livraisonRepository->add($livraison);
            return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livraison/new.html.twig', [
            'livraison' => $livraison,
            'form' => $form->createView(),
        ]);
        

    }

    /**
     * @Route("/new", name="app_livraison_new", methods={"GET", "POST"})
     */
    public function new(Request $request, LivraisonRepository $livraisonRepository): Response
    {
        $livraison = new Livraison();
        
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $livraisonRepository->add($livraison);
            return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livraison/new.html.twig', [
            'livraison' => $livraison,
            'form' => $form->createView(),
        ]);
    }
   
    /**
     * @Route("/{id}", name="app_livraison_show", methods={"GET"})
     */
    public function show(Livraison $livraison): Response
    {
        return $this->render('livraison/show.html.twig', [
            'livraison' => $livraison,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_livraison_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, LivraisonRepository $livraisonRepository,EntityManagerInterface $entityManager, ClientRepository $usrRep): Response
    {
        $idLiv = $request->get('id');
        $user=$this->getUser()->getUsername();
        $currentuser=$usrRep->findOneBy(array('email'=>$user));
        $id= $currentuser->getId();
        
        $livraison = $entityManager->getRepository(Livraison::class)->findOneBy(array('id'=>$idLiv));    
        $livraison->setIdClient($id);

        


        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $livraisonRepository->add($livraison);
            return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livraison/edit.html.twig', [
            'livraison' => $livraison,
            'form' => $form->createView(),
        ]);
    }
    

    /**
     * @Route("/{id}", name="app_livraison_delete", methods={"POST"})
     */
    public function delete(Request $request, Livraison $livraison, LivraisonRepository $livraisonRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livraison->getId(), $request->request->get('_token'))) {
            $livraisonRepository->remove($livraison);
        }

        return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
    }
    
}
