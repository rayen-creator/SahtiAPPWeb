<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Progclient;
use App\Entity\Programmeentraineur;
use App\Repository\ProgclientRepository;
use App\Form\ProgclientType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/progclient")
 */
class ProgclientController extends AbstractController
{
    /**
     * @Route("/", name="app_progclient_index", methods={"GET","POST"})
     */
   // public function index(EntityManagerInterface $entityManager): Response
    //{
      //  $progclients = $entityManager
        //    ->getRepository(Progclient::class)
          //  ->findAll();

        //return $this->render('progclient/index.html.twig', [
          //  'progclients' => $progclients,
        //]);
    //}
    public function index(ProgclientRepository $progclientRepository, Request $request,PaginatorInterface $paginator): Response
    {
        $back = null;
        if ( $request->isMethod('POST')) {

            if ( $request->request->get('optionsRadios')){
                $SortKey = $request->request->get('optionsRadios');
                switch ($SortKey){
                    case 'id':
                        $progclients = $progclientRepository->SortById();
                        break;

                    case 'idprog':
                        $progclients = $progclientRepository->SortByIdProg();
                        break;
                    case 'iduser':
                        $progclients = $progclientRepository->SortByIdUser();
                        break;

                }
            }
            else
            {
                $type = $request->request->get('optionsearch');
                $value = $request->request->get('Search');
                switch ($type){
                    case 'id':
                        $progclients = $progclientRepository->findById($value);
                        break;

                    case 'idprog':
                        $progclients = $progclientRepository->findByIdProg($value);
                        break;
                    case 'iduser':
                        $progclients = $progclientRepository->findByIdUser($value);
                        break;


                }
            }


            if ( $progclients){
                $back = "success";
            }else{
                $back = "failure";
            }

            $progclientsPaginator = $paginator->paginate(
                $progclients, // Requête contenant les données à paginer (ici nos articles)
                $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
                5 // Nombre de résultats par page
            );

            return $this->render('progclient/index.html.twig', [
                'progclients' => $progclientsPaginator,
                'back' => $back,
            ]);
        }

        $progclients = $progclientRepository->findAll();
        $progclientsPaginator = $paginator->paginate(
            $progclients, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            5 // Nombre de résultats par page
        );

        return $this->render('progclient/index.html.twig', [
            'progclients' => $progclientsPaginator,
            'back' => $back,
        ]);
    }

    /**
     * @Route("/new", name="app_progclient_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $progclient = new Progclient();
        $lprogs = $this->getDoctrine()->getRepository(Programmeentraineur::class)->findAll();
        $x = 0;
        foreach ($lprogs as $i){
            $idprog[$lprogs[$x]->getId()] = implode((array) $lprogs[$x]->getId());
            $x++;

        }
        $lusers = $this->getDoctrine()->getRepository(Client::class)->findAll();
        $k = 0;
        $iduser= null ;
        foreach ($lusers as $i){

            $iduser[ $lusers[$k]->getPrenom()] = implode((array) $lusers[$k]->getId());
            $k++;}


        $form = $this->createForm(ProgclientType::class, $progclient);
        $form->add('idprog',ChoiceType::class,[
        'choices' => $idprog]);
        $form->add('iduser',ChoiceType::class,[
            'choices' => $iduser]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($progclient);
            $entityManager->flush();

            return $this->redirectToRoute('app_progclient_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('progclient/new.html.twig', [
            'progclient' => $progclient,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_progclient_show", methods={"GET"})
     */
    public function show(Progclient $progclient): Response
    {
        return $this->render('progclient/show.html.twig', [
            'progclient' => $progclient,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_progclient_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Progclient $progclient, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProgclientType::class, $progclient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_progclient_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('progclient/edit.html.twig', [
            'progclient' => $progclient,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_progclient_delete", methods={"POST"})
     */
    public function delete(Request $request, Progclient $progclient, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$progclient->getId(), $request->request->get('_token'))) {
            $entityManager->remove($progclient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_progclient_index', [], Response::HTTP_SEE_OTHER);
    }
}
