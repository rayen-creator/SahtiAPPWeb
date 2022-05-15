<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ProduitRepository;
use App\Entity\Produit;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class MobileController extends AbstractController
{
    /**
     * @Route("/liste", name="liste")
     * @param NormalizerInterface $normalizer
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function displayProduitMobile(NormalizerInterface $normalizer): Response
    {
        $produit = $this->getDoctrine()->getRepository(Produit::class)->findAll();
        $formatted = $normalizer->normalize($produit,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;

    }
    /**
     * @Route("/addProduitMobile", name="addProduitMobile")
     */
    public function addProduitMobile(Request $request, NormalizerInterface $normalizer): Response
    {
        $produit = new Produit();
        $entityManager = $this->getDoctrine()->getManager();
        $produit->setNom($request->get('nom'));
        $prix =(float)$request->query->get('prix');
        $produit->setPrix($prix);
        $produit->setImage($request->get('image'));
        $quantite =$request->query->get('quantite');
        $produit->setQuantite($quantite);
        $produit->setDescprod($request->get('descProd'));
        $produit->setIdCat($request->get('id_cat'));
        $entityManager->persist($produit);
        $entityManager->flush();
        $jsonContent = $normalizer->normalize($produit, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/deleteProduitMobile", name="deleteProduitMobile")
     */
    public function deleteProduitMobile(Request $request, SerializerInterface $serializer): Response
    {
        $id = $request->query->get("idProd");
        $entityManager = $this->getDoctrine()->getManager();
        $produit= $entityManager->getRepository(Produit::class)->find($id);
        if ($produit != null) {
            $entityManager->remove($produit);
            $entityManager->flush();
            $formatted = $serializer->normalize($produit, 'json', ['groups' => 'post:read']);
            return new Response(json_encode($formatted));

        }


        return new Response(" type invalide");
    }
    /**
     * @Route("/updateProduitMobile", name="updateProduitMobile")
     */
    public function updateProduitMobile(Request $request): Response{
        $em = $this->getDoctrine()->getManager();
        $produit = $this->getDoctrine()->getManager()
            ->getRepository(Produit::class)
            ->find($request->get("idProd"));
        $produit->setNom($request->get('nom'));
        $prix =(float)$request->query->get('prix');
        $produit->setPrix($prix);
        $produit->setImage($request->get('image'));
        $quantite =$request->query->get('quantite');
        $produit->setQuantite($quantite);
        $produit->setDescprod($request->get('descProd'));
        $produit->setIdCat($request->get('id_cat'));
        $em->persist($produit);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($produit);
        return new JsonResponse("Produit a ete modifiee avec success.");


    }


}