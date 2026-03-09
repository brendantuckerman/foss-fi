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
        // ## Calculations ##
        // Scenarion values
        $age = $scenario->getAge();
        $income = $scenario->getIncome();
        $outgoings = $scenario->getOutgoings();
        $returnRate = $scenario->getReturnRate();
        $inflationRate = $scenario->getInflationRate();
        $netWorth = $scenario->getInvestmentAmount();
        $currentSuper = $scenario->getSuper();
        $superGuarantee = $scenario->getSuperGuarantee();
        // Shorthand
        $compute = $this->calculator;
        // Service calculations
        // PRE SUPER
        $annualSavings = $compute->calculateIncomeDifference($income, $outgoings);
        $superPreservationAge = $compute->calculatePreservationAge($age);
        $yearsUntilPreservation = $compute->calculateYearsToPreservation($age);
        $preservationYear = $compute->calculatePreservationYear($age);
        $savingsRate = $compute->calculateSavingsRate($income, $outgoings);
        $inflationAdjustedGrowth = $compute->calculateInflationAdjustedGrowthRate($returnRate , $inflationRate);
        $monthlyExpenses = $compute->calculateMonthlyExpenses($outgoings);
        // NB: Need to convert % to decimal
        $currentPv = $compute->calculatePresentValue($inflationAdjustedGrowth / 100, $yearsUntilPreservation, ($outgoings * -1), 0);

        // Post PV requires the number of periods
        $superSweetSpot = $compute->calculatePreSuperSweetSpot($outgoings, $yearsUntilPreservation, $inflationAdjustedGrowth / 100,  $annualSavings, $netWorth);
        $postPv = $compute->calculatePresentValue($inflationAdjustedGrowth / 100, $superSweetSpot, -$outgoings, 0);

        $remainingPreSuper = $currentPv - $postPv;
        $yearsPreSuperNper = $compute->calculateNper($inflationAdjustedGrowth/ 100, $annualSavings, $netWorth, -$postPv);
        $preservationPreSuperDifference = $compute->calculateDifferenceTillPreSuper($yearsPreSuperNper, $yearsUntilPreservation);

        $yearOfPresuper = date('Y') + ceil($yearsPreSuperNper);

        // Super
        $superTarget = $compute->calculateRequiredSuper($outgoings);
        $superResult = $compute->calculateFutureValue($inflationAdjustedGrowth / 100, $yearsPreSuperNper, ($income * ($superGuarantee / 100) * -1), -$currentSuper);
        $superRequiredForFi = $compute->calculatePresentValue($inflationAdjustedGrowth /100, $preservationPreSuperDifference - 1.71,0, -$superTarget );
        $superNeeded = $superRequiredForFi - $superResult;
        $yearsToSuperTarget = $compute->calculateNper($inflationAdjustedGrowth /100, $income, $superResult, -$superRequiredForFi);

        // Grand total
        $yearsToFi = $yearsToSuperTarget + $yearsPreSuperNper;
        $yearOfFi = ceil(date('Y') + $yearsToFi);

        // Currently rendered for testing
        return $this->render('scenario/show.html.twig', [
            'scenario' => $scenario,
            'superPreservationAge' => $superPreservationAge,
            'yearsUntilPreservation' => $yearsUntilPreservation,
            'preservationYear' => $preservationYear,
            'savingsRate' => $savingsRate,
            'inflationAdjustedGrowth' => $inflationAdjustedGrowth,
            'monthlyExpenses' => $monthlyExpenses,
            'currentPv' => $currentPv,
            'postPv' => $postPv,
            'remainingPreSuper' => $remainingPreSuper,
            'yearsPreSuperPv' => $yearsPreSuperNper,
            'yearsDifferencePreSuper' => $preservationPreSuperDifference,
            'yearOfPreSuper' => $yearOfPresuper,
            'superTarget' => $superTarget,
            'superResult' => $superResult,
            'superRequiredForFi' => $superRequiredForFi,
            'superNeeded' => $superNeeded,
            'yearsToSuperTarget' => $yearsToSuperTarget,
            'yearsToFi' => $yearsToFi,
            'yearOfFi' => $yearOfFi


        ]);
     }
}
