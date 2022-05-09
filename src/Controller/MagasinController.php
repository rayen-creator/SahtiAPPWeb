<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MagasinController extends AbstractController
{
    /**
     * @Route("/magasin", name="app_magasin")
     */
    public function index(EntityManagerInterface $entityManager, Request $request, PaginatorInterface $paginator ): Response
    {
        $donnees = $entityManager
            ->getRepository(Produit::class)
            ->findAll();
        $produits = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );
        return $this->render('magasin/index.html.twig', [
            'produits' => $produits,
        ]);
    }
    /**
     * @Route("/magasin/{idProd}", name="app_magasin_detail", methods={"GET"})
     */
    public function show(Produit $produit): Response
    {
        return $this->render('magasin/detail.html.twig', [
            'produit' => $produit,
        ]);
    }
    /**
     * @Route("/search", name="search_mag")
     */
    public function search(Request $request, ProduitRepository $produitRepository, PaginatorInterface $paginator): Response
    {
        $nom = $_GET['nom'];
        $donnees = $produitRepository->createQueryBuilder('u')
            ->select('u')
            ->where("u.nom  = '".$nom."' ")
            ->getQuery()
            ->
            getResult();
        $produits = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );
        return $this->render('magasin/index.html.twig', [
            'produits'=>$produits
        ]);
    }
    /**
     * @Route("filter", name="filter")
     */
    public function Filter( ProduitRepository $repo ,Request $request ,PaginatorInterface $paginator) : Response
    {
        $prix = $_GET['prix'];
        $data = $repo->Filter($prix);
        $produits = $paginator->paginate(
            $data, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );

        return $this->render('magasin/index.html.twig', [
            'produits' => $produits
        ]);  }
}
