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
     * @return Response
     * @Route("/registreclient",name ="registreclient")
     */
    public function registre(Request $request)
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $date = $form->get('date')->getData();
            $image = $form->get('imgFile')->getData();
            $pwd=$form->get('passwd')->getData();
            $confirmpwd=$form->get('confirmpwd')->getData();

            if  ($pwd != $confirmpwd) {
                $this->get('session')->getFlashBag()->add(
                    'alert',
                    'Password mismatch ! '
                );
            }else if ($date == null ){
                $this->get('session')->getFlashBag()->add(
                    'alert2',
                    'Date must be added  ! '
                );
            }else if ($image == null ){
                $this->get('session')->getFlashBag()->add(
                    'alert3',
                    'Image must be added  ! '
                );
            }else {
                $stringdate=$date->format('Y-m-d');
                $client->setDatenaiss($stringdate);
                //******
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

                $fichier = $originalFilename.md5(uniqid()).'.'.$image->guessExtension();
                $destination = $this->getParameter('kernel.project_dir').'/public/uploads';

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $destination ,
                    $fichier
                );
                $client->setImg($fichier);
                ///*****
                $client->setPasswd(md5($pwd));
                $em = $this->getDoctrine()->getManager();
                $em->persist($client);
                //execute query DB
                $em->flush();
                return $this->redirectToRoute('app_login');
            }

        }
            return $this->render('signupclient/registre.html.twig', ['formC' => $form->createView()]);
    }

}
