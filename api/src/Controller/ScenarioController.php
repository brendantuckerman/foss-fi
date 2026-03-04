<?php

namespace App\Controller;

use App\Entity\Scenario;
use App\Form\ScenarioType;
use App\Service\ProjectionCalculator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ScenarioController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ProjectionCalculator $calculator,
    ){

    }

    #[Route('/', name: 'home')]
    public function index(Request $request): Response
    {

        $leftOver = $this->calculator->calculateIncomeDifference(10, 5);

        //Form
        $scenario = new Scenario();
        $form = $this->createForm(ScenarioType::class, $scenario);
        $form->handleRequest($request);

        return $this->render('scenario/index.html.twig', [
            'controller_name' => 'ScenarioController',
            'Leftover' => $leftOver,
            'scenario_form' => $form,
        ]);
    }
}
