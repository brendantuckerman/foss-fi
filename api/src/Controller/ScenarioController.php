<?php

namespace App\Controller;

use App\Entity\Scenario;
use App\Form\ScenarioType;
use App\Service\ProjectionCalculator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    #[Route('/api/scenario/calculate', name: 'scenario_calculate', methods: ['POST'])]
    public function calculate(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!is_array($data)) {
            return $this->json(['error' => 'Invalid JSON'], 400);
        }

        $scenario = new Scenario();
        $scenario->setAge((int) ($data['age'] ?? 0));
        $scenario->setIncome((int) ($data['income'] ?? 0));
        $scenario->setPostTaxIncome((int) ($data['postTaxIncome'] ?? 0));
        $scenario->setOutgoings((int) ($data['outgoings'] ?? 0));
        $scenario->setReturnRate((string) ($data['returnRate'] ?? 0));
        $scenario->setInflationRate((string) ($data['inflationRate'] ?? 0));
        $scenario->setInvestmentAmount((int) ($data['investmentAmount'] ?? 0));
        $scenario->setSuper((int) ($data['super'] ?? 0));
        $scenario->setSuperGuarantee((string) ($data['superGuarantee'] ?? 0));

        return $this->json($this->runCalculations($scenario));
    }

    #[Route('/scenario/{id}', name: 'scenario')]
    public function show(Scenario $scenario): Response
    {
        return $this->render('scenario/show.html.twig', array_merge(
            ['scenario' => $scenario],
            $this->runCalculations($scenario),
        ));
    }

    /**
     * Runs all FI projections for a scenario and returns the results as an array.
     *
     * preTaxIncome is used for super contribution calculations.
     * postTaxIncome is used for savings and savings rate calculations.
     */
    private function runCalculations(Scenario $scenario): array
    {
        $age = $scenario->getAge();
        $postTaxIncome = (int) $scenario->getPostTaxIncome();
        $preTaxIncome = (int) $scenario->getIncome();
        $outgoings = $scenario->getOutgoings();
        $returnRate = (float) $scenario->getReturnRate();
        $inflationRate = (float) $scenario->getInflationRate();
        $netWorth = $scenario->getInvestmentAmount();
        $currentSuper = $scenario->getSuper();
        $superGuarantee = (float) $scenario->getSuperGuarantee();

        //Just to stop me typing it so many times
        $compute = $this->calculator;

        $annualSavings = $compute->calculateIncomeDifference($postTaxIncome, $outgoings);
        $superPreservationAge = $compute->calculatePreservationAge($age);
        $yearsUntilPreservation = $compute->calculateYearsToPreservation($age);
        $preservationYear = $compute->calculatePreservationYear($age);
        $savingsRate = $compute->calculateSavingsRate($postTaxIncome, $outgoings);
        $inflationAdjustedGrowth = $compute->calculateInflationAdjustedGrowthRate($returnRate, $inflationRate);
        $monthlyExpenses = $compute->calculateMonthlyExpenses($outgoings);

        $currentPv = $compute->calculatePresentValue($inflationAdjustedGrowth / 100, $yearsUntilPreservation, ($outgoings * -1), 0);
        $superSweetSpot = $compute->calculatePreSuperSweetSpot($outgoings, $yearsUntilPreservation, $inflationAdjustedGrowth / 100, $annualSavings, $netWorth);
        $postPv = $compute->calculatePresentValue($inflationAdjustedGrowth / 100, $superSweetSpot, -$outgoings, 0);

        $remainingPreSuper = $currentPv - $postPv;
        $yearsPreSuperNper = $compute->calculateNper($inflationAdjustedGrowth / 100, $annualSavings, $netWorth, -$postPv);
        $preservationPreSuperDifference = $compute->calculateDifferenceTillPreSuper($yearsPreSuperNper, $yearsUntilPreservation);
        $yearOfPresuper = date('Y') + ceil($yearsPreSuperNper);

        $superTarget = $compute->calculateRequiredSuper($outgoings);
        $superResult = $compute->calculateFutureValue($inflationAdjustedGrowth / 100, $yearsPreSuperNper, ($preTaxIncome * ($superGuarantee / 100) * -1), -$currentSuper);
        $superRequiredForFi = $compute->calculatePresentValue($inflationAdjustedGrowth / 100, $preservationPreSuperDifference - 1.71, 0, -$superTarget);
        $superNeeded = $superRequiredForFi - $superResult;
        $yearsToSuperTarget = $compute->calculateNper($inflationAdjustedGrowth / 100, $preTaxIncome, $superResult, -$superRequiredForFi);

        $yearsToFi = $yearsToSuperTarget + $yearsPreSuperNper;
        $yearOfFi = ceil(date('Y') + $yearsToFi);

        // Schedules
        // Pre super
        $preSuperSchedule = $compute->createPreSuperSchedule($yearsPreSuperNper, $annualSavings, $inflationAdjustedGrowth /100, $netWorth);
        $preSuperToZeroSchedule = $compute->createPreSuperToZero($yearOfPresuper, $postPv, $outgoings, $inflationAdjustedGrowth / 100);


        // Post super -not yet done

        return [
            'superPreservationAge' => $superPreservationAge,
            'yearsUntilPreservation' => $yearsUntilPreservation,
            'preservationYear' => $preservationYear,
            'savingsRate' => $savingsRate,
            'inflationAdjustedGrowth' => $inflationAdjustedGrowth,
            'monthlyExpenses' => $monthlyExpenses,
            'currentPv' => $currentPv,
            'postPv' => $postPv,
            'remainingPreSuper' => $remainingPreSuper,
            'yearsPreSuperNper' => $yearsPreSuperNper,
            'yearsDifferencePreSuper' => $preservationPreSuperDifference,
            'yearOfPreSuper' => $yearOfPresuper,
            'superTarget' => $superTarget,
            'superResult' => $superResult,
            'superRequiredForFi' => $superRequiredForFi,
            'superNeeded' => $superNeeded,
            'yearsToSuperTarget' => $yearsToSuperTarget,
            'yearsToFi' => $yearsToFi,
            'yearOfFi' => $yearOfFi,
            'preSuperSchedule' => $preSuperSchedule,
            'preSuperToZeroSchedule' => $preSuperToZeroSchedule 
        ];
    }
}
