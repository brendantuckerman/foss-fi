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
}
