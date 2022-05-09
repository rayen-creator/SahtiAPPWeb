<?php

namespace App\Controller;

use App\Entity\Aliment;
use App\Entity\Repas;
use App\Form\AlimentType;
use App\Repository\AlimentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;




/**
 * @Route("/aliment")
 */
class AlimentController extends AbstractController
{
    /**
     * @Route("/", name="aliment_index", methods={"GET"})
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $allaliments = $this->getDoctrine()
            ->getRepository(Aliment::class)
            ->findAll();
        //Paginate the results of the query
        $aliments = $paginator->paginate(
        // Doctrine Query, not results
            $allaliments,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            3
        );

        return $this->render('aliment/index.html.twig', [
            'aliments' => $aliments,
        ]);

    }

    /**
     * @Route("/front", name="aliment_front", methods={"GET"})
     */
    public function front(SessionInterface $session, Request $request): Response
    {
        $aliments = $this->getDoctrine()
            ->getRepository(Aliment::class)
            ->findAll();

        return $this->render('aliment/showaliment.html.twig', [
            'aliments' => $aliments,
        ]);
    }


    /**
     * @Route("/new", name="aliment_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $aliment = new Aliment();
        $form = $this->createForm(AlimentType::class, $aliment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ImageFile = $form->get('image')->getData();
            if ($ImageFile) {

                // this is needed to safely include the file name as part of the URL

                $newFilename = md5(uniqid()) . '.' . $ImageFile->guessExtension();
                $destination = $this->getParameter('kernel.project_dir') . '/public/images/aliment';
                // Move the file to the directory where brochures are stored
                try {
                    $ImageFile->move(
                        $destination,
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'ImageFilename' property to store the PDF file name
                // instead of its contents
                $aliment->setImage($newFilename);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($aliment);
            $entityManager->flush();


            return $this->redirectToRoute('aliment_index');
        }

        return $this->render('aliment/new.html.twig', [
            'aliment' => $aliment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idAliment}", name="aliment_show", methods={"GET"})
     */
    public function show(Aliment $aliment): Response
    {
        return $this->render('aliment/show.html.twig', [
            'aliment' => $aliment,
        ]);
    }

    /**
     * @Route("/id/{idAliment}", name="alimentfrontItem", methods={"GET"})
     */
    public function sho(Aliment $aliment): Response
    {

        return $this->render('aliment/detailaliment.html.twig', [
            'aliment' => $aliment,
        ]);
    }



    /**
     * @param AlimentRepository $alimentRepository
     * @param Request $request
     * @return Response
     * @Route("/search",name="search")
     */
    public function searchAliment(AlimentRepository $alimentRepository, Request $request)
    {
        $requestString = $request->get('searchValue');
        if (strlen($requestString) > 0) {
            $aliment = $alimentRepository->findAlimentByNom($requestString);

        } else {
            $aliment = $alimentRepository->findAll();
        }

        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent = $serializer->serialize($aliment, 'json', ['ignored_attributes' => ['idRepas']]);


        $response = new Response(json_encode($jsonContent));
        $response->headers->set('Content-Type', 'application/json; charset=utf-8');

        return $response;
    }

    /**
     * @Route("/{idAliment}/edit", name="aliment_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Aliment $aliment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AlimentType::class, $aliment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ImageFile = $form->get('image')->getData();
            if ($ImageFile) {

                // this is needed to safely include the file name as part of the URL

                $newFilename = md5(uniqid()) . '.' . $ImageFile->guessExtension();
                $destination =
                    $this->getParameter('kernel.project_dir') . '/public/images/aliment';
                // Move the file to the directory where brochures are stored
                try {
                    $ImageFile->move(
                        $destination,
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'ImageFilename' property to store the PDF file name
                // instead of its contents
                $aliment->setImage($newFilename);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('aliment_index');
        }

        return $this->render('aliment/edit.html.twig', [
            'aliment' => $aliment,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{idAliment}", name="aliment_delete", methods={"POST"})
     */
    public function delete(Request $request, Aliment $aliment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $aliment->getIdAliment(), $request->request->get('_token'))) {
            $entityManager->remove($aliment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('aliment_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("statistiques/{idAliment}", name="statevenement")
     */
    public function stat(AlimentRepository $alimentRepository): Response
    {
        $nbrs[] = array();

        $a1 = $alimentRepository->find_Nb_Rec_Par_Status("Vegan");
        dump($a1);
        $nbrs[] = $a1[0][1];


        $a2 = $alimentRepository->find_Nb_Rec_Par_Status("Non Vegan");
        dump($a2);
        $nbrs[] = $a2[0][1];

        /*
                $e3=$activiteRepository->find_Nb_Rec_Par_Status("Diffence");
                dump($e3);
                $nbrs[]=$e3[0][1];
        */

        dump($nbrs);
        reset($nbrs);
        dump(reset($nbrs));
        $key = key($nbrs);
        dump($key);
        dump($nbrs[$key]);

        unset($nbrs[$key]);

        $nbrss = array_values($nbrs);
        dump(json_encode($nbrss));

        return $this->render('aliment/statis.html.twig', [
            'nbr' => json_encode($nbrss),
        ]);
    }

    /**
     * @Route("/pdf", name="PDF", methods={"GET"})
     */
    public function pdf(AlimentRepository $alimentRepository): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('user/PdfUser.html.twig', [
            'aliment' => $alimentRepository->findAll(),
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();
        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("mypdf.pdf", [
            "aliments" => true
        ]);
    }


    /**
     * @Route("/r/search_back1", name="search_back1",methods={"GET"})
     */

    public function search_back1(Request $request,AlimentRepository $alimentRepository): Response
    {

        $requestString = $request->get('searchValue');
        $Aliments = $alimentRepository->findTeamwithNumber($requestString);
        $responseArray = [];
        $idx = 0;
        foreach ($Aliments as $aliment) {
            $temp = [
                'idAliment' => $aliment->getIdAliment(),
                'nom' => $aliment->getNom(),
                'type' => $aliment->getType(),
                'calories' => $aliment->getCalories(),
                'image'=>$aliment->getImage(),
                'description' => $aliment->getDescription(),

            ];

            $responseArray[$idx++] = $temp;
        }
        return new JsonResponse($responseArray);
    }

    /**
     * @Route("DOWNtriEQUIPE", name="DOWNtriEQUIPE",options={"expose"=true})
     */
    public function DOWNtriEQUIPE(Request $request,AlimentRepository $repository): JsonResponse
    {

        $UPorDOWN=$request->get('order');
        $Aliments=$repository->DescReclamationSearch($UPorDOWN);
        $responseArray = [];
        $idx = 0;
        foreach ($Aliments as $aliment){
            $temp = [
                'idAliment' => $aliment->getIdAliment(),
                'nom' => $aliment->getNom(),
                'type' => $aliment->getType(),
                'calories' => $aliment->getCalories(),
                'image'=>$aliment->getImage(),
                'description' => $aliment->getDescription(),

            ];

            $responseArray[$idx++] = $temp;

        }
        return new JsonResponse($responseArray);
    }



    /**
     * @Route("UPtriEQUIPE", name="UPtriEQUIPE",options={"expose"=true})
     */
    public function UPtriEQUIPE(Request $request,AlimentRepository $repository): JsonResponse
    {


        $UPorDOWN=$request->get('order');
        $Aliments=$repository->AscReclamationSearch ($UPorDOWN);
        $responseArray = [];
        $idx = 0;
        foreach ($Aliments as $aliment){
            $temp = [
                'idAliment' => $aliment->getIdAliment(),
                'nom' => $aliment->getNom(),
                'type' => $aliment->getType(),
                'calories' => $aliment->getCalories(),
                'image' => $aliment->getImage(),
                'description' => $aliment->getDescription(),

            ];
            $responseArray[$idx++] = $temp;

        }
        return new JsonResponse($responseArray);
    }



}
