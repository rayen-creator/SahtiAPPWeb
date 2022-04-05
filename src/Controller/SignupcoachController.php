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

            $image = $form->get('img')->getData();
            $entraineur->setImg($image);
            $em = $this->getDoctrine()->getManager();
            $em->persist($entraineur);
            //execute query DB
            $em->flush();
            return $this->redirectToRoute('app_signupcoach');
        }
        return $this->render('signupcoach/registre.html.twig', ['formE' => $form->createView()]);

    }

}
