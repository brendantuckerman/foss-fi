<?php

namespace App\Service;


class ProjectionCalculator
{
    /**
     * @param $income int
     * @param $expendture int
     *
     * @return int difference between income and expenditure
     */
    public function calculateIncomeDifference(int $income, int $expenditure): int
    {
        return ($income - $expenditure);
    }


    public function calculateSavingsRate(int $income, int $expenditure): int
    {
        return (int) ceil(($income - $expenditure) / $income * 100);
    }

    /**
     * @param int $investyment amount in $
     * @param int $interestRate as an annual %
     *
     * @return int diff between income and expenditure
     */
    public function calculateAnnualInterest(int $investmentAmount, int $interestRate)
    {
        return (int) ($investmentAmount * ($interestRate / 100));
    }


}
