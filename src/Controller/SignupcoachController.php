<?php

namespace App\Controller;

use App\Entity\Entraineur;
use App\Form\EntraineurType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SignupcoachController extends AbstractController
{
    /**
     * @Route("/signupcoach", name="app_signupcoach")
     */
    public function index(): Response
    {
        return $this->render('signupcoach/index.html.twig', [
            'controller_name' => 'SignupcoachController',
        ]);
    }

    /**
     * @return Response
     * @Route("/registrecoach",name ="registrecoach")
     */
    public function registre(Request $request)
    {
        $entraineur = new Entraineur();
        $form = $this->createForm(EntraineurType::class, $entraineur);
//        $form->add('Registre ',submitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('imgFile')->getData();
            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

            $fichier = $originalFilename.md5(uniqid()).'.'.$image->guessExtension();
            $destination = $this->getParameter('kernel.project_dir').'/public/uploads';

            // On copie le fichier dans le dossier uploads
            $image->move(
                $destination ,
                $fichier
            );
            $entraineur->setImg($fichier);

            $em = $this->getDoctrine()->getManager();
            $em->persist($entraineur);
            //execute query DB
            $em->flush();
            return $this->redirectToRoute('app_login');
        }
        return $this->render('signupcoach/registre.html.twig', ['formE' => $form->createView()]);

    }

}
