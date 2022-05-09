<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Suivieevolution;
use App\Form\SuivieevolutionType;
use App\Repository\SuivieevolutionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
/**
 * @Route("/suivieevolution")
 */
class SuivieevolutionController extends AbstractController
{
    /**
     * @Route("/SuivieevolutionPDF", name="SuivieevolutionPDF", methods={"GET"})
     */
    public function print(SuivieevolutionRepository $suivieevolutionRepository)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html= $this->render('suivieevolution/SuivieevolutionPDF.html.twig', [
            'suivieevolutions' => $suivieevolutionRepository->findAll(),
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("SuivieevolutionPDF.pdf", [
            "Attachment" => true

        ]);
    }
    /**
     * @Route("/", name="app_suivieevolution_index", methods={"GET","POST"})
     */
   // public function index(EntityManagerInterface $entityManager): Response
    //{
      //  $suivieevolutions = $entityManager
        //    ->getRepository(Suivieevolution::class)
          //  ->findAll();

        //return $this->render('suivieevolution/index.html.twig', [
          //  'suivieevolutions' => $suivieevolutions,
        //]);
    //}
    public function index(SuivieevolutionRepository $suivieevolutionRepository, Request $request,PaginatorInterface $paginator): Response
    {
        $back = null;
        if ( $request->isMethod('POST')) {

            if ( $request->request->get('optionsRadios')){
                $SortKey = $request->request->get('optionsRadios');
                switch ($SortKey){
                    case 'id':
                        $suivieevolutions = $suivieevolutionRepository->SortById();
                        break;

                    case 'iduser':
                        $suivieevolutions = $suivieevolutionRepository->SortByIdUser();
                        break;
                    case 'poids':
                        $suivieevolutions = $suivieevolutionRepository->SortByPoids();
                        break;
                    case 'datedebutprogramme':
                        $suivieevolutions = $suivieevolutionRepository->SortByDateDebutProgramme();
                        break;
                    case 'dateevolution':
                        $suivieevolutions = $suivieevolutionRepository->SortByDateEvolution();
                        break;

                }
            }
            else
            {
                $type = $request->request->get('optionsearch');
                $value = $request->request->get('Search');
                switch ($type){
                    case 'id':
                        $suivieevolutions = $suivieevolutionRepository->findById($value);
                        break;

                    case 'iduser':
                        $suivieevolutions = $suivieevolutionRepository->findByIduser($value);
                        break;
                    case 'poids':
                        $suivieevolutions = $suivieevolutionRepository->findByPoids($value);
                        break;
                    case 'datedebutprogramme':
                        $suivieevolutions = $suivieevolutionRepository->findByDateDebutProgramme($value);
                        break;
                    case 'dateevolution':
                        $suivieevolutions = $suivieevolutionRepository->findByDateEvolution($value);
                        break;


                }
            }


            if ( $suivieevolutions){
                $back = "success";
            }else{
                $back = "failure";
            }

            $suivieevolutionsPaginator = $paginator->paginate(
                $suivieevolutions, // Requête contenant les données à paginer (ici nos articles)
                $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
                5 // Nombre de résultats par page
            );

            return $this->render('suivieevolution/index.html.twig', [
                'suivieevolutions' => $suivieevolutionsPaginator,
                'back' => $back,
            ]);
        }

        $suivieevolutions = $suivieevolutionRepository->findAll();
        $suivieevolutionsPaginator = $paginator->paginate(
            $suivieevolutions, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            5 // Nombre de résultats par page
        );

        return $this->render('suivieevolution/index.html.twig', [
            'suivieevolutions' => $suivieevolutionsPaginator,
            'back' => $back,
        ]);
    }
    /**
     * @Route("/new", name="app_suivieevolution_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $suivieevolution = new Suivieevolution();
        $lusers = $this->getDoctrine()->getRepository(Client::class)->findAll();
        $k = 0;
        $iduser= null ;
        foreach ($lusers as $i){
            $iduser[$lusers[$k]->getId()] = implode((array) $lusers[$k]->getId());
            $k++;}
        $form = $this->createForm(SuivieevolutionType::class, $suivieevolution);
        $form->add('iduser',ChoiceType::class,[
            'choices' => $iduser]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($suivieevolution);
            $entityManager->flush();

            return $this->redirectToRoute('app_suivieevolution_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('suivieevolution/new.html.twig', [
            'suivieevolution' => $suivieevolution,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_suivieevolution_show", methods={"GET"})
     */
    public function show(Suivieevolution $suivieevolution): Response
    {
        return $this->render('suivieevolution/show.html.twig', [
            'suivieevolution' => $suivieevolution,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_suivieevolution_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Suivieevolution $suivieevolution, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SuivieevolutionType::class, $suivieevolution);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_suivieevolution_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('suivieevolution/edit.html.twig', [
            'suivieevolution' => $suivieevolution,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_suivieevolution_delete", methods={"POST"})
     */
    public function delete(Request $request, Suivieevolution $suivieevolution, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$suivieevolution->getId(), $request->request->get('_token'))) {
            $entityManager->remove($suivieevolution);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_suivieevolution_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/triSuivi", name="triSuivi", methods={"GET"})
     */
    public function triSuivi(SuivieevolutionRepository $suivieevolutionRepository): Response
    {
        return $this->render('suivieevolution/triSuivi.html.twig', [
            'suivieevolutions' => $this->getDoctrine()->getRepository(Suivieevolution::class)->createQueryBuilder('s')
                ->orderBy('s.iduser','DESC ')
                ->getQuery()->getResult(),
        ]);
    }

}
