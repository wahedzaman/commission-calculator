<?php

declare(strict_types=1);

namespace APP\CommissionCalculator\Utility;

class DateUtility
{
    public function isFromSameWeek(string $dateString1, string $dateString2): bool
    {
        $date1 = \DateTime::createFromFormat('Y-m-d', $dateString1);
        $date2 = \DateTime::createFromFormat('Y-m-d', $dateString2);

        $week1 = $date1->format('W');
        $year1 = $date1->format('o');
        $week2 = $date2->format('W');
        $year2 = $date2->format('o');

        return $week1 === $week2 && $year1 === $year2;
    }
}
