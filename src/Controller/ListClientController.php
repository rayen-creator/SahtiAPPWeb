<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListClientController extends AbstractController
{
    /**
     * @Route("/list/client", name="app_list_client")
     */
    public function index(): Response
    {
        return $this->render('list_client/index.html.twig', [
            'controller_name' => 'ListClientController',
        ]);
    }
}
