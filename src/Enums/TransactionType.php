<?php

declare(strict_types=1);

namespace APP\CommissionCalculator\Enums;

enum TransactionType
{
    case withdraw;
    case deposit;
}
