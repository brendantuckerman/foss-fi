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
     * @param int $principal amount in $
     * @param int $interestRate as an annual %
     *
     * @return int amount of interest earned on a principle
     */
    public function calculateAnnualInterest(int $principal, int $interestRate)
    {
        return (int) ($principal * ($interestRate / 100));
    }

    /**
     * Calculates the present value of a series of future cash flows (equivalent
     * to Excel's PV function). Use this when you need to determine what a
     * future stream of payments is worth in today's dollars — for example,
     * when evaluating whether an investment is worthwhile or projecting the
     * lump sum needed today to fund a retirement drawdown.
     *
     * @param float $rate  Periodic interest rate (e.g. 0.005 for 0.5%/month).
     * @param int   $nper  Total number of payment periods.
     * @param float $pmt   Payment made each period (negative for outflows).
     * @param float $fv    Future value remaining after the last payment (default 0).
     * @param int   $type  0 = payments at end of period, 1 = start of period.
     *
     * @return float Present value (negative indicates money paid out).
     */
    function calculatePresentValue(float $rate, int $nper, float $pmt, float $fv = 0, int $type = 0): float
    {
        if ($rate === 0.0) {
            return -($pmt * $nper + $fv);
        }

        $factor = (1 + $rate) ** -$nper;

        return -(
            $pmt * (1 + $rate * $type) * (1 - $factor) / $rate
            + $fv * $factor
        );
    }

    function calculatePreservationAge(int $age) {

        $yearBorn = date('Y') -  $age;

        switch ($yearBorn) {
            case $yearBorn <= 1960:
                $preservation = 55;
                break;
            case $yearBorn == 1961:
                $preservation = 56;
                break;
            case $yearBorn == 1962:
                $preservation = 57;
                break;
            case $yearBorn == 1963;
                $preservation = 58;
                break;
            case $yearBorn == 1964;
                $preservation = 59;
                break;
            default:
                $preservation = 60;
        }

        return $preservation;
    }

    




}
