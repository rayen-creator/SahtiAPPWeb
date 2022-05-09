<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Entraineur;
use App\Entity\Nutritioniste;
use App\Repository\ClientRepository;
use App\Repository\CoachRepository;
use App\Repository\NutritionisteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModifyuserController extends AbstractController
{

    /**
     * @Route("/profile", name="app_profile")
     */
    public function index(ClientRepository $usrRep): Response
    {
        $user=$this->getUser()->getUsername();
        $currentuser=$usrRep->findOneBy(array('email'=>$user));
        return $this->render('modifyuser/indexclient.html.twig', [
            'client' => $currentuser,
        ]);
//        return $this->redirectToRoute('modifycoach');
    }

    /**
     * @Route("/profileE", name="app_profileE")
     */
    public function indexE(CoachRepository $usrRep): Response
    {
        $user=$this->getUser()->getUsername();
        $currentuser=$usrRep->findOneBy(array('email'=>$user));
        return $this->render('modifyuser/indexcoach.html.twig', [
            'coach' => $currentuser,
        ]);
//        return $this->redirectToRoute('modifycoach');
    }

    /**
     * @Route("/profileN", name="app_profileN")
     */
    public function indexN(NutritionisteRepository $usrRep): Response
    {
        $user=$this->getUser()->getUsername();
        $currentuser=$usrRep->findOneBy(array('email'=>$user));
        return $this->render('modifyuser/indexnutri.html.twig', [
            'nutri' => $currentuser,
        ]);
//        return $this->redirectToRoute('modifycoach');
    }


    /**
     * @return Response
     * @Route("/modifyclient/{id}", name="modifyclient")
     */
    public function modifyclient($id,Request $request , ClientRepository $em  )
    {
        $form = $this->createFormBuilder()
            ->add('nom', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'LastName')))
            ->add('prenom', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'FirstName')))
            ->add('passwd', PasswordType::class, array(
                'attr' => array(
                    'placeholder' => 'Password')))
            ->add('confirmpasswd', PasswordType::class, array(
                'attr' => array(
                    'placeholder' => 'Confirm Password')))
            ->add('img', FileType::class)

            ->add('Save', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

            $LastName = $form->get('nom')->getData();
            $FirstName = $form->get('prenom')->getData();
            $pwd = $form->get('passwd')->getData();
            $confirmpwd = $form->get('confirmpasswd')->getData();
            $image = $form->get('img')->getData();

        $foundC= $this -> getDoctrine() -> getRepository(Client::class)->find($id);
        if ($form->isSubmitted() && $form->isValid()) {
         if  ( ( $LastName != null )|| ($FirstName != null) || ($confirmpwd != null) || ($image != null) ) {

            if ($LastName != null) {
                $c = $em->updatelastname($foundC, $LastName);

                $this->get('session')->getFlashBag()->add(
                    'alert1',
                    'LastName updated successfully ! '
                );

            }
            if ($FirstName != null) {
                $c = $em->updatefirstname($foundC, $FirstName);

                $this->get('session')->getFlashBag()->add(
                    'alert2',
                    'FirstName updated successfully ! '
                );
            }
            if (($pwd != null) && ($confirmpwd != null) ) {
                    if ($pwd==$confirmpwd){
                        $c = $em->updatepwd($foundC, $pwd);
                        $this->get('session')->getFlashBag()->add(
                            'alert3',
                            'Password updated successfully ! '
                        );
                    }else{
                        $this->get('session')->getFlashBag()->add(
                            'alert4',
                            'Password mismatch ! '
                        );
                    }
            }
             if($image != null){

                 $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

                 $fichier = $originalFilename.md5(uniqid()).'.'.$image->guessExtension();
                 $destination = $this->getParameter('kernel.project_dir').'/public/uploads';

//                 $oldimg=$em->searchimg($foundC);

                 //delete the file from uploads
//                 unlink($destination.'/'.$oldimg);

                 // On copie le fichier dans le dossier uploads
                 $image->move(
                     $destination ,
                     $fichier
                 );

                 $c = $em-> updateimg($foundC,$fichier);
                 $this->get('session')->getFlashBag()->add(
                     'alert6',
                     'Password updated successfully ! '
                 );
             }
         }else{
             $this->get('session')->getFlashBag()->add(
                 'alert5',
                 'Nothing to update ! '
             );
         }
        }
        return $this->render('modifyuser/modifyclient.html.twig', ['f' => $form->createView()]);
    }

    /**
     * @return Response
     * @Route("/modifycoach/{id}", name="modifycoach")
     */
    public function modifycoach($id,Request $request , CoachRepository $em)
    {
        $form = $this->createFormBuilder()
            ->add('nom', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'LastName')))
            ->add('prenom', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'FirstName')))
            ->add('passwd', PasswordType::class, array(
                'attr' => array(
                    'placeholder' => 'Password')))
            ->add('confirmpasswd', PasswordType::class, array(
                'attr' => array(
                    'placeholder' => 'Confirm Password')))
            ->add('bio', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Bio')))
            ->add('img', FileType::class)
            ->add('Save', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        $LastName = $form->get('nom')->getData();
        $FirstName = $form->get('prenom')->getData();
        $pwd = $form->get('passwd')->getData();
        $confirmpwd = $form->get('confirmpasswd')->getData();
        $bio = $form->get('bio')->getData();
        $image = $form->get('img')->getData();

        $foundE= $this -> getDoctrine() -> getRepository(Entraineur::class)->find($id);
        if ($form->isSubmitted() && $form->isValid()) {
            if  ( ( $LastName != null )|| ($FirstName != null) || ($confirmpwd != null) || ($bio != null) || ($image != null) ) {

                if ($LastName != null) {
                    $c = $em->updatelastname($foundE, $LastName);

                    $this->get('session')->getFlashBag()->add(
                        'alert1',
                        'LastName updated successfully ! '
                    );
                    return $this->redirectToRoute('modifycoach');

                }
                if ($FirstName != null) {
                    $c = $em->updatefirstname($foundE, $FirstName);

                    $this->get('session')->getFlashBag()->add(
                        'alert2',
                        'FirstName updated successfully ! '
                    );
                    return $this->redirectToRoute('modifycoach');

                }
                if (($pwd != null) && ($confirmpwd != null) ) {
                    if ($pwd==$confirmpwd){
                        $c = $em->updatepwd($foundE, $pwd);
                        $this->get('session')->getFlashBag()->add(
                            'alert3',
                            'Password updated successfully ! '
                        );
                    }else{
                        $this->get('session')->getFlashBag()->add(
                            'alert4',
                            'Password mismatch ! '
                        );
                    }
                    return $this->redirectToRoute('modifycoach');

                }
                if ($bio != null) {
                    $c = $em->updatebio($foundE, $bio);

                    $this->get('session')->getFlashBag()->add(
                        'alert6',
                        'Bio updated successfully ! '
                    );
                    return $this->redirectToRoute('modifycoach');

                }
                if($image != null){

                    $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

                    $fichier = $originalFilename.md5(uniqid()).'.'.$image->guessExtension();
                    $destination = $this->getParameter('kernel.project_dir').'/public/uploads';

//                 $oldimg=$em->searchimg($foundC);

                    //delete the file from uploads
//                 unlink($destination.'/'.$oldimg);

                    // On copie le fichier dans le dossier uploads
                    $image->move(
                        $destination ,
                        $fichier
                    );

                    $c = $em-> updateimg($foundE,$fichier);
                    $this->get('session')->getFlashBag()->add(
                        'alert7',
                        'Profile picture updated successfully ! '
                    );
                }

            }else{
                $this->get('session')->getFlashBag()->add(
                    'alert5',
                    'Nothing to update ! '
                );
            }
        }
        return $this->render('modifyuser/modifycoach.html.twig', ['f' => $form->createView()]);
    }

    /**
     * @return Response
     * @Route("/modifynutri/{id}", name="modifynutri")
     */
    public function modifynutri($id,Request $request , NutritionisteRepository $em)
    {
        $formN = $this->createFormBuilder()
            ->add('nom', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'LastName')))
            ->add('prenom', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'FirstName')))
            ->add('passwd', PasswordType::class, array(
                'attr' => array(
                    'placeholder' => 'Password')))
            ->add('confirmpasswd', PasswordType::class, array(
                'attr' => array(
                    'placeholder' => 'Confirm Password')))
            ->add('bio', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Bio')))
            ->add('img', FileType::class)
            ->add('Save', SubmitType::class)
            ->getForm();
        $formN->handleRequest($request);

        $LastName = $formN->get('nom')->getData();
        $FirstName = $formN->get('prenom')->getData();
        $pwd = $formN->get('passwd')->getData();
        $confirmpwd = $formN->get('confirmpasswd')->getData();
        $bio = $formN->get('bio')->getData();
        $image = $formN->get('img')->getData();

        $foundN= $this -> getDoctrine() -> getRepository(Nutritioniste::class)->find($id);
        if ($formN->isSubmitted() && $formN->isValid()) {
            if  ( ( $LastName != null )|| ($FirstName != null) || ($confirmpwd != null) || ($bio  != null) || ($image  != null) ) {

                if ($LastName != null) {
                    $c = $em->updatelastname($foundN, $LastName);

                    $this->get('session')->getFlashBag()->add(
                        'alert1',
                        'LastName updated successfully ! '
                    );

                }
                if ($FirstName != null) {
                    $c = $em->updatefirstname($foundN, $FirstName);

                    $this->get('session')->getFlashBag()->add(
                        'alert2',
                        'FirstName updated successfully ! '
                    );

                }
                if (($pwd != null) && ($confirmpwd != null) ) {
                    if ($pwd==$confirmpwd){
                        $c = $em->updatepwd($foundN, $pwd);
                        $this->get('session')->getFlashBag()->add(
                            'alert3',
                            'Password updated successfully ! '
                        );
                    }else{
                        $this->get('session')->getFlashBag()->add(
                            'alert4',
                            'Password mismatch ! '
                        );
                    }

                }
                if ($bio != null) {
                    $c = $em->updatebio($foundN, $bio);

                    $this->get('session')->getFlashBag()->add(
                        'alert6',
                        'Bio updated successfully ! '
                    );

                }
                if($image != null){

                    $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

                    $fichier = $originalFilename.md5(uniqid()).'.'.$image->guessExtension();
                    $destination = $this->getParameter('kernel.project_dir').'/public/uploads';

//                 $oldimg=$em->searchimg($foundC);

                    //delete the file from uploads
//                 unlink($destination.'/'.$oldimg);

                    // On copie le fichier dans le dossier uploads
                    $image->move(
                        $destination ,
                        $fichier
                    );

                    $c = $em-> updateimg($foundN,$fichier);
                    $this->get('session')->getFlashBag()->add(
                        'alert7',
                        'profile picture updated successfully ! '
                    );
                }
            }else{
                $this->get('session')->getFlashBag()->add(
                    'alert5',
                    'Nothing to update ! '
                );
            }
        }
        return $this->render('modifyuser/modifynutri.html.twig', ['f' => $formN->createView()]);
    }
}
