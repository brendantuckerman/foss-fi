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
        $this->assertTrue($result === 500);
    }
}
