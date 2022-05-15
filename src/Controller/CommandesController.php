<?php

namespace App\Controller;

use App\Entity\Commandes;
use App\Entity\Produit;
use App\Form\CommandesType;
use App\Entity\LigneCommande;
use App\Repository\ClientRepository;
use App\Repository\CommandesRepository;

//use App\Repository\ClientReporsitory;
use App\Entity\Client;

use App\Repository\LigneCommandeRepository;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;


use App\Services\StripeService;
use App\Manager\CommandeManager;



/**
 * @Route("/commandes")
 */
class CommandesController extends AbstractController
{
    protected $stripeService;
//    private ClientRepository $clientRepository;
    

    /**
     * @Route("/", name="app_commandes_index", methods={"GET"})
     */
    public function index(CommandesRepository $commandesRepository,ClientRepository $usrRep, PaginatorInterface $paginator, Request $request): Response
    {
        $client = new Client();
        $user=$this->getUser()->getUsername();
        
        $currentuser=$usrRep->findOneBy(array('email'=>$user));
        $id= $currentuser->getId();
        if($user == "Admin@sahti.com")
            $commandes = $commandesRepository->findBy(array(),array('etat'=>"asc"));
        else
            $commandes = $commandesRepository->findBy(array('client' => $id), array('etat' => "asc"));

        $commandes = $paginator->paginate($commandes,$request->query->getInt('page',1) ,6);


        //$test = $client->getRoles();
        //dd($test);

        return $this->render('commandes/index.html.twig', [
            'commandes' => $commandes
        ]);
    }

    /**
     * @Route("/new", name="app_commandes_new", methods={"GET", "POST"})
     */
    public function new(Request $request, SessionInterface $session, ClientRepository $usrRep,
                        ProduitRepository $produitRepository, CommandesRepository $commandesRepository): Response
    {
        //*********************
        $client = new Client();
        $user=$this->getUser()->getUsername();
        $currentuser=$usrRep->findOneBy(array('email'=>$user));
        $id= $currentuser->getId();
        //dd($id);
        //*********************

        $panier = $session->get('panier', []);
        $panierWithData = [];
        foreach($panier as $id => $quatity){
            $panierWithData[]= [
                'produit' => $produitRepository->find($id),
                'quantite' => $quatity
            ];
        }
        $total = 0;
        foreach ($panierWithData as $item ){
            $totalItem = $item['produit']->getPrix() * $item['quantite'];
            $total += $totalItem;
        }        
        $commande = new Commandes();
        
        //yet7at feha id taa l produit wl id taa lcommande
        $i=1;
        $numCmd = $commandesRepository->findBy(array(),array('id'=>'DESC'),1,0);
        //dd($numCmd);
        if(!empty($numCmd))
            $i += (int)$numCmd[0]->getNumCmd();        
        
        $commande->setNumCmd(strval($i));
        $commande->setMontantCmd($total);
        $commande->setCommentaire("test Comment");
        $commande->setEtat(0);
        $commande->setQtecmd($quatity);
        $commande->setModePay("Carte bancaire");
       
        //$commande
        $em = $this->getDoctrine()->getManager();
        $em->persist($commande);
        $em->flush();
        $qCmd = $commandesRepository->findOneBy(array(),array('id'=>'DESC'),1,0);
        
        //$idCmd = $qCmd[0]->getId();
        $idTab = [];
        foreach ($panierWithData as $item ){
            $id = $item['produit']->getIdProd();
            $idTab[] = $id;
        }
        $test=$item['produit']->getIdProd();
        for ($i = 0;$i<=sizeof($idTab)-1;$i++){
            $lcommande =  new LigneCommande();
            $prod = $produitRepository->findOneBy(array('idProd'=> $idTab[$i]));
            //dd($prod);

            $lcommande->setIdProd($idTab[$i]);
            $lcommande->setIdCmd($qCmd->getId());
            //$idProd = $lcommande->getIdProd()->getId();
            //dd($idTab);
            
            $em->persist($lcommande);
            $em->flush();
        }
        //dd($idTab[0]);
            $idCommande = $commandesRepository->findOneBy(array(),array('id'=>'DESC'),1,0);
        return $this->redirectToRoute('payment', ['id'=>$idCommande->getId(), "client"=>$id], Response::HTTP_SEE_OTHER);
           
    }

    /**
     * @Route("/{id}", name="app_commandes_show", methods={"GET"})
     */
    public function show(Commandes $commande, ProduitRepository $produit, ClientRepository $usrRep, Request $request, LigneCommandeRepository $lcommandeRepository): Response
    {
        $idcmd = $request->get('id');
        ###
        $client = new Client();
        $user=$this->getUser()->getUsername();

        $currentuser=$usrRep->findOneBy(array('email'=>$user));
        $id= $currentuser->getId();
        
        ####
        $idsProd = $lcommandeRepository->findBy(array('idCmd' => $idcmd ),array());
            //dd($idsProd);
        $produits=array();
        for ($i=0; $i<count($idsProd); $i++){
            $produits[] = $produit->findBy(array('idProd'=> $idsProd[$i]->getIdProd()));
        }
        //dd($idsProd[0]->getIdProd());
        
        //dd($produits);
        return $this->render('commandes/show.html.twig', [
            'commande' => $commande,
            'produits' => $produits
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_commandes_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Commandes $commande, CommandesRepository $commandesRepository): Response
    {
        
        $form = $this->createForm(CommandesType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandesRepository->add($commande);
            return $this->redirectToRoute('app_commandes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commandes/edit.html.twig', [
            'commande' => $commande,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_commandes_delete", methods={"POST"})
     */
    public function delete(Request $request, Commandes $commande, CommandesRepository $commandesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $commandesRepository->remove($commande);
        }

        return $this->redirectToRoute('app_commandes_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/valider", name="valider_commande", methods={"GET", "POST"})
     * @param Request $request
     * @param SessionInterface $session
     * @param $produitRepository
     * @return Response
     */
    public function validerCommande(Request $request, SessionInterface $session, ClientRepository $usrRep, ProduitRepository $produitRepository ): Response
    {
        $panier = $session->get('panier', []);
        $panierWithData = [];
        foreach($panier as $id => $quatity){
            $panierWithData[]= [
                'produit' => $produitRepository->find($id),
                'quantite' => $quatity
            ];
        }
        $total = 0;
        foreach ($panierWithData as $item ){
            $totalItem = $item['produit']->getPrix() * $item['quantite'];
            $total += $totalItem;
        }
        $client = new Client();
        $user=$this->getUser()->getUsername();
        $currentuser=$usrRep->findOneBy(array('email'=>$user));
        $id= $currentuser->getId();


        $commande = new Commandes();
        $commande->setNumCmd("test");
        $commande->setMontantCmd($total);
        $commande->setCommentaire("test Comment");
        $commande->setEtat(1);
        $commande->setQtecmd($quatity);
        $commande->setModePay("Carte bancaire");
        $commande->setClient($id);

        //$commande//
        $em = $this->getDoctrine()->getManager();
        $em->persist($commande);
        $em->flush();
        //dd($em);




        //return $this->render('commandes/edit.html.twig');

//yelzem irecuperi es donne wi 7othom fel commande
    }
    /**
     * @Route("/{id}/payment", name="payment",methods={"GET", "POST"})
     */
    public function payment(Commandes $commande, Request $request, CommandesRepository $commandesRepository, CommandeManager $commandeManager): Response
    {
        $id = $request->get('id');
        $commande = $commandesRepository->find($id);
        $commande->setEtat(1);
        $em = $this->getDoctrine()->getManager();
        $em->persist($commande);
        $em->flush();
        
        return $this-> render('commandes/payment.html.twig',[
            'intentSecret' => $commandeManager->intentSecret($commande),
            'commande' => $commande
        ]);
    }
    
}
