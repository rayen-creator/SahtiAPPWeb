<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ProduitRepository;

class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="panier_index")
     */
    public function index(SessionInterface $session, ProduitRepository $produitRepository): Response
    {
        $panier = $session->get('panier',[]);
        $panierWithData = [];
        foreach($panier as $id => $quatity){
            $panierWithData[]= [
                'produit' => $produitRepository->find($id),
                'quantite' => $quatity
            ];
        }
        //dd($panierWithData);
        $total = 0;
        foreach ($panierWithData as $item ){
            $totalItem = $item['produit']->getPrix() * $item['quantite'];
            $total += $totalItem;
        }
        //
        //dd($panier);
        return $this->render('panier/index.html.twig', [
            'items' => $panierWithData,
            'total' => $total
        ]);
    }
/*
offre feha ref_equipement

*/ 
    /**
     * @Route("/panier/add/{id}", name="cart_add")
     */
    public function add($id, SessionInterface $session){
       
        $panier = $session-> get('panier',[]);
        if(!empty($panier[$id])){
            $panier[$id]++;
        }else{
            $panier[$id]=1;
        }
        $session->set('panier', $panier);
        //dd($session->get('panier'));
        return $this->redirectToRoute('app_magasin');
    }
    /** 
    * @Route("/panier/remove/{id}", name="supp_prod_panier")
    */
     
    public function remove($id, SessionInterface $session){
        $panier = $session->get('panier', []);
        if(!empty($panier[$id])){
            unset($panier[$id]);
        }
        $session->set('panier', $panier);
        return $this->redirectToRoute("panier_index");
    }
    
}
