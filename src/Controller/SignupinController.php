<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SignupinController extends AbstractController
{
    /**
     * @Route("/signupin", name="app_signupin")
     */
    public function index(): Response
    {
        return $this->render('signupin/index.html.twig', [
            'controller_name' => 'SignupinController',
        ]);
    }
}
