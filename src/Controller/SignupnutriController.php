<?php

namespace App\Controller;

use App\Entity\Nutritioniste;
use App\Form\NutritionisteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SignupnutriController extends AbstractController
{
    /**
     * @Route("/signupnutri", name="app_signupnutri")
     */
    public function index(): Response
    {
        return $this->render('signupnutri/index.html.twig', [
            'controller_name' => 'SignupnutriController',
        ]);
    }

    /**
     * @return Response
     * @Route("/registrenutri",name ="registrenutri")
     */
    public function registre(Request $request)
    {
        $nutritioniste = new Nutritioniste();
        $form = $this->createForm(NutritionisteType::class, $nutritioniste);
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
            $nutritioniste->setImg($fichier);

            $em = $this->getDoctrine()->getManager();
            $em->persist($nutritioniste);
            //execute query DB
            $em->flush();
            return $this->redirectToRoute('app_login');
        }
        return $this->render('signupnutri/registre.html.twig', ['formN' => $form->createView()]);

    }

}
