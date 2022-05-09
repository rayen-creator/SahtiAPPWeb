<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Promotion;
use App\Form\PromotionType;
use App\Repository\PromotionRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Client;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

/**
 * @Route("/promotion")
 */
class PromotionController extends AbstractController
{
    /**
     * @Route("/", name="app_promotion_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager,Request $request,PaginatorInterface $paginator): Response
    {
        $donnees = $entityManager
            ->getRepository(Promotion::class)
            ->findAll();
        $promotions = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            4 // Nombre de résultats par page
        );



        return $this->render('promotion/index.html.twig', [
            'promotions' => $promotions,
        ]);
    }

    /**
     * @Route("/new", name="app_promotion_new", methods={"GET", "POST"})
     */
    public function new(MailerInterface $mailer ,Request $request, EntityManagerInterface $entityManager,PromotionRepository  $repository): Response
    {
        $produit = new Produit();
        $promotion = new Promotion();
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $promotion->getImage();
            $fileName=md5(uniqid()) .'.'.$file->guessExtension();
            try
            {
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );}
            catch (FileException $e){
            }
            $clients = $entityManager
                ->getRepository(Client::class)
                ->findAll();
            foreach ($clients as $client)
            {
                $email = (new  TemplatedEmail())
                    ->from('noreplysahti@gmail.com')
                    // On attribue le destinataire
                    ->to($client->getEmail())
                    // On crée le texte avec la vue
                    ->subject($form["titre"]->getData())
                    ->htmlTemplate('promotion/emails.html.twig')
                    ->context([
                        'porcentage' => $form["porcentage"]->getData(),
                        'desc' => $form["descprom"]->getData(),
                        'pic'=> $fileName
                    ]);
                $mailer->send($email);
            }
            $promotion->setImage($fileName);
            $porcentage = $form["porcentage"]->getData();
            $idproduit = $form['idProd']->getData();
            $produit = $entityManager
                ->getRepository(Produit::class)
                ->find($idproduit);
            $prix = $produit->getprix();
            $promotion->setAncienprix($prix);
            $newprix = ($prix * $porcentage) / 100;
            $produit->setprix($newprix);
            $entityManager->persist($produit);
            $entityManager->persist($promotion);
            $entityManager->flush();
            return $this->redirectToRoute('app_promotion_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('promotion/new.html.twig', [
            'promotion' => $promotion,
            'form' => $form->createView(),
        ]);


    }

    /**
     * @Route("/{idProm}", name="app_promotion_show", methods={"GET"})
     */
    public function show(Promotion $promotion): Response
    {
        return $this->render('promotion/show.html.twig', [
            'promotion' => $promotion,
        ]);
    }

    /**
     * @Route("/{idProm}/edit", name="app_promotion_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Promotion $promotion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $porcent = $form["porcentage"]->getData();
            $id = $form['idProd']->getData();
            $produit = $entityManager
                ->getRepository(Produit::class)
                ->find($id);
            $prix = $promotion->getAncienprix();
            $newprix = ($prix * $porcent) / 100;
            $produit->setprix($newprix);
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('app_promotion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('promotion/edit.html.twig', [
            'promotion' => $promotion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idProm}", name="app_promotion_delete", methods={"POST"})
     */
    public function delete(Request $request, Promotion $promotion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$promotion->getIdProm(), $request->request->get('_token'))) {
            $idproduit = $promotion->getIdProd();
            $produit = $entityManager
                ->getRepository(Produit::class)
                ->find($idproduit);
            $prix =$promotion->getAncienprix();
            $produit->setPrix($prix);
            $entityManager->persist($produit);
            $entityManager->remove($promotion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_promotion_index', [], Response::HTTP_SEE_OTHER);
    }


}

