<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Notification\NouveauCompteNotification;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;

/**
 *
 * @Route("/produit")
 */
class ProduitController extends AbstractController
{
    /**
     * @var NouveauCompteNotification
     */
    private $notify_creation;

    public function __construct(NouveauCompteNotification $notify_creation)
    {
        $this->notify_creation = $notify_creation;
    }

    /**
     * @Route("/", name="app_produit_index", methods={"GET"})
     */
    public function index(ProduitRepository $rep,EntityManagerInterface $entityManager,Request $request,PaginatorInterface $paginator): Response
    {
        $Prod = $rep->repture();
        $this->notify_creation->notify($Prod);
        $donnees = $entityManager
            ->getRepository(Produit::class)
            ->findAll();
        $produits = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            4 // Nombre de résultats par page
        );

        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
        ]);
    }

    /**
     * @Route("/new", name="app_produit_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $produit->getImage();
            $fileName=md5(uniqid()) .'.'.$file->guessExtension();
            try
            {
            $file->move(
                $this->getParameter('images_directory'),
                $fileName
            );}
            catch (FileException $e){
            }
            $produit->setImage($fileName);
            $entityManager->persist($produit);
            $entityManager->flush();
            $this->addFlash('success', 'votre produit a ete ajouté');
            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idProd}", name="app_produit_show", methods={"GET"})
     */
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    /**
     * @Route("/{idProd}/edit", name="app_produit_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $produit->getImage();
            $fileName=md5(uniqid()) .'.'.$file->guessExtension();
            try
            {
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );}
            catch (FileException $e){
            }
            $produit->setImage($fileName);
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idProd}", name="app_produit_delete", methods={"POST"})
     */
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $produit->getIdProd(), $request->request->get('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @param ProduitRepository $repository
     * @return Response
     * @Route ("tri",name="tri")
     */
    function Order(ProduitRepository  $repository,Request $request,PaginatorInterface $paginator){
        $donnees=$repository->Order();
        $produits = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            4 // Nombre de résultats par page
        );
        return $this->render("produit/index.html.twig",
            ['produits'=>$produits]);
    }
    /**
     * @Route("/r/search_back1", name="search_back1",methods={"GET"})
     */

    public function search_back1(Request $request,ProduitRepository $Repository ): Response
    {

        $requestString = $request->get('searchValue');
        $produits = $Repository->findTeamwithNumber($requestString);
        $responseArray = [];
        $idx = 0;
        foreach ($produits as $produit) {
            $temp = [
                'idProd' => $produit->getIdProd(),
                'nom' => $produit->getNom(),
                'prix' => $produit->getPrix(),
                'image' => $produit->getImage(),
                'quantite' => $produit->getQuantite(),
                'descprod' => $produit->getDescprod(),

            ];

            $responseArray[$idx++] = $temp;
        }
        return new JsonResponse($responseArray);
    }
    /**
     * @Route("DOWNtriEQUIPE", name="DOWNtriEQUIPE",options={"expose"=true})
     */
    public function DOWNtriEQUIPE(Request $request,ProduitRepository $repository): JsonResponse
    {

        $UPorDOWN=$request->get('order');
        $Produits=$repository->DescProdSearch($UPorDOWN);
        $responseArray = [];
        $idx = 0;
        foreach ($Produits as $produit){
            $temp = [
                'idProd' => $produit->getIdProd(),
                'nom' => $produit->getNom(),
                'prix' => $produit->getPrix(),
                'image' => $produit->getImage(),
                'quantite' => $produit->getQuantite(),
                'descprod' => $produit->getDescprod(),
            ];

            $responseArray[$idx++] = $temp;

        }
        return new JsonResponse($responseArray);
    }



    /**
     * @Route("UPtriEQUIPE", name="UPtriEQUIPE",options={"expose"=true})
     */
    public function UPtriEQUIPE(Request $request,ProduitRepository $repository): JsonResponse
    {
        $UPorDOWN=$request->get('order');
        $Produits=$repository->AscProdSearch ($UPorDOWN);
        $responseArray = [];
        $idx = 0;
        foreach ($Produits as $produit){
            $temp = [
                'idProd' => $produit->getIdProd(),
                'nom' => $produit->getNom(),
                'prix' => $produit->getPrix(),
                'image' => $produit->getImage(),
                'quantite' => $produit->getQuantite(),
                'descprod' => $produit->getDescprod(),
            ];
            $responseArray[$idx++] = $temp;

        }
        return new JsonResponse($responseArray);
    }


    /**
    * @Route("imprProd", name="imprProd")
    */
    public function imprimer(ProduitRepository  $repository ,EntityManagerInterface $entityManager): Response

    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('isRemoteEnabled', true);
        $pdfOptions->set('isHtml5ParserEnabled', true);
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        $produits = $entityManager
            ->getRepository(Produit::class)
            ->findAll();

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('produit/pdf.html.twig', [
            'produits' => $produits,

        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("Liste  Produit.pdf", [
            "Attachment" => true

        ]);
    }

}
