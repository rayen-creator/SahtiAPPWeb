<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SignupclientController extends AbstractController
{
    /**
     * @Route("/signupclient", name="app_signupclient")
     */
    public function index(): Response
    {
        return $this->render('signupclient/index.html.twig', [
            'controller_name' => 'SignupclientController',
        ]);
    }

    /**
     * @return Response
     * @Route("/registreclient",name ="registreclient")
     */
    public function registre(Request $request)
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
//        $form->add('Registre ',submitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get('img')->getData();
            $client->setImg($image);
            $em = $this->getDoctrine()->getManager();
            $em->persist($client);
            //execute query DB
            $em->flush();
            return $this->redirectToRoute('app_signupclient');
        }
            return $this->render('signupclient/registre.html.twig', ['formC' => $form->createView()]);

    }

}
