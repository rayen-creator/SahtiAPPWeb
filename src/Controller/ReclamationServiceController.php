<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\ReclamationRepository;
use App\Entity\Reclamations;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

 /**
 * @Route("/reclam")
 */
class ReclamationServiceController extends AbstractController
{
  /**
 * @Route("/liste", name="list", methods={"GET"})
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
     * @Route("/add", name="add", methods={"GET"})
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
    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, ReclamationRepository $reclamationRepository): Response
    {
        $reclamation = $reclamationRepository->find((int)$request->get("id"));

        if (!$reclamation) {
            return new JsonResponse(null, 404);
        }

        return $this->manage($reclamation, $request);
    }
    public function manage($reclamation, $request): JsonResponse
    {   
        $reclamation->setId($request->get('id'));
        $reclamation->setNumreclamation($request->get('numreclamation'));
            $reclamation->setTitre($request->get('titre'));
            $reclamation->setType($request->get('type'));
            $reclamation->setMessage($request->get('message'));
       /* $reclamation->setNumreclamation($request->get('numReclamation'));
        $reclamation->setTitre($request->get('titre'));
        $reclamation->setType($request->get('type'));
        $reclamation->setMessage($request->get('message'));*/

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($reclamation);
        $entityManager->flush();

        return new JsonResponse($reclamation, 200);
    }
     /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, ReclamationRepository $reclamationRepository): JsonResponse
    {
        $reclamation = $reclamationRepository->find((int)$request->get("id"));

        if (!$reclamation) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($reclamation);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }

}
