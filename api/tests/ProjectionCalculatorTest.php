<?php

namespace App\Tests;

use App\Service\ProjectionCalculator;
use PHPUnit\Framework\TestCase;

class ProjectionCalculatorTest extends TestCase
{
    public function testIncomeDifferenceCalculation(): void
    {
        $calculator = new ProjectionCalculator();
        $income = 1000;
        $outgoings = 500;
        $result = $calculator->calculateIncomeDifference($income, $outgoings);
        $this->assertSame(500, $result);

        // Large numbers
        $result_2 = $calculator->calculateIncomeDifference(1000000, 750000);
        $this->assertSame(250000, $result_2);

        // Expenditure exceeds income
        $result_3 = $calculator->calculateIncomeDifference(500, 800);
        $this->assertSame(-300, $result_3);
    }

    public function testSavingsRate(): void
    {
        $calculator = new ProjectionCalculator();
        $income_1 = 1000;
        $outgoings_1 = 500;
        $result_1 = $calculator->calculateSavingsRate($income_1, $outgoings_1);
        $this->assertSame(50, $result_1);

        // Non-round number: ceil(8496/8985*100) = ceil(94.56) = 95
        $income_2 = 8985;
        $outgoings_2 = 489;
        $result_2 = $calculator->calculateSavingsRate($income_2, $outgoings_2);
        $this->assertSame(95, $result_2);

        // Spending more than income: ceil(-100/900*100) = ceil(-11.11) = -11
        $income_3 = 900;
        $outgoings_3 = 1000;
        $result_3 = $calculator->calculateSavingsRate($income_3, $outgoings_3);
        $this->assertSame(-11, $result_3);
    }

    public function testAnnualInterest(): void
    {
        $calculator = new ProjectionCalculator();
        $investment_1 = 1000;
        $interestRate_1 = 5;
        $result_1 = $calculator->calculateAnnualInterest($investment_1, $interestRate_1);
        $this->assertSame(50, $result_1);
    }

    public function testPresentValue(): void
    {
        $calculator = new ProjectionCalculator();

        // Zero interest rate: PV = pmt * nper = 100 * 12 = 1200
        $result_1 = $calculator->calculatePresentValue(0.0, 12, -100);
        $this->assertEqualsWithDelta(1200.0, $result_1, 0.01);

        // Single period, future value only: PV = -fv / (1 + rate) = -1100 / 1.1 = -1000
        $result_2 = $calculator->calculatePresentValue(0.1, 1, 0, 1100);
        $this->assertEqualsWithDelta(-1000.0, $result_2, 0.01);

        // 10% rate, 10 periods, $100k/period: PV = 100000 * (1 - 1.1^-10) / 0.1 ≈ 614,456.71
        $result_3 = $calculator->calculatePresentValue(0.1, 10, -100000);
        $this->assertGreaterThan(600000.0, $result_3);
        $this->assertEqualsWithDelta(614456.71, $result_3, 0.01);
    }


    public function testFutureValue(): void
    {
        $calculator = new ProjectionCalculator();

        // Zero interest rate: FV = -(pmt * nper + pv) = -(-100 * 12 + 0) = 1200
        $result_1 = $calculator->calculateFutureValue(0.0, 12, -100);
        $this->assertEqualsWithDelta(1200.0, $result_1, 0.01);

        // Standard: FV(0.005, 240, -500, 0) ≈ 231,020.45 (20yr monthly @6%pa, $500/mo)
        $result_2 = $calculator->calculateFutureValue(0.005, 240, -500);
        $this->assertEqualsWithDelta(231020.45, $result_2, 0.01);

        // With existing balance: FV(0.005, 240, -500, -50000) ≈ 396,530.67
        $result_3 = $calculator->calculateFutureValue(0.005, 240, -500, -50000);
        $this->assertEqualsWithDelta(396530.67, $result_3, 0.01);
    }

    public function testCalculatePreservationAge(): void
    {
        $calculator = new ProjectionCalculator();

        $result_1 = $calculator->calculatePreservationAge(35);
        $this->assertSame($result_1, 60);

        // These tests will fail after 2026
        $result_2 = $calculator->calculatePreservationAge(69);
        $this->assertSame($result_2, 55);

        $result_3 = $calculator->calculatePreservationAge(64);
        $this->assertSame($result_3, 57);
    }

    public function testCalculateYearsToPreservation(): void
    {
        $calculator = new ProjectionCalculator();

        $result_1 = $calculator->calculateYearsToPreservation(50);
        $this->assertSame($result_1, 10);

        // Negative result
        $result_2 = $calculator->calculateYearsToPreservation(80);
        $this->assertSame($result_2, -25);
    }

    public function testCalculatePreservationYear(): void
    {
        $calculator = new ProjectionCalculator();

        // Test only valid 2026
        $result_1 = $calculator->calculatePreservationYear(50);
        $this->assertSame($result_1, 2036);

    }

    public function testCalculateInflationAdjustedGrowthRate(): void
    {
        $calculator = new ProjectionCalculator();

        $result_1 = $calculator->calculateInflationAdjustedGrowthRate(8.00, 3.00);
        $this->assertSame($result_1, 5.0);

        // Negative growth
        $result_2 = $calculator->calculateInflationAdjustedGrowthRate(3.00, 5.00);
        $this->assertSame($result_2, -2.0);

    }

    public function testCalculateMonthlyExpenses(): void
    {
        $calculator = new ProjectionCalculator();

        $result_1 = $calculator->calculateMonthlyExpenses(60000);
        $this->assertSame($result_1, 5000);
    }

    // Note the cast to int in this function will round down
     public function testCalculatePreSuperSavings(): void
    {
        $calculator = new ProjectionCalculator();

        $result_1 = $calculator->calculatePreSuperSavings(500000, 614456.71);
        $this->assertSame($result_1, 114456);

        $pv_result2 = $calculator->calculatePresentValue(0.1, 10, -100000);
        $result_2 =$calculator->calculatePreSuperSavings(500000, $pv_result2);
        $this->assertSame($result_2, 114456);
    }

    public function testCalculatePreSuperSavingsReducesWithHigherNetWorth(): void
    {
        $calculator = new ProjectionCalculator();
        $pvAmount = 614456.71;

        $result_base = $calculator->calculatePreSuperSavings(500000, $pvAmount);
        $result_higher_net_worth = $calculator->calculatePreSuperSavings(600000, $pvAmount);

        $this->assertLessThan($result_base, $result_higher_net_worth);
    }

     public function testCalculateNper(): void
    {
        $calculator = new ProjectionCalculator();

        // Zero rate: nper = -(pv + fv) / pmt = -(1000 + 0) / -100 = 10
        $result_1 = $calculator->calculateNper(0.0, -100.0, 1000.0);
        $this->assertEqualsWithDelta(10.0, $result_1, 0.01);

        // Standard rate: NPER(0.1, -200, 1000) = log(2) / log(1.1) ≈ 7.27
        $result_2 = $calculator->calculateNper(0.1, -200.0, 1000.0);
        $this->assertEqualsWithDelta(7.27, $result_2, 0.01);

        // Payment too small to cover interest — must throw
        $this->expectException(\InvalidArgumentException::class);
        $calculator->calculateNper(0.1, -50.0, 1000.0);
    }

    public function testCalculateDifferenceTillPreSuper(): void
    {
        $calculator = new ProjectionCalculator();

        // Straight up test - no dependencies
        $result_1 = $calculator->calculateDifferenceTillPreSuper(9.15, 15);
        $this->assertSame($result_1, 5.85);

        // Test with dependencies
        $nper_result2 = $calculator->calculateNper(0.1, -200.0, 1000.0); //7.27
        $years_result2 = $calculator->calculateYearsToPreservation(50); //10
        $result_2 = $calculator->calculateDifferenceTillPreSuper($nper_result2, $years_result2);
         $this->assertSame($result_2, 2.73);
    }

    public function testCalculateRequiredSuper(): void
    {
        $calculator = new ProjectionCalculator();

        // Default
        $result_1 = $calculator->calculateRequiredSuper(85000);
        $this->assertSame($result_1, 2125000);

        // Different drwa down rate
        $result_2 =  $calculator->calculateRequiredSuper(65000, 0.05);
        $this->assertSame($result_2, 1300000);
    }

    public function testCalculatePayment(): void
    {
        $calculator = new ProjectionCalculator();

        // Zero rate: PMT = -(pv + fv) / nper = -(9000 + 0) / 3 = -3000
        $result_1 = $calculator->calculatePayment(0.0, 3, 9000);
        $this->assertEqualsWithDelta(-3000.0, $result_1, 0.01);

        // Standard loan: PMT(5%, 3 periods, $10k) = -(10000 * 1.05^3 * 0.05) / (1.05^3 - 1) ≈ -3672.09
        $result_2 = $calculator->calculatePayment(0.05, 3, 10000);
        $this->assertEqualsWithDelta(-3672.09, $result_2, 0.01);

        // Savings goal (pv=0): PMT(0.5%/mo, 240 months, pv=0, fv=100000) ≈ -216.43
        $result_3 = $calculator->calculatePayment(0.005, 240, 0.0, 100000);
        $this->assertEqualsWithDelta(-216.43, $result_3, 0.01);
    }

    public function testCalculateInterestPayment(): void
    {
        $calculator = new ProjectionCalculator();

        // Zero rate: no interest in any period
        $result_1 = $calculator->calculateInterestPayment(0.0, 1, 3, 10000);
        $this->assertEqualsWithDelta(0.0, $result_1, 0.01);

        // Period 1: interest on full balance = -(10000 * 0.05) = -500
        $result_2 = $calculator->calculateInterestPayment(0.05, 1, 3, 10000);
        $this->assertEqualsWithDelta(-500.0, $result_2, 0.01);

        // Period 2: balance after period 1 = 10000*1.05 + (-3672.09)*1 = 6827.91, interest = -(6827.91 * 0.05) ≈ -341.40
        $result_3 = $calculator->calculateInterestPayment(0.05, 2, 3, 10000);
        $this->assertEqualsWithDelta(-341.40, $result_3, 0.01);
    }

    public function testCalculatePrincipalPayment(): void
    {
        $calculator = new ProjectionCalculator();

        // Zero rate: all payment is principal = -(9000 / 3) = -3000
        $result_1 = $calculator->calculatePrincipalPayment(0.0, 1, 3, 9000);
        $this->assertEqualsWithDelta(-3000.0, $result_1, 0.01);

        // Period 1: PPMT = PMT - IPMT = -3672.09 - (-500) = -3172.09
        $result_2 = $calculator->calculatePrincipalPayment(0.05, 1, 3, 10000);
        $this->assertEqualsWithDelta(-3172.09, $result_2, 0.01);

        // Period 3 (final): PPMT = PMT - IPMT = -3672.09 - (-174.86) ≈ -3497.23
        $result_3 = $calculator->calculatePrincipalPayment(0.05, 3, 3, 10000);
        $this->assertEqualsWithDelta(-3497.23, $result_3, 0.01);
    }

    public function testCreatePreSuperSchedule(): void
    {
        //Set up vars
        $r1_yearsTilPreSuper = 5.4;
        $r1_annualDepositAmount = 55000;
        $r1_interestRate = 5.00 / 100;
        $r1_netWorth = 100000;
        $r1_preSuperTarget = 462008;

        $calculator = new ProjectionCalculator();

        $result_1 = $calculator->createPreSuperSchedule($r1_yearsTilPreSuper, $r1_annualDepositAmount, $r1_interestRate, $r1_netWorth, $r1_preSuperTarget);
        $this->assertIsArray($result_1, 'This is an array');
        $this->assertArrayHasKey('1', $result_1);
        $this->assertArrayHasKey('6', $result_1);
        $this->assertArrayNotHasKey('7', $result_1);
        // Check first and last
        $this->assertEquals($result_1[1]['year'], 2027);
        $this->assertEquals($result_1[1]['deposit'], 55000);
        $this->assertEquals($result_1[1]['interestMade'], 5000);
        $this->assertEquals($result_1[1]['savedSoFar'], 60000);
        $this->assertEquals($result_1[1]['balance'], 160000);
        // Last
        $this->assertEquals($result_1[6]['year'], 2032);
        $this->assertEquals($result_1[6]['deposit'], 55000);
        $this->assertEquals($result_1[6]['interestMade'], 21577);
        $this->assertEquals($result_1[6]['savedSoFar'], 76577);
        $this->assertEquals($result_1[6]['balance'], 508115);

    }

    public function testCreatePreSuperToZero(): void
    {
         //Set up vars
        $r1_initialYear = 2031;
        $r1_principal = 462008;
        $r1_expenses = 65000;
        $r1_interestRate = 5.00 / 100;


        $calculator = new ProjectionCalculator();
        $result_1 = $calculator->createPreSuperToZero($r1_initialYear, $r1_principal, $r1_expenses, $r1_interestRate);
        $this->assertIsArray($result_1, 'Result is an array');
        //Ensure correct amount of results
        $this->assertArrayHasKey('0', $result_1);
        $this->assertArrayHasKey('8', $result_1);
        $this->assertArrayNotHasKey('9', $result_1);
        //Check first and last
        $this->assertEquals($result_1[0]['year'], 2031);
        $this->assertEquals($result_1[0]['balance'], $r1_principal);
        $this->assertEquals($result_1[0]['interestMade'], 23100);
        // Last
        $this->assertEquals($result_1[8]['year'], 2039);
        $this->assertEquals($result_1[8]['balance'], 61904);
        $this->assertEquals($result_1[8]['interestMade'], 3095 );

    }

    public function testCalculatePreSuperSweetSpot(): void
    {
        $calculator = new ProjectionCalculator();
        $r1_annualExpenses = 65000;
        $r1_yearsTillPreservation = 13;
        $r1_interestRate = 5.00 / 100;
        $r1_yearlySavings = 55000;
        $r1_netWorth = 100000;

        $result_1 = $calculator->calculatePreSuperSweetSpot($r1_annualExpenses, $r1_yearsTillPreservation, $r1_interestRate, $r1_yearlySavings, $r1_netWorth);
        $this->assertEquals($result_1, 8);
    }
}


