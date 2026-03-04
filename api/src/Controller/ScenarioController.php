<?php

namespace App\Controller;

use app\Entity\Scenario;
use App\Service\ProjectionCalulator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ScenarioController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $calculator = new ProjectionCalulator();
        $leftOver = $calculator->calculateIncomeDifference(10, 5);
        return $this->render('scenario/index.html.twig', [
            'controller_name' => 'ScenarioController',
            'Leftover' => $leftOver
        ]);
    }
}
