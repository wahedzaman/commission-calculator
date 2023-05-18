<?php

declare(strict_types=1);

namespace APP\CommissionCalculator\Model;

class Transaction
{
    public string $operation_date;
    public int $user_id;
    public string $user_type;
    public string $operation_type;
    public float $operation_amount;
    public String $operation_currency;

    public function __construct(string $operation_date, int $user_id, string $user_type, string $operation_type, float $operation_amount, string $operation_currency)
    {
        $this->operation_date = $operation_date;
        $this->user_id = $user_id;
        $this->user_type = $user_type;
        $this->operation_type = $operation_type;
        $this->operation_amount = $operation_amount;
        $this->operation_currency = $operation_currency;
    }
}
