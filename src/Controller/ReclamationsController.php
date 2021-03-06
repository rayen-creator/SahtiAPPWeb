<?php

namespace App\Controller;

use App\Entity\Reclamations;
use App\Entity\Client;

use App\Form\ReclamationsType;
use App\Repository\ReclamationRepository;

use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
//pagination
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @Route("/reclamations")
 */
class ReclamationsController extends AbstractController
{
    /**
     * @Route("/", name="app_reclamations_index", methods={"GET"})
     */
    public function index(PaginatorInterface $paginator, Request $request, ClientRepository $usrRep,EntityManagerInterface $entityManager, ReclamationRepository $reclamationRepository): Response
    {
        $client = new Client();
        $reclamations = new Reclamations();
        $user=$this->getUser()->getUsername();
        $role=$this->getUser()->getRoles();
//        var_dump($role);
        if ( ($user != null) && (in_array("ROLE_USER", $role) ) )
        {
            $currentuser=$usrRep->findOneBy(array('email'=>$user));
            $id= $currentuser->getId();
            $reclamations = $entityManager->getRepository(Reclamations::class)->findBy(array("client"=>$id));

        }else if (in_array("ROLE_ADMIN", $role)) {
        $reclamations = $entityManager->getRepository(Reclamations::class)->findAll();

        }

            $haute = array("SYSTEME", "Systeme", "systeme", "CRITIQUE", "Erreur","ERREUR", "SPAM", "ARNAQUE", "ERREUR", "BUG", "CRASH", "COMMANDE", "COMMANDES", "ANNULER", "PAIMENT", "LIVRAISON");
            $moyenne = array("COACH", "NUTRITIONNISTE", "coach", "Coach","ACHAT", "EVOLUTION");
            //contient les message des réclamations
            $reclamationTab = array();
            //contient le clean 1 des message
            $reclamationBr = array();
            //tableau les mots de message
            $numMsgTab = array();
            //trier tableau
            $resultTab = array();
            //compteur
            $i = 0;
            //initialisation du priorité à low
            $priorite = 0;
            //Entité réclamation


            foreach ($reclamations as $item ){
               $reclamationTab[$i]=$item->getNumreclamation()."\n".($item->getMessage());
               $i++;
            }            
            for ($j = 0; $j < count($reclamationTab); $j++){
                $reclamationBr[$j] = str_replace("\n"," ",$reclamationTab[$j]);
                $numMsgTab[] = (explode(" ",$reclamationBr[$j]));
            }
            for($h=0; $h < count($numMsgTab);$h++){
                $numMsgTab[$h] = str_replace("\r","",$numMsgTab[$h]);
            }
            for ($l = 0; $l<=count($numMsgTab)-1; $l++){                 
                for ($k = 0; $k<count($haute); $k++){
                    if (in_array($haute[$k], $numMsgTab[$l])){
                        $priorite = 3;
                        if (count($resultTab)>0){
                            $reclamationFinal = $reclamationRepository->findBy(array('numreclamation' => $numMsgTab[$l][0]), array('datereclamation'=> 'ASC'));
                            $resultTab[count($resultTab)] = $reclamationFinal;
                        }else{                        
                            $reclamationFinal = $reclamationRepository->findBy(array('numreclamation' => $numMsgTab[$l]), array('datereclamation'=> 'ASC'));
                            $resultTab[0] = $reclamationFinal;                            
                        }       
                    }
                }                                                
            }                        
            for ($l = 0; $l<=count($numMsgTab)-1; $l++){ 
                for ($k = 0; $k<count($moyenne); $k++){
                    if (in_array($moyenne[$k], $numMsgTab[$l])){
                        $priorite = 2;
                        if (count($resultTab)>0){
                                $reclamationFinal = $reclamationRepository->findBy(array('numreclamation' => $numMsgTab[$l][0]), array('datereclamation'=> 'ASC'));
                                $resultTab[count($resultTab)] = $reclamationFinal;
                            }else{                        
                                $reclamationFinal = $reclamationRepository->findBy(array('numreclamation' => $numMsgTab[$l]), array('datereclamation'=> 'ASC'));
                                $resultTab[0] = $reclamationFinal;
                            }        
                    }                                    
                }                            
            }
            $test = array();
            $k=0;
            for ($i=0; $i<count($resultTab); $i++){
                for($j=0; $j<1; $j++){
                    $test[$k]=$resultTab[$i][$j];
                    
                    $k++;                    
                }
              
            }
            $final = $reclamations;
            if (count($final) > 2 && count($resultTab) > 0){
                $j=0;
                $countTab = count($resultTab);
                for($j=0; $j<$countTab-1;$j++){
                for ($i = 0; $i<count($final)-1; $i++){
                    
                    //if (count($test)-1>=$j){
                        if ($final[$i]->getNumreclamation() !==  $test[$j]->getNumReclamation()){
                            $test[count($test)] = $final[$i];
                            
                         }
                    }
                }

            }else
                $test=$reclamations;
            
            //pagination
            $test = $paginator->paginate($test,$request->query->getInt('page',1) ,6);
            //dd($reclamations);
        return $this->render('reclamations/index.html.twig', [
            'reclamations' => $test
        ]);
    }
    /**
     * @Route("/searchReclamation", name="searchReclamation")
     */

    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $reclamations =  $em->getRepository(Reclamations::class)->findEntitiesByString($requestString);
        if(!$reclamations) {
            $result['reclamations']['error'] = "Réclamation introuvable :( ";
        } else {
            $result['reclamations'] = $this->getRealEntities($reclamations);
        }
        return new Response(json_encode($result));
        
    }
    public function getRealEntities($reclamations){
        foreach ($reclamations as $reclamations){
            $realEntities[$reclamations->getId()] = [$reclamations->getNumReclamation(), $reclamations-> getTitre(), $reclamations-> getMessage(), $reclamations-> getType(), $reclamations->getCloturer(), $reclamations->getDatereclamation()];

        }
        return $realEntities;
    }
    /**
     * @Route("/new", name="app_reclamations_new", methods={"GET", "POST"})
     */
    public function new(Request $request,ClientRepository $usrRep, ReclamationRepository $reclamationRepository, EntityManagerInterface $entityManager): Response
    {
        $client = new Client();
        $user=$this->getUser()->getUsername();
        $currentuser=$usrRep->findOneBy(array('email'=>$user));
        $id= $currentuser->getId();
        $i=0;
        $numReclamaiton = "22-04".$i++;
        $rec = $reclamationRepository->findOneBy(array(),array("id"=>"DESC"), 1,1);
        if($rec != null){
            $n = substr($rec->getNumreclamation(),-1,1);
            $n = intval($n);
            $n+=1;
            $numReclamaiton[-1]=$n;
        }
        //dd($numReclamaiton);
        $reclamation = new Reclamations();
        $form = $this->createForm(ReclamationsType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamation->getUploadFile();
            $reclamation->setNumreclamation($numReclamaiton);
            $reclamation->setClient($id);
//            $reclamation->setDatereclamation(strval(new Date('now', "yyyy-MM-dd")));
            $entityManager->persist($reclamation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamations/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_reclamations_show", methods={"GET"})
     */
    public function show(Reclamations $reclamation): Response
    {
        return $this->render('reclamations/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_reclamations_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Reclamations $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReclamationsType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamation->setEtat_reclamation(1);
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamations/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}/colurer", name="app_reclamations_cloture", methods={"GET", "POST"})
     */
    public function cloturerReclamation(Request $request, Reclamations $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReclamationsType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamation->setCloturer(1);
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamations_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('reclamations/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_reclamations_delete", methods={"POST"})
     */
    public function delete(Request $request, Reclamations $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reclamations_index', [], Response::HTTP_SEE_OTHER);
    }
    
}
