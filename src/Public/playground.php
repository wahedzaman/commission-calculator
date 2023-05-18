<?php

declare(strict_types=1);

// THIS FILE IS KEPT INTENTIONALLY.
// THIS IS THE ORGINAL FILE OR WE CAN CALL
// IT THE PLAYGROUND/SCRATCHPAD, WHERE THE INITIAL PROTOTYPE
// AND EARLY ALGORITHMIC THOUGHT PROCESSES WERE MADE

require __DIR__.'/../../vendor/autoload.php';

use APP\CommissionCalculator\Enums\TransactionType;
use APP\CommissionCalculator\Enums\UserType;
use APP\CommissionCalculator\Model\Transaction;

if (isset($argv[1])) {
    // Get the file name from the argument
    $file = $argv[1];
    // Get the file extension
    $extension = pathinfo($file, PATHINFO_EXTENSION);
    // Check if the file extension is csv
    if ($extension === 'csv') {
        // Check if the file exists and is readable
        if (is_readable($file)) {
            // Open the file in read mode
            $csvFile = fopen($file, 'r');
            $transactions = [];
            $transactionRecords = [];
            $commissions = [];
            $exchangeRates = callCurrencyExchangeAPI();

            while (($line = fgetcsv($csvFile)) !== false) {
                // Get the values from the line
                list($operation_date, $user_id, $user_type, $operation_type, $operation_amount, $operation_currency) = $line;
                // Create a new Transaction object with the values
                $transaction = new Transaction($operation_date, (int) $user_id, $user_type, $operation_type, (float) $operation_amount, $operation_currency);
                // Add the transaction to the array
                $transactions[] = $transaction;
                // todo:: move out further processing out of this loop. (new foreach for transactions)
            }
            fclose($csvFile);
            for ($index = 0; $index < count($transactions); ++$index) {
                $transaction = $transactions[$index];
                $euroValue = $transaction->operation_amount;
                echo "calculating transaction for record no $index :";

                $dateTime = new DateTime($transaction->operation_date);

                // $intlCalendar = IntlCalendar::fromDateTime($dateTime, null);
                // echo ' weeeeek: ' . $intlCalendar->get(IntlCalendar::FIELD_YEAR_WOY) . '  '; // integer 39

                // $transactionRecords[22] = [45 => ["count" => 9, "lastamount" => 9, "total" => 9]];
                // ($transactionRecords[22][45]["couny"]++);
                // ($transactionRecords[22][45]["couny"] += 887);

                if ($transaction->operation_currency !== 'EUR') {
                    $euroValue = fromOtherToEuro($transaction->operation_amount, $exchangeRates[$transaction->operation_currency]);
                }

                if ($transaction->operation_type === TransactionType::deposit->name) {
                    $commissions[] = number_format($transaction->operation_amount * (0.03 / 100), 2);
                } else {
                    if ($transaction->user_type === UserType::private->name) {
                        // $weekNumber = date("W", strtotime($transaction->operation_date));
                        // $cal = IntlCalendar::createInstance();
                        // $cal->setFirstDayOfWeek(IntlCalendar::DOW_MONDAY);
                        // echo "week number $weekNumber ";
                        // if (isset($transactionRecords[$transaction->user_id]) && isset($transactionRecords[$transaction->user_id][$year . '_' . $weekNumber])) {
                        // if (isset($transactionRecords[$transaction->user_id])) {
                        if (isset($transactionRecords[$transaction->user_id]) && isFromSameWeek($transaction->operation_date, $transactionRecords[$transaction->user_id]['last_txn_date'])) {
                            // current transaction date last transaction date is in same week
                            // calculate commission fee
                            // $transactionRecords[$transaction->user_id] [] =; // update
                            // else
                            // calculate commission fee
                            // add new transaction in to the record array
                            // $transactionRecords[$transaction->user_id][] =;
                            // if (isFromSameWeek($transaction->operation_date, $transactionRecords[$transaction->user_id]["last_txn_date"])) {
                            $transactionRecords[$transaction->user_id]['last_txn_date'] = $transaction->operation_date;
                            ++$transactionRecords[$transaction->user_id]['txn_count'];
                            // $transactionRecords[$transaction->user_id]["total_amount"] += $euroValue;
                            $transactionRecords[$transaction->user_id]['last_amount'] = $euroValue;
                        // echo ' same week ';
                        // print_r($transactionRecords);
                        // } else {
                        // $transactionRecords[$transaction->user_id] = ["last_txn_date" => $transaction->operation_date, "txn_count" => 1, "total_amount" => $euroValue, "last_amount" => $euroValue];
                        // $transactionRecords[$transaction->user_id] = ["last_txn_date" => $transaction->operation_date, "txn_count" => 1, "total_amount" => 0, "last_amount" => $euroValue];
                        // echo ' new week ';
                        // }

                        // assuming the provided csv is sorted by date, if not then below part needs to be commented
                        // $recordsForThisUser = end($transactionRecords[$transaction->user_id]);
                        // ,,$recordsForThisUser = end($transactionRecords[$transaction->user_id][array_key_last($transactionRecords[$transaction->user_id])]);
                        // if (isFromSameWeek($transaction->operation_date, array_key_last($transactionRecords[$transaction->user_id]))) {
                        // ,,if (isFromSameWeek($transaction->operation_date, $recordsForThisUser["last_txn_date"])) {
                        // $transactionRecords[$transaction->user_id][$transaction->operation_date]["txn_count"]++;
                        // $transactionRecords[$transaction->user_id][$transaction->operation_date]["total_amount"] += $euroValue;
                        // $transactionRecords[$transaction->user_id][$transaction->operation_date]["last_amount"] = $euroValue;

                        //     $transactionRecords[$transaction->user_id]["last_txn_date"] = $transaction->operation_date;
                        //     $transactionRecords[$transaction->user_id]["txn_count"]++;
                        //     $transactionRecords[$transaction->user_id]["total_amount"] += $euroValue;
                        //     $transactionRecords[$transaction->user_id]["last_amount"] = $euroValue;
                        // } else {
                        //     $transactionRecords[$transaction->user_id] = [
                        //         "last_txn_date" => $transaction->operation_date,
                        //         "txn_count" => 1, "total_amount" => $euroValue, "last_amount" => $euroValue
                        //     ];
                        // }..

                        // assuming the provided csv is sorted by date, if not then below part needs to be uncommneted
                            /*  $recordsForThisUser = $transactionRecords[$transaction->user_id];
                            foreach ($recordsForThisUser as $key => $transactionRecord) {
                                if (isFromSameWeek($transaction->operation_date, $key)) {
                                    break;
                                }
                            } */

                        // already have records for this user in the same week
                        // update user transaction recrod
                        // echo ' same week ';
                        // $transactionRecords[$transaction->user_id][$weekNumber]["txn_count"]++;
                        // $transactionRecords[$transaction->user_id][$weekNumber]["total_amount"] += $euroValue;
                        // $transactionRecords[$transaction->user_id][$weekNumber]["last_amount"] = $euroValue;

                        // $transactionRecords[$transaction->user_id][$year . '_' . $weekNumber]["txn_count"]++;
                        // $transactionRecords[$transaction->user_id][$year . '_' . $weekNumber]["total_amount"] += $euroValue;
                        // $transactionRecords[$transaction->user_id][$year . '_' . $weekNumber]["last_amount"] = $euroValue;
                        } else {
                            // $transactionRecords[$transaction->user_id] = ["last_txn_date" => $transaction->operation_date, "txn_count" => 1, "total_amount" => $euroValue, "last_amount" => $euroValue];
                            $transactionRecords[$transaction->user_id] = ['last_txn_date' => $transaction->operation_date, 'txn_count' => 1, 'total_amount' => 0, 'last_amount' => $euroValue];
                        }
                        // else {
                        //     echo ' new week ';
                        //     //no previous record for this user for this week
                        //     // $transactionRecords[$transaction->user_id][$year . '_' . $weekNumber] = ["txn_count" => 1, "total_amount" => $euroValue, "last_amount" => $euroValue];
                        // }
                        // $commission = calculateCommission($transactionRecords[$transaction->user_id][$year . '_' . $weekNumber]);
                        // print_r($transactionRecords);
                        // todo:: return back to original currency format
                        $commission = calculateCommission($transactionRecords[$transaction->user_id]);
                        if ($transaction->operation_currency !== 'EUR') {
                            $commission = fromEuroToOther($commission, $exchangeRates[$transaction->operation_currency]);
                        }
                        $commissions[] = number_format($commission, 2);
                    } else {
                        $commissions[] = number_format($euroValue * (0.5 / 100), 2);
                    }
                }
                echo "$commissions[$index]\n";
            }
        // print_r($transactionRecords);
        // var_dump($commissions);
        // foreach ($commissions as $commission){
        //     echo "$commission \n";
        // }
        // var_dump($transactions);
        } else {
            echo 'provided file could not be found';
        }
    } else {
        echo 'only csv file type is accepted';
    }
} else {
    echo "csv file is not provided. Please provide a valid csv input file \n";
    echo 'run > php index.php input.csv';
}

function isFromSameWeek(string $dateString1, string $dateString2): bool
{
    // echo "\nabout to calculate $dateString1 and $dateString2 \n";
    $date1 = DateTime::createFromFormat('Y-m-d', $dateString1);
    $date2 = DateTime::createFromFormat('Y-m-d', $dateString2);

    $week1 = $date1->format('W');
    $year1 = $date1->format('o');
    $week2 = $date2->format('W');
    $year2 = $date2->format('o');

    /*  if ($week1 == $week2 && $year1 == $year2) {
        echo " $dateString1 and $dateString2 The dates are in the same week. \n";
        return true;
    } else {
        echo " $dateString1 and $dateString2  The dates are not in the same week. \n";
        return false;
    } */

    return $week1 === $week2 && $year1 === $year2;
}

function calculateCommission(array &$transactionRecord): float
{
    // var_dump($transactionRecord);
    $commissionableAmount = 0;
    if ($transactionRecord['txn_count'] > 3) {
        $commissionableAmount = $transactionRecord['last_amount'];
    } else {
        if ($transactionRecord['total_amount'] > 0) {
            if ($transactionRecord['total_amount'] > 1000) {
                $commissionableAmount = $transactionRecord['last_amount'];
            } else {
                $commissionableAmount = $transactionRecord['last_amount'] - (1000 - $transactionRecord['total_amount']);
            }
        } else {
            if ($transactionRecord['last_amount'] > 1000) {
                $commissionableAmount = $transactionRecord['last_amount'] - 1000;
            }
        }
    }
    // if last_amount > 1000
    // return last_amount ;
    // else
    // if total_amount > 1000
    // return  $transactionRecord["last_amount"] - (1000 -( $transactionRecord["total_amount"]- $transactionRecord["last_amount"])); ;
    // else total_amount <1000
    // if ($transactionRecord["total_amount"] > 1000) {
    //     $commissionableAmount = $transactionRecord["last_amount"];
    // } else {
    //     if($transactionRecord["total_amount"] > 1000){

    //     }
    // }

    // if ($transactionRecord["last_amount"] > 1000) {
    //     $commissionableAmount = $transactionRecord["last_amount"];
    // } else {
    //     if ($transactionRecord["total_amount"] > 1000) {
    //         $commissionableAmount = $transactionRecord["last_amount"];
    //     } else {
    //         $commissionableAmount = $transactionRecord["last_amount"] - (1000 - $transactionRecord["total_amount"]);
    //     }
    // }
    // }
    /*  else if (($transactionRecord["txn_count"] <= 3) && ($transactionRecord["last_amount"] > 1000)) {
        $commissionableAmount = $transactionRecord["total_amount"] - 1000;
    } else if (($transactionRecord["txn_count"] <= 3) && ($transactionRecord["total_amount"] > 1000)) {
        $commissionableAmount = $transactionRecord["last_amount"] - 1000;
    } */
    $transactionRecord['total_amount'] += $transactionRecord['last_amount'];

    return $commissionableAmount * (0.3 / 100);
}
// 22,923.5159107577  130.869977
// 21,923.5159107577 -1000
// 65.7705477323 %
// 8,607.3900690035 130.869977,

// 23,160.6577626805 129.53
// 22,160.6577626805 -1000
// 66.481973288  %
// 8,611.41   129.53

function fromOtherToEuro(float $amount, float $exchangeRate): float
{
    // echo "\nconverted to euro value from $exchangeRate\n";
    return $amount / $exchangeRate;
}

function fromEuroToOther(float $amount, float $exchangeRate): float
{
    // echo "\nconverted from euro value to $exchangeRate for $amount\n";
    return $amount * $exchangeRate;
}

function callCurrencyExchangeAPI(): array
{
    // call api to prepare exchange rates
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://developers.paysera.com/tasks/api/currency-exchange-rates');
    curl_setopt($ch, CURLOPT_HTTPGET, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($response, true);
    $exchangeRates = $data['rates'];
    // print_r($exchangeRates);
    return $exchangeRates;
}
