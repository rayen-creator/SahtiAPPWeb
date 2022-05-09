<?php

namespace App\Controller;

use App\Entity\Programmeentraineur;
use App\Form\ProgrammeentraineurType;
use App\Repository\ClientRepository;
use App\Repository\ProgrammeentraineurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/programmeentraineur")
 */
class ProgrammeentraineurController extends AbstractController
{
    /**
     * @Route("/ProgrammeentraineurPDF", name="ProgrammeentraineurPDF", methods={"GET"})
     */
    public function print(ProgrammeentraineurRepository $programmeentraineurRepository)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html= $this->render('programmeentraineur/ProgrammeentraineurPDF.html.twig', [
            'programmeentraineurs' => $programmeentraineurRepository->findAll(),
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("ProgrammeentraineurPDF.pdf", [
            "Attachment" => true

        ]);
    }
    /**
     * @Route("/", name="app_programmeentraineur_index", methods={"GET","POST"})
     */
    //public function index(EntityManagerInterface $entityManager): Response
    //{
      //  $programmeentraineurs = $entityManager
        //    ->getRepository(Programmeentraineur::class)
          //  ->findAll();

        //return $this->render('programmeentraineur/index.html.twig', [
          //  'programmeentraineurs' => $programmeentraineurs,
        //]);
    //}
    public function index(ProgrammeentraineurRepository $programmeentraineurRepository, Request $request,PaginatorInterface $paginator): Response
    {
        $back = null;
        if ( $request->isMethod('POST')) {

            if ( $request->request->get('optionsRadios')){
                $SortKey = $request->request->get('optionsRadios');
                switch ($SortKey){
                    case 'id':
                        $programmeentraineurs = $programmeentraineurRepository->SortById();
                        break;

                    case 'idexercice':
                        $programmeentraineurs = $programmeentraineurRepository->SortByIdExercice();
                        break;
                    case 'nompack':
                        $programmeentraineurs = $programmeentraineurRepository->SortByNomPack();
                        break;
                    case 'type':
                        $programmeentraineurs = $programmeentraineurRepository->SortByType();
                        break;


                }
            }
            else
            {
                $type = $request->request->get('optionsearch');
                $value = $request->request->get('Search');
                switch ($type){
                    case 'id':
                        $programmeentraineurs = $programmeentraineurRepository->findById($value);
                        break;

                    case 'idexercice':
                        $programmeentraineurs = $programmeentraineurRepository->findByIdExercice($value);
                        break;
                    case 'nompack':
                        $programmeentraineurs = $programmeentraineurRepository->findByNomPack($value);
                        break;
                    case 'type':
                        $programmeentraineurs = $programmeentraineurRepository->findByType($value);
                        break;


                }
            }


            if ( $programmeentraineurs){
                $back = "success";
            }else{
                $back = "failure";
            }

            $programmeentraineursPaginator = $paginator->paginate(
                $programmeentraineurs, // Requête contenant les données à paginer (ici nos articles)
                $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
                5 // Nombre de résultats par page
            );

            return $this->render('programmeentraineur/index.html.twig', [
                'programmeentraineurs' => $programmeentraineursPaginator,
                'back' => $back,
            ]);
        }

        $programmeentraineurs = $programmeentraineurRepository->findAll();
        $programmeentraineursPaginator = $paginator->paginate(
            $programmeentraineurs, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            5 // Nombre de résultats par page
        );

        return $this->render('programmeentraineur/index.html.twig', [
            'programmeentraineurs' => $programmeentraineursPaginator,
            'back' => $back,
        ]);
    }
    /**
     * @Route("/new", name="app_programmeentraineur_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $programmeentraineur = new Programmeentraineur();
        $form = $this->createForm(ProgrammeentraineurType::class, $programmeentraineur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($programmeentraineur);
            $entityManager->flush();

            return $this->redirectToRoute('app_programmeentraineur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('programmeentraineur/new.html.twig', [
            'programmeentraineur' => $programmeentraineur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_programmeentraineur_show", methods={"GET"})
     */
    public function show(Programmeentraineur $programmeentraineur): Response
    {
        return $this->render('programmeentraineur/show.html.twig', [
            'programmeentraineur' => $programmeentraineur,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_programmeentraineur_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Programmeentraineur $programmeentraineur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProgrammeentraineurType::class, $programmeentraineur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_programmeentraineur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('programmeentraineur/edit.html.twig', [
            'programmeentraineur' => $programmeentraineur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_programmeentraineur_delete", methods={"POST"})
     */
    public function delete(Request $request, Programmeentraineur $programmeentraineur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$programmeentraineur->getId(), $request->request->get('_token'))) {
            $entityManager->remove($programmeentraineur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_programmeentraineur_index', [], Response::HTTP_SEE_OTHER);
    }
}
