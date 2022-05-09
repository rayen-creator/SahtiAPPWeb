<?php

namespace App\Controller;

use App\Entity\Regime;
use App\Form\RegimeType;
use App\Repository\RegimeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\BarChart;


/**
 * @Route("/regime")
 */
class RegimeController extends AbstractController
{
    /**
     * @Route("/", name="regime_index", methods={"GET"})
     */
    public function index(Request $request , PaginatorInterface $paginator): Response
    {
        $allregimes = $this->getDoctrine()
            ->getRepository(Regime::class)
            ->findAll();
        //Paginate the results of the query
        $regimes= $paginator->paginate(
        // Doctrine Query, not results
            $allregimes,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            3
        );

        return $this->render('regime/index.html.twig', [
            'regimes' => $regimes,
        ]);
    }
    /**
     * @Route("/new", name="regime_new", methods={"GET", "POST"})
     */
    public function new(Request $request,MailerInterface $mailer): Response
    {
        $regime = new Regime();
        $form = $this->createForm(RegimeType::class, $regime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $ImageFile = $form->get('image')->getData();
            if ($ImageFile) {

                // this is needed to safely include the file name as part of the URL

                $newFilename = md5(uniqid()).'.'.$ImageFile->guessExtension();
                $destination = $this->getParameter('kernel.project_dir').'/public/images/regime';
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
                $regime->setImage($newFilename);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($regime);
            $entityManager->flush();


            $email = (new  TemplatedEmail())
                ->from('noreplysahti@gmail.com')
                // On attribue le destinataire
                ->to('nourchen.hedfi@esprit.tn')
                // On crée le texte avec la vue
                ->subject('Regime ajouté')
                ->htmlTemplate('regime/email.html.twig')
                ->context([
                    'regime' => $regime,
                ]);
            $mailer->send($email);
            return $this->redirectToRoute('regime_index');

        }

        return $this->render('regime/new.html.twig', [
            'regime' => $regime,
            'form' => $form->createView(),
        ]);
    }

    public function mailing($name, \Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('nourchen.hedfi@esprit.tn')
            ->setTo('iheb.akrimi@esprit.tn')
            ->setBody( 'LGHADRA LEHA NESHA')

            // you can remove the following code if you don't define a text version for your emails
        ;

        $mailer->send($message);

    }
    /**
     * @Route("/front", name="regime_front", methods={"GET"})
     */
    public function front(Request $request): Response
    {

        $regimes = $this->getDoctrine()
            ->getRepository(Regime::class)
            ->findAll();

        return $this->render('regime/showregime.html.twig', [
            'regimes' => $regimes,
        ]);
    }
    /**
     * @Route("/{idRegime}", name="regime_show", methods={"GET"})
     */
    public function show(Regime $regime): Response
    {
        return $this->render('regime/show.html.twig', [
            'regime' => $regime,
        ]);
    }

    /**
     * @Route("/{idRegime}/edit", name="regime_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Regime $regime, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RegimeType::class, $regime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ImageFile = $form->get('image')->getData();
            if ($ImageFile) {

                // this is needed to safely include the file name as part of the URL
                $newFilename = md5(uniqid()).'.'.$ImageFile ->guessExtension();
                $destination = $this->getParameter('kernel.project_dir').'/public/images/regime';
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
                $regime->setImage($newFilename);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('regime_index');
        }

        return $this->render('regime/edit.html.twig', [
            'regime' => $regime,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idRegime}", name="regime_delete", methods={"POST"})
     */
    public function delete(Request $request, Regime $regime, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$regime->getIdRegime(), $request->request->get('_token'))) {
            $entityManager->remove($regime);
            $entityManager->flush();
        }

        return $this->redirectToRoute('regime_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/id/{idRegime}", name="regimefrontItem", methods={"GET"})
     */
    public function sho(Regime $regime): Response
    {
        return $this->render('regime/detailregime.html.twig', [
            'regime' => $regime,
        ]);
    }
    /**
     * @Route("statistiques",name="statistiquesRec")
     * @param RegimeRepository $repository
     * @return Response
     */
    public function stat(RegimeRepository $repository): Response
    {

        $regime = $repository->statisti();
          $data = [['rate', 'regime']];
    foreach ($regime as $nb) {
        $data[] = array($nb['objectif'], $nb['rec']);
    }
    $bar = new barchart();
    $bar->getData()->setArrayToDataTable(
        $data
    );

    $bar->getOptions()->getTitleTextStyle()->setColor('#07600');
    $bar->getOptions()->getTitleTextStyle()->setFontSize(50);
    return $this->render('regime/stat.html.twig', array('barchart' => $bar, 'nbs' => $regime));
    }

}
