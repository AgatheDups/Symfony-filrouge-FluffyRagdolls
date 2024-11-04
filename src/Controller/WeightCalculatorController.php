<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WeightCalculatorController extends AbstractController
{
    #[Route('/weight-calculator', name: 'app_weight_calculator')]
    public function index(): Response
    {
        return $this->render('weight_calculator/index.html.twig', [
            'controller_name' => 'WeightCalculatorController',
        ]);
    }
}
