<?php

namespace App\Controller;

use App\Entity\Regime;
use App\Entity\Repas;
use App\Form\RegimeType;
use App\Form\RepasType;
use App\Repository\RegimeRepository;
use App\Repository\RepasRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/repas")
 */
class RepasController extends AbstractController
{
    /**
     * @Route("/", name="repas_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $repas = $entityManager
            ->getRepository(Repas::class)
            ->findAll();

        return $this->render('repas/index.html.twig', [
            'repas' => $repas,
        ]);
    }

    /**
     * @Route("/new", name="repas_new", methods={"GET", "POST"})
     */
    public function new(Request $request,RegimeRepository $regimeRepository): Response
    {
        $repa = new Repas();
        $form = $this->createForm(RepasType::class, $repa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $regimes=$form->get('idRegime')->getData();
            foreach($regimes as $regime){
                $r=$regimeRepository->find($regime);
                $max=$r->getMaxCalories();
                $repas=$r->getRepas();
                $nbc=0;
                foreach ($repas as $r){
                    $nbc+=$r->getNbCal();
                }

                $calories=$form->get('nbCal')->getData();

                if(($calories+$nbc)>$max){
                    return $this->render('repas/new.html.twig', [
                        'repa' => $repa,
                        'form' => $form->createView(),
                        'error'=> 1,
                    ]);
                }
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($repa);
            $entityManager->flush();
            $toastrFactory->addFlash('success', 'Data has been saved successfully!');


            return $this->redirectToRoute('repas_index');
        }

        return $this->render('repas/new.html.twig', [
            'repa' => $repa,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idRepas}", name="repas_show", methods={"GET"})
     */
    public function show(Repas $repa): Response
    {
        return $this->render('repas/show.html.twig', [
            'repa' => $repa,
        ]);
    }

    /**
     * @Route("/{idRepas}/edit", name="repas_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Repas $repa): Response
    {
        $form = $this->createForm(RepasType::class, $repa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('repas_index');
        }

        return $this->render('repas/edit.html.twig', [
            'repa' => $repa,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idRepas}", name="repas_delete", methods={"POST"})
     */
    public function delete(Request $request, Repas $repas): Response
    {
        if ($this->isCsrfTokenValid('delete'.$repas->getIdRepas(),
            $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($repas);
            $entityManager->flush();
        }

        return $this->redirectToRoute('repas_index');
    }


    /**
     * @Route("imp", name="impr")
     */
    public function imprimer(RepasRepository $repository,EntityManagerInterface $entityManager): Response

    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        $repas = $entityManager
            ->getRepository(Repas::class)
            ->findAll();

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('repas/Pdf.html.twig', [
            'repas' => $repas,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("liste des repas.pdf", [
            "Attachment" => true

        ]);
    }

    /**
     * @Route("/TrierParNomDESCr", name="TrierParNomDESCr")
     */
    public function TrierParNomr(Request $request): Response
    {
        $repository = $this->getDoctrine()->getRepository(RepasRepository::class);
        $repas = $repository->findByNamer();

        return $this->render('repas/index.html.twig', [
            'repas' => $repas,
        ]);
    }
    /**
     * @Route("/TrierParNomASCr", name="TrierParNomASCr")
     */
    public function TrierParNomdesr(Request $request): Response
    {
        $repository = $this->getDoctrine()->getRepository(Repas::class);
        $repas = $repository->findByNameascr();

        return $this->render('repas/index.html.twig', [
            'repas' => $repas,
        ]);
    }
    /**
     * @Route("/ff/ff/{idRepas}", name="repas_showfront", methods={"GET"})
     */
    public function show2(Repas $repa): Response
    {
        return $this->render('repas/showrepas.html.twig', [
            'repa' => $repa,
        ]);
    }
    /**
     * @Route("/non_filtre", name="nonfiltre", methods={"GET"})
     */
    public function non_filtre(RepasRepository $repository,SessionInterface $session): Response
    {
        $repas=$repository->findAll();
        $template = $this->render('repas/ajaxtable.html.twig',['repas'=>$repas])->getContent();
        $response = new JsonResponse();
        $response->setStatusCode(200);
        return $response->setData(['template' => $template ]);
    }

    /**
     * @Route("/index/{filtre}/{filtrevalue}", name="repas_filtre", methods={"GET"})
     */
    public function filtre(RepasRepository $repository,SessionInterface $session,$filtre,$filtrevalue): Response
    {
        $repas=$repository->findBy([$filtre=>$filtrevalue]);
        $template = $this->render('repas/ajaxtable.html.twig',['repas'=>$repas])->getContent();
        $response = new JsonResponse();
        $response->setStatusCode(200);
        return $response->setData(['template' => $template ]);
    }


}
