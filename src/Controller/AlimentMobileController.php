<?php

namespace App\Controller;

use App\Entity\Aliment;
use App\Repository\AlimentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


/**
 * @Route("/mobile/aliment")
 */
class AlimentMobileController extends AbstractController
{
//    /**
//     * @Route("", methods={"GET"})
//     */
//    public function index(AlimentRepository $alimentRepository): JsonResponse
//    {
//        $aliments = $alimentRepository->findAll();
//
//            return new JsonResponse($aliments, 200);
//    }

    /**
     * @param NormalizerInterface $Normalizer
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @Route("/",methods={"GET"})
     */
    public function index(NormalizerInterface $Normalizer)
    {
        $aliments = $this->getDoctrine()->getRepository(Aliment::class)->findAll();

        $jsonContent = $Normalizer->normalize($aliments, 'json', ['groups' => 'post:read']);

        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $aliment = new Aliment();

        return $this->manage($aliment, $request, false);
    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, AlimentRepository $alimentRepository): Response
    {
        $aliment = $alimentRepository->find((int)$request->get("idAliment"));

        if (!$aliment) {
            return new JsonResponse(null, 404);
        }

        return $this->manage($aliment, $request, true);
    }

    public function manage($aliment, $request, $isEdit): JsonResponse
    {
        $file = $request->files->get("file");
        if ($file) {
            $imageFileName = md5(uniqid()) . '.' . $file->guessExtension();

            try {
                $file->move($this->getParameter('team_directory'), $imageFileName);
            } catch (FileException $e) {
                dd($e);
            }
        } else {
            if ($request->get("image")) {
                $imageFileName = $request->get("image");
            } else {
                $imageFileName = "null";
            }
        }

        $aliment->setUp(
            $request->get("nom"),
            $request->get("type"),
            $imageFileName,
            $request->get("calories"),
            $request->get("description")
        );



        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($aliment);
        $entityManager->flush();

        return new JsonResponse($aliment, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, AlimentRepository $alimentRepository): JsonResponse
    {
        $aliment =  $alimentRepository->find((int)$request->get("idAliment"));

        if (!$aliment) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($aliment);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }

    /**
     * @Route("deleteAll", methods={"POST"})
     */
    public function deleteAll(EntityManagerInterface $entityManager, AlimentRepository $alimentRepository): Response
    {
        $aliments = $alimentRepository->findAll();

        foreach ($aliments as $aliment) {
            $entityManager->remove($aliment);
            $entityManager->flush();
        }

        return new JsonResponse([], 200);
    }

    /**
     * @Route("/image/{image}", methods={"GET"})
     */
    public function getPicture(Request $request): BinaryFileResponse
    {
        return new BinaryFileResponse(
            $this->getParameter('team_directory') . "/" . $request->get("image")
        );
    }

}