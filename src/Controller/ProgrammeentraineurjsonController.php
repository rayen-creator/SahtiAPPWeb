<?php

namespace App\Controller;

use App\Entity\Programmeentraineur;
use App\Repository\ProgrammeentraineurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ProgrammeentraineurjsonController extends AbstractController
{
    /**
     * @Route("/listprogrammeentraineurjson", name="listprogrammeentraineurjson", methods={"GET"})
     */
    public function liste(ProgrammeentraineurRepository $programmeentraineurRepository)
    {
        // On récupère la liste des reclamations
        $programmeentraineurs = $programmeentraineurRepository->findAll();

        // On spécifie qu'on utilise l'encodeur JSON
        $encoders = [new JsonEncoder()];

        // On instancie le "normaliseur" pour convertir la collection en tableau
        $normalizers = [new ObjectNormalizer()];

        // On instancie le convertisseur
        $serializer = new Serializer($normalizers, $encoders);

        // On convertit en json
        $jsonContent = $serializer->serialize($programmeentraineurs, 'json', [
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
     * @Route("/addprogrammeentraineurjson", name="addprogrammeentraineurjson", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function add(Request $request)
    {
        // On vérifie si la requête est une requête Ajax
        //if($request->isXmlHttpRequest()) {
        // On instancie un nouvel reclamation
        $programmeentraineur = new programmeentraineur();

        // On décode les données envoyées
        $donnees = json_decode($request->getContent());
        // dd($request->getContent());
        // On hydrate l'objet
        $programmeentraineur->setIdexercice($request->get('idexercice'));
        $programmeentraineur->setNompack($request->get('nompack'));
        $programmeentraineur->setType($request->get('type'));

        //$user = $this->getDoctrine()->getRepository(Users::class)->findOneBy(["id" => 1]);
        //$reclamation->setUsers($user);

        // On sauvegarde en base
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($programmeentraineur);
        $entityManager->flush();

        // On retourne la confirmation
        return new Response('ok', 201);
        //}
        //dd($reclamation);
        //return new Response('Failed', 404);
    }

    /**
     * @Route("/editprogrammeentraineurjson", name="editprogrammeentraineurjson", methods={"POST"})
     * @param Request $request
     * @param ProgrammeentraineurRepository $programmeentraineurRepository
     * @return Response
     */
    public function edit(Request $request, ProgrammeentraineurRepository $programmeentraineurRepository): Response
    {
        $programmeentraineur = $programmeentraineurRepository->find((int)$request->get("id"));

        if (!$programmeentraineur) {
            return new JsonResponse(null, 404);
        }

        return $this->manage($programmeentraineur, $request);
    }
    public function manage($programmeentraineur, $request): JsonResponse
    {
        $programmeentraineur->setId($request->get('id'));
        $programmeentraineur->setIdexercice($request->get('idexercice'));
        $programmeentraineur->setNompack($request->get('nompack'));
        $programmeentraineur->setType($request->get('type'));
        /* $reclamation->setNumreclamation($request->get('numReclamation'));
         $reclamation->setTitre($request->get('titre'));
         $reclamation->setType($request->get('type'));
         $reclamation->setMessage($request->get('message'));*/

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($programmeentraineur);
        $entityManager->flush();

        return new JsonResponse($programmeentraineur, 200);
    }

    /**
     * @Route("/deleteprogrammeentraineurjson", name="deleteprogrammeentraineurjson", methods={"POST"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param ProgrammeentraineurRepository $programmeentraineurRepository
     * @return JsonResponse
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, ProgrammeentraineurRepository $programmeentraineurRepository): JsonResponse
    {
        $programmeentraineur = $programmeentraineurRepository->find((int)$request->get("id"));

        if (!$programmeentraineur) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($programmeentraineur);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }


}
