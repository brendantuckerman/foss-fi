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

        //Form
        $scenario = new Scenario();
        $form = $this->createForm(ScenarioType::class, $scenario);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // TODO: In future, we only want to do this if a user
            // is logged in
            $this->entityManager->persist($scenario);
            $this->entityManager->flush();

            // Go to the new scenario
            return $this->redirectToRoute('scenario', ['id' => $scenario->getId()]);

        }

        return $this->render('scenario/index.html.twig', [
            'controller_name' => 'ScenarioController',
            'scenario_form' => $form,

        ]);
    }

     #[Route('/scenario/{id}', name: 'scenario')]
     public function show(Scenario $scenario): Response
     {
        return $this->render('scenario/show.html.twig', [
            'scenario' => $scenario
        ]);
     }
}
