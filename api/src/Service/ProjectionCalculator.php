<?php

namespace App\Service;


class ProjectionCalculator
{

    public function calculateIncomeDifference(int $income, int $expenditure): int
    {
        return ($income - $expenditure);
    }
}
