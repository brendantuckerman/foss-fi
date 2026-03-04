<?php

namespace App\Service;


class ProjectionCalulator
{
    public function calculateIncomeDifference(int $income, int $expenditure): int
    {
        return ($income - $expenditure);
    }
}
