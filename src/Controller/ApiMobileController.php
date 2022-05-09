<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Client;
use App\Entity\Entraineur;
use App\Entity\Nutritioniste;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use App\Repository\CoachRepository;
use App\Repository\NutritionisteRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;



class ApiMobileController extends AbstractController
{

    //********BEGIN*************ADMIN PANNEL*********************************

    //-------------------------------------GET---------------------------------


    /**
     * @param Request $request
     * @param NormalizerInterface $Normalizer
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @Route("/admin/listclient",name ="adminlistclient")
     */
    public function listclient(Request $request, NormalizerInterface $Normalizer)
    {
        $listec = $this->getDoctrine()->getRepository(Client::class)->findAll();

        $jsonContent = $Normalizer->normalize($listec, 'json', ['groups' => 'post:read']);

        return new Response(json_encode($jsonContent));
    }

    /**
     * @param Request $request
     * @param NormalizerInterface $Normalizer
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @Route("/admin/listcoach",name ="adminlistcoach")
     */
    public function listCoach(Request $request, NormalizerInterface $Normalizer)
    {
        $listec = $this->getDoctrine()->getRepository(Entraineur::class)->findAll();

        $jsonContent = $Normalizer->normalize($listec, 'json', ['groups' => 'post:read']);

        return new Response(json_encode($jsonContent));
    }

    /**
     * @param Request $request
     * @param NormalizerInterface $Normalizer
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @Route("/admin/listnutri",name ="adminlistnutri")
     */
    public function listNutri(Request $request, NormalizerInterface $Normalizer)
    {
        $listec = $this->getDoctrine()->getRepository(Nutritioniste::class)->findAll();

        $jsonContent = $Normalizer->normalize($listec, 'json', ['groups' => 'post:read']);

        return new Response(json_encode($jsonContent));
    }
    //-------------------------------------GET---------------------------------

    //-------------------------------------Delete---------------------------------

    /**
     * @Route("/admin/deleteclientjson/{id}",name ="deleteclientjson")
     * @param Request $request
     * @param $id
     * @param NormalizerInterface $Normalizer
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function deleteC($id, NormalizerInterface $Normalizer)
    {
        $listec = $this->getDoctrine()->getRepository(Client::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($listec);
        $em->flush();

//        $jsonContent=$Normalizer->normalize($listec,'json',['groups'=>'user']);

        //testing delete by reading the entity to delete
        $jsonContent = $Normalizer->normalize($listec, 'json', ['groups' => 'post:read']);


        return new Response("Delete was success" . json_encode($jsonContent));
    }

    /**
     * @Route("/admin/deletecoachjson/{id}",name ="deletecoachjson")
     * @param Request $request
     * @param $id
     * @param NormalizerInterface $Normalizer
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function deleteE($id, NormalizerInterface $Normalizer)
    {
        $listec = $this->getDoctrine()->getRepository(Entraineur::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($listec);
        $em->flush();

//        $jsonContent=$Normalizer->normalize($listec,'json',['groups'=>'user']);

        //testing delete by reading the entity to delete
        $jsonContent = $Normalizer->normalize($listec, 'json', ['groups' => 'post:read']);


        return new Response("Delete was success" . json_encode($jsonContent));
    }

    /**
     * @Route("/admin/deletenutrijson/{id}",name ="deletenutrijson")
     * @param Request $request
     * @param $id
     * @param NormalizerInterface $Normalizer
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function deleteN($id, NormalizerInterface $Normalizer)
    {
        $listec = $this->getDoctrine()->getRepository(Nutritioniste::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($listec);
        $em->flush();

//        $jsonContent=$Normalizer->normalize($listec,'json',['groups'=>'user']);

        //testing delete by reading the entity to delete
        $jsonContent = $Normalizer->normalize($listec, 'json', ['groups' => 'post:read']);


        return new Response("Delete was success" . json_encode($jsonContent));
    }

    //-------------------------------------Delete---------------------------------


    //**********END******************ADMIN PANNEL*********************************

    //------------------------Signup-------------POST---------------------------------

    /**
     * @Route("/newclient",name ="newclient")
     * @param Request $request
     * @param NormalizerInterface $Normalizer
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function registreclient(Request $request, NormalizerInterface $Normalizer)
    {
        $client = new Client();

//        $image=$client->getImg();
//        $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
//
//        $fichier = $originalFilename.md5(uniqid()).'.'.$image->guessExtension();
//        $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
//
//                // On copie le fichier dans le dossier uploads
//        $image->move(
//            $destination ,
//            $fichier
//                );
//        $client->setImg($fichier);
        ///*****
        $em = $this->getDoctrine()->getManager();
        $client->setNom($request->get('nom'));
        $client->setPrenom($request->get('prenom'));
        $client->setEmail($request->get('email'));
        $client->setPasswd($request->get('passwd'));
        $client->setAdresse($request->get('adresse'));
        $client->setDatenaiss($request->get('datenaiss'));

        $em->persist($client);
        //execute query DB
        $em->flush();
        $jsonContent = $Normalizer->normalize($client, 'json', ['groups' => 'post:read']);

        return new Response("client was a success" . json_encode($jsonContent));
    }

    /**
     * @Route("/newcoach",name ="newcoach")
     * @param Request $request
     * @param NormalizerInterface $Normalizer
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function registrecoach(Request $request, NormalizerInterface $Normalizer)
    {
        $coach = new Entraineur();

//        $image=$client->getImg();
//        $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
//
//        $fichier = $originalFilename.md5(uniqid()).'.'.$image->guessExtension();
//        $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
//
//                // On copie le fichier dans le dossier uploads
//        $image->move(
//            $destination ,
//            $fichier
//                );
//        $client->setImg($fichier);
        ///*****
        $em = $this->getDoctrine()->getManager();
        $coach->setNom($request->get('nom'));
        $coach->setPrenom($request->get('prenom'));
        $coach->setEmail($request->get('email'));
        $coach->setPasswd($request->get('passwd'));
        $coach->setAdresse($request->get('adresse'));
        $coach->setBio($request->get('bio'));
        $coach->setCertification($request->get('certification'));

        $em->persist($coach);
        //execute query DB
        $em->flush();
        $jsonContent = $Normalizer->normalize($coach, 'json', ['groups' => 'post:read']);

        return new Response("coach was a success" . json_encode($jsonContent));
    }

    /**
     * @Route("/newnutri",name ="newnutri")
     * @param Request $request
     * @param NormalizerInterface $Normalizer
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function registrenutri(Request $request, NormalizerInterface $Normalizer)
    {
        $nutri = new Nutritioniste();

//        $image=$client->getImg();
//        $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
//
//        $fichier = $originalFilename.md5(uniqid()).'.'.$image->guessExtension();
//        $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
//
//                // On copie le fichier dans le dossier uploads
//        $image->move(
//            $destination ,
//            $fichier
//                );
//        $client->setImg($fichier);
        ///*****
        $em = $this->getDoctrine()->getManager();
        $nutri->setNom($request->get('nom'));
        $nutri->setPrenom($request->get('prenom'));
        $nutri->setEmail($request->get('email'));
        $nutri->setPasswd($request->get('passwd'));
        $nutri->setAdresse($request->get('adresse'));
        $nutri->setBio($request->get('bio'));
        $nutri->setCertification($request->get('certification'));
        $em->persist($nutri);
        //execute query DB
        $em->flush();
        $jsonContent = $Normalizer->normalize($nutri, 'json', ['groups' => 'post:read']);

        return new Response("nutri was a success" . json_encode($jsonContent));
    }


    //-------------------------------------POST---------------------------------

    //*************************USER****************************


    /**
     * @Route("/forgetpwd", name="forgetpwd")
     * @param MailerInterface $mailer
     * @param Request $request
     * @param ClientRepository $cr
     * @param CoachRepository $er
     * @param NutritionisteRepository $nr
     * @param NormalizerInterface $Normalizer
     * @return Response
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function forgetpwd(MailerInterface $mailer, Request $request, ClientRepository $cr,
                              CoachRepository $er, NutritionisteRepository $nr, NormalizerInterface $Normalizer)
    {

        $mail = $request->get('email');
        if ($cr->searchemail($mail)) { //client
            $newpwd = $this->randomPassword();
            $c = $cr->updateresetpwd($mail, $newpwd);

            $bodymail = 'Hello  ' . $mail . ' 
                This is your new password generated by SAHTI Application :' .
                $newpwd . ' 
               The above is a temporary password.
               We highly recommend that you update the password after you log in successfully.
               Thanks.';
            $email = (new Email())
                ->from('noreplysahti@gmail.com')
                ->to($mail)
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                ->priority(Email::PRIORITY_HIGH)
                ->subject('Password reset request ')
                ->text($bodymail);
            $mailer->send($email);

        } else if ($nr->searchemail($mail)) { //nutri
            $newpwdN = $this->randomPassword();
            $c = $nr->updateresetpwd($mail, $newpwdN);

            $bodymail = 'Hello  ' . $mail . ' 
                This is your new password generated by SAHTI Application :' .
                $newpwdN . ' 
               The above is a temporary password.
               We highly recommend that you update the password after you log in successfully.
               Thanks.';
            $email = (new Email())
                ->from('noreplysahti@gmail.com')
                ->to($mail)
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                ->priority(Email::PRIORITY_HIGH)
                ->subject('Password reset request ')
                ->text($bodymail);
            $mailer->send($email);

        } else if ($er->searchemail($mail)) { //coach
            $newpwdE = $this->randomPassword();
            $c = $er->updateresetpwd($mail, $newpwdE);

            $bodymail = 'Hello  ' . $mail . ' 
                This is your new password generated by SAHTI Application :' .
                $newpwdE . ' 
               The above is a temporary password.
               We highly recommend that you update the password after you log in successfully.
               Thanks.';
            $email = (new Email())
                ->from('noreplysahti@gmail.com')
                ->to($mail)
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                ->priority(Email::PRIORITY_HIGH)
                ->subject('Password reset request ')
                ->text($bodymail);
            $mailer->send($email);

        }
        $jsonContent = $Normalizer->normalize($mail, 'json', ['groups' => 'post:read']);

        return new Response("mail was a success" . json_encode($jsonContent));
    }

    function randomPassword()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
    //*************************USER****************************

    //*************************


    /**
     * @param NormalizerInterface $Normalizer
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @Route("/availablecoach",name ="availablecoach")
     */
    public function availablecoach(NormalizerInterface $Normalizer)
    {
        $listec = $this->getDoctrine()->getRepository(Entraineur::class)->findavailablecoach();

        $jsonContent = $Normalizer->normalize($listec, 'json', ['groups' => 'post:read']);

        return new Response(json_encode($jsonContent));
    }

    /**
     * @param NormalizerInterface $Normalizer
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @Route("/availablenutri",name ="availablenutri")
     */
    public function availablenutri(NormalizerInterface $Normalizer)
    {
        $listec = $this->getDoctrine()->getRepository(Nutritioniste::class)->findavailablenutri();

        $jsonContent = $Normalizer->normalize($listec, 'json', ['groups' => 'post:read']);

        return new Response(json_encode($jsonContent));
    }


    //*******************************LOGIN*************************************************
    /**
     * @Route("/jsonloginadmin", name="jsonloginadmin")
     * @param Request $request
     * @param NormalizerInterface $Normalizer
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function jsonloginadmin(Request $request , NormalizerInterface $Normalizer)
    {
//        $repository = $this -> getDoctrine() -> getRepository(Admin::class);
        $email = $request->get('email');
        $pwd = $request->get('passwd');
//        $email="admin@sahti.com";
//        $pwd="pidev2122";
        $result = $this->getDoctrine()->getRepository(Admin::class)->findOneBy(
            array('email' => $email, 'passwd' => md5($pwd))
        );
         if ( $result){
//             $admin=$repository->adminexist($email,$pwd);
//             $jsonContent = $Normalizer->normalize(true, 'json', ['groups' => 'post:read']);
             $jsonContent = $Normalizer->normalize(true, 'json', ['groups' => 'post:read']);
         }else{
             $jsonContent = $Normalizer->normalize(false, 'json', ['groups' => 'post:read']);
         }
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/jsonloginclient", name="jsonloginclient")
     * @param Request $request
     * @param NormalizerInterface $Normalizer
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function jsonloginclient(Request $request , NormalizerInterface $Normalizer)
    {
        $email = $request->get('email');
        $pwd = $request->get('passwd');

        $result = $this->getDoctrine()->getRepository(Client::class)->findOneBy(
            array('email' => $email, 'passwd' => md5($pwd))
        );
        if ( $result){
            $jsonContent = $Normalizer->normalize(true, 'json', ['groups' => 'post:read']);
        }else{
            $jsonContent = $Normalizer->normalize(false, 'json', ['groups' => 'post:read']);
        }
        return new Response(json_encode($jsonContent));
    }

    //*******************************LOGIN*************************************************

    /**
     * @Route("/loggedinblocked", name="loggedinblocked")
     * @param Request $request
     * @param NormalizerInterface $Normalizer
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */

    public function loggedinblocked(Request $request , NormalizerInterface $Normalizer)
    {
        $repository = $this -> getDoctrine() -> getRepository(Client::class);
        $email = $request->get('email');
        $pwd = $request->get('passwd');

        $clientstate=$repository->clientisblocked($email,$pwd);
       if ($clientstate){
       $jsonContent = $Normalizer->normalize($clientstate, 'json', ['groups' => 'post:read']);

    }else{
           $jsonContent = $Normalizer->normalize($clientstate, 'json', ['groups' => 'post:read']);

    }


        return new Response(json_encode($jsonContent));
    }

}

