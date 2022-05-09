<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ReclamationRepository;
use App\Entity\Reclamations;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ReclamationServiceController extends AbstractController
{
  /**
 * @Route("/reclamations/liste", name="liste", methods={"GET"})
 */
    public function liste(ReclamationRepository $reclamationsRepo)
    {
        // On récupère la liste des reclamations
        $reclamations = $reclamationsRepo->findAll();

        // On spécifie qu'on utilise l'encodeur JSON
        $encoders = [new JsonEncoder()];

        // On instancie le "normaliseur" pour convertir la collection en tableau
        $normalizers = [new ObjectNormalizer()];

        // On instancie le convertisseur
        $serializer = new Serializer($normalizers, $encoders);

        // On convertit en json
        $jsonContent = $serializer->serialize($reclamations, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        // On instancie la réponse
        $response = new Response($jsonContent);

        // On ajoute l'entête HTTP
        $response->headers->set('Content-Type', 'application/json');

        // On envoie la réponse
        return $response;
    }
    /**
     * @Route("/reclamation/ajout", name="ajout", methods={"GET"})
     */
    public function addReclamation(Request $request)
    {
        // On vérifie si la requête est une requête Ajax
        //if($request->isXmlHttpRequest()) {
            // On instancie un nouvel reclamation
            $reclamation = new Reclamations();

            // On décode les données envoyées
            $donnees = json_decode($request->getContent());
           // dd($request->getContent());
            // On hydrate l'objet
            $reclamation->setNumreclamation($request->get('numReclamation'));
            $reclamation->setTitre($request->get('titre'));
            $reclamation->setType($request->get('type'));
            $reclamation->setMessage($request->get('message'));

            //$user = $this->getDoctrine()->getRepository(Users::class)->findOneBy(["id" => 1]);
            //$reclamation->setUsers($user);

            // On sauvegarde en base
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reclamation);
            $entityManager->flush();

            // On retourne la confirmation
            return new Response('ok', 201);
        //}
        //dd($reclamation);
        //return new Response('Failed', 404);
    }
    
}
