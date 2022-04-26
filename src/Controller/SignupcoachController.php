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
            $pwd = $form->get('passwd')->getData();
            $confirmpwd = $form->get('confirmpwd')->getData();

            if ($pwd != $confirmpwd) {
                $this->get('session')->getFlashBag()->add(
                    'alert',
                    'Password mismatch ! '
                );
            } else if ($image == null) {
                $this->get('session')->getFlashBag()->add(
                    'alert2',
                    'Image must be added  ! '
                );
            } else {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

                $fichier = $originalFilename . md5(uniqid()) . '.' . $image->guessExtension();
                $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $destination,
                    $fichier
                );
                $entraineur->setImg($fichier);
                $entraineur->setPasswd(md5($pwd));
                $em = $this->getDoctrine()->getManager();
                $em->persist($entraineur);
                //execute query DB
                $em->flush();
                return $this->redirectToRoute('app_login');
            }
        }
        return $this->render('signupcoach/registre.html.twig', ['formE' => $form->createView()]);

    }

}
