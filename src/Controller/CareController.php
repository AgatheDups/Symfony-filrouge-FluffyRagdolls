<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CareController extends AbstractController
{
    #[Route('/care', name: 'app_care')]
    public function index(): Response
    {
        return $this->render('care/index.html.twig', [
            'controller_name' => 'CareController',
        ]);
    }
}
