<?php

namespace App\Services;

use App\Enums\WorkShift;

class WorkCostCalculator
{
    public function calculate(
        int $dailyRate,
        float $laborUnits,
        WorkShift $workShift,
        float $overtimeHours,
        int $highwayCost,
        int $parkingCost,
        int $otherCost,
    ): array {
        $nightMultiplier = $workShift === WorkShift::Night
            ? 1.25
            : 1.00;

        $overtimeRate = $workShift === WorkShift::Night
            ? 1250
            : 1000;

        $baseLaborCost = (int) round(
            $dailyRate
            * $laborUnits
            * $nightMultiplier
        );

        $overtimeCost = (int) round(
            $overtimeHours
            * $overtimeRate
        );

        $laborCost = $baseLaborCost + $overtimeCost;

        $expenseTotal =
            $highwayCost
            + $parkingCost
            + $otherCost;

        return [
            'daily_rate_snapshot' => $dailyRate,
            'night_multiplier_snapshot' => $nightMultiplier,
            'overtime_rate_snapshot' => $overtimeRate,

            'base_labor_cost' => $baseLaborCost,
            'overtime_cost' => $overtimeCost,
            'labor_cost' => $laborCost,

            'expense_total' => $expenseTotal,
            'total_cost' => $laborCost + $expenseTotal,
        ];
    }
}
