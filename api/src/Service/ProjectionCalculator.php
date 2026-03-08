<?php

namespace App\Service;

use DateTime;

class ProjectionCalculator
{
    // ## Pre super calculations ##

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

    /**
     * @param $income int
     * @param $expendture int
     *
     * @return int percentage of income that is currently being saved
     */
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

        return round(-(
            $pmt * (1 + $rate * $type) * (1 - $factor) / $rate
            + $fv * $factor
        ), 2);
    }

    /**
     * Calculates the future value of an investment based on periodic, constant
     * payments and a constant interest rate (equivalent to Excel's FV function).
     * Use this when you need to project how much an investment or savings plan
     * will be worth after a series of contributions — for example, estimating
     * a super balance at retirement given regular contributions and a growth rate.
     *
     * @param float $rate  Periodic interest rate (e.g. 0.005 for 0.5%/month).
     * @param int   $nper  Total number of payment periods.
     * @param float $pmt   Payment made each period (negative for outflows).
     * @param float $pv    Present value / initial balance (default 0).
     * @param int   $type  0 = payments at end of period, 1 = start of period.
     *
     * @return float Future value (negative indicates money paid out).
     */
    function calculateFutureValue(float $rate, int $nper, float $pmt, float $pv = 0, int $type = 0): float
    {
        if ($rate === 0.0) {
            return -($pmt * $nper + $pv);
        }

        $factor = (1 + $rate) ** $nper;

        return -(
            $pmt * (1 + $rate * $type) * ($factor - 1) / $rate
            + $pv * $factor
        );
    }

    /**
     * Calculates the fixed periodic payment for a loan or annuity (equivalent
     * to Excel's PMT function). Use this to determine the regular payment amount
     * needed to repay a loan or reach a savings target within a fixed number of
     * periods — for example, calculating the monthly repayment on a mortgage or
     * the contribution needed to reach a retirement savings goal.
     *
     * @param float $rate  Periodic interest rate (e.g. 0.005 for 0.5%/month).
     * @param int   $nper  Total number of payment periods.
     * @param float $pv    Present value / initial balance.
     * @param float $fv    Future value remaining after the last payment (default 0).
     * @param int   $type  0 = payments at end of period, 1 = start of period.
     *
     * @return float Payment per period (negative indicates money paid out).
     */
    public function calculatePayment(float $rate, int $nper, float $pv, float $fv = 0.0, int $type = 0): float
    {
        if ($rate === 0.0) {
            return round(-($pv + $fv) / $nper, 2);
        }

        $factor = (1 + $rate) ** $nper;

        return round(
            -(($pv * $factor + $fv) * $rate) / (($factor - 1) * (1 + $rate * $type)),
            2
        );
    }

    /**
     * Calculates the interest portion of a payment for a specific period
     * (equivalent to Excel's IPMT function). Use this to determine how much
     * of a given payment goes towards interest — useful for tracking loan
     * amortisation or understanding the interest cost at each stage of repayment.
     *
     * For annuity-due (type=1), period 1 returns 0 because the first payment
     * is made immediately with no prior interest accrual.
     *
     * @param float $rate  Periodic interest rate (e.g. 0.005 for 0.5%/month).
     * @param int   $per   The period for which to calculate interest (1-indexed).
     * @param int   $nper  Total number of payment periods.
     * @param float $pv    Present value / initial balance.
     * @param float $fv    Future value remaining after the last payment (default 0).
     * @param int   $type  0 = payments at end of period, 1 = start of period.
     *
     * @return float Interest portion of the payment (negative indicates money paid out).
     */
    public function calculateInterestPayment(float $rate, int $per, int $nper, float $pv, float $fv = 0.0, int $type = 0): float
    {
        if ($rate === 0.0) {
            return 0.0;
        }

        if ($type === 1 && $per === 1) {
            return 0.0;
        }

        $pmt = $this->calculatePayment($rate, $nper, $pv, $fv, $type);

        // For annuity-due, the interest in period per is accrued on the balance
        // after the previous beginning-of-period payment. Shift back one period
        // and use the balance after the first payment as the effective PV.
        if ($type === 1) {
            $effectivePer = $per - 1;
            $effectivePv  = $pv + $pmt;
        } else {
            $effectivePer = $per;
            $effectivePv  = $pv;
        }

        $balance = $effectivePv * (1 + $rate) ** ($effectivePer - 1)
            + $pmt * ((1 + $rate) ** ($effectivePer - 1) - 1) / $rate;

        return round(-$balance * $rate, 2);
    }

    /**
     * Calculates the principal portion of a payment for a specific period
     * (equivalent to Excel's PPMT function). Use this alongside
     * calculateInterestPayment() to break down how much of each payment reduces
     * the outstanding balance versus covering interest charges — for example,
     * when modelling the equity built up in a property over time.
     *
     * @param float $rate  Periodic interest rate (e.g. 0.005 for 0.5%/month).
     * @param int   $per   The period for which to calculate principal (1-indexed).
     * @param int   $nper  Total number of payment periods.
     * @param float $pv    Present value / initial balance.
     * @param float $fv    Future value remaining after the last payment (default 0).
     * @param int   $type  0 = payments at end of period, 1 = start of period.
     *
     * @return float Principal portion of the payment (negative indicates money paid out).
     */
    public function calculatePrincipalPayment(float $rate, int $per, int $nper, float $pv, float $fv = 0.0, int $type = 0): float
    {
        $pmt  = $this->calculatePayment($rate, $nper, $pv, $fv, $type);
        $ipmt = $this->calculateInterestPayment($rate, $per, $nper, $pv, $fv, $type);

        return round($pmt - $ipmt, 2);
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

     public function calculateYearsToPreservation(int $age): int
    {
        $preservationAge = $this->calculatePreservationAge($age);
        return  $preservationAge - $age;

    }

    public function calculatePreservationYear(int $age): int
    {
        $yearsToPreservation = $this->calculateYearsToPreservation($age);
        $currentYear = date('Y');
        return $currentYear + $yearsToPreservation;
    }

    public function calculateInflationAdjustedGrowthRate(float $returnRate, float $inflationRate): float
    {
        return $returnRate - $inflationRate;
    }

    public function calculateMonthlyExpenses(int $annualExpenses): int
    {
        return $annualExpenses / 12;
    }

    /**
     * Calculate what is needed to save pre super. Casts float to int to return
     * dollar amount, so note that it will round down.
     *
     * @param int $netWorth $ amount of savings / investments
     * @param float $pvAmount result of calculatePresentValue()
     *
     * @return int the amout needed to save pre-super
     */
    public function calculatePreSuperSavings(int $netWorth, float $pvAmount ): int
    {
        $intPv = (int) $pvAmount;
        return  $intPv - $netWorth;
    }

    /**
     * Calculate the number of periods for an investment or loan.
     *
     * Equivalent to Excel's NPER(rate, pmt, pv, [fv], [type]).
     *
     * @param float $rate Interest rate per period.
     * @param float $pmt  Payment per period (negative = cash out).
     * @param float $pv   Present value (negative = cash out).
     * @param float $fv   Future value target (default 0 = full payoff).
     * @param int   $type 0 = end-of-period payments, 1 = beginning-of-period.
     *
     * @return float number of periods required to reach the target. In this case, years.
     *
     * @throws \InvalidArgumentException If the inputs produce an invalid result.
     */
    public function calculateNper(float $rate, float $pmt, float $pv, float $fv = 0.0, int $type = 0): float
    {
        if ($rate === 0.0) {
            if ($pmt === 0.0) {
                throw new \InvalidArgumentException('PMT and rate cannot both be zero.');
            }
            return -($pv + $fv) / $pmt;
        }

        // For annuity-due (type=1), payments earn one extra period of interest.
        $effectivePmt = $type === 1 ? $pmt * (1 + $rate) : $pmt;

        $numerator   = $effectivePmt / $rate - $fv;
        $denominator = $pv + $effectivePmt / $rate;

        if ($denominator === 0.0 || ($numerator / $denominator) <= 0) {
            throw new \InvalidArgumentException(
                'Cannot compute NPER: check that the payment is sufficient to cover interest.'
            );
        }

        return log($numerator / $denominator) / log(1 + $rate);
    }
    /**
     * Take the result of the Nper function and the number of years till preservation
     * and return the difference. Again, casting to int will round down.
     */
    public function calculateDifferenceTillPreSuper(float $nperResult, int $yearsTillPreservation): float
    {
        $floatYears = (float) $yearsTillPreservation;
        return round($floatYears - $nperResult, 2);
    }

    // ### Post super calculations ###


    public function calculateRequiredSuper(int $annualExpenses, float $drawDownRate=0.04): int
    {
        $floatAmount = $annualExpenses / $drawDownRate;
        return (int) $floatAmount;
    }

    // ### Schedule creations
    public function createPreSuperSchedule(float $yearsTilPreSuper, int $annualDepositAmount, float $adjustedInterestRate, float $netWorth): array
    {
        $schedule = [];
        $currentYear = (int) date('Y');

        //For loop while i < $yearsTilPreSuper.
        // Each i is a period
        // Need to start at 1 to match years
        for ($i = 1; $i -1 < $yearsTilPreSuper; $i++ ) {
            $interestMade = round($netWorth * ($adjustedInterestRate / 100), 0);
            $savedSoFar = $annualDepositAmount + $interestMade;
            $netWorth += $savedSoFar;

            $schedule[$i] = [
                'year' => $currentYear + $i,
                'deposit' => $annualDepositAmount,
                'interestMade' => $interestMade,
                'savedSoFar' => $savedSoFar,
                'balance' => $netWorth,
            ];
        }

        return $schedule;
    }

}
