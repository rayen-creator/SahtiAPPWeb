<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminpanelController extends AbstractController
{
    /**
     * @Route("/adminpanel", name="app_adminpanel")
     */
    public function index(): Response
    {
        return $this->render('adminpanel/index.html.twig', [
            'controller_name' => 'AdminpanelController',
        ]);
    }
}
