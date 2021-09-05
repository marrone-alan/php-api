<?php

namespace App\Services;

use App\Services\AccountService;
use DB;

class DepositService
{
    private $destination;
    private $amount;
    CONST MIN_AMOUNT = 1;

    /**
     * Create a new DepositService instance.
     * 
     * @param string $destination
     * @param int $amount
     *
     * @return void
     */
    public function __construct(string $destination, int $amount)
    {
        if ($amount < self::MIN_AMOUNT) {
            throw new \Exception('The amount must be at least ' . self::MIN_AMOUNT . '.');
        }

        $this->destination = $destination;
        $this->amount = $amount;
    }

    /**
     * execute deposit transaction
     * 
     * @return array
     */
    public function exec(): array
    {
        $accountService = new AccountService();
        $returnData = [];

        DB::beginTransaction();
        try {
            $account = $accountService->fetchCreateAccount($this->destination);

            $account->balance = $this->getTotalAmount($account->balance, $this->amount);
            $account->save();
    
            DB::commit();
        } catch (\Error $e) {
            DB::rollback();
            throw new \Exception('DB Transaction error');
        }
        
        $returnData = [
            'destination' => [
                'id' => $account->id,
                'balance' => $account->balance,
            ]
        ];

        return $returnData;
    }

    /**
     * Calculates total amount to deposit
     * 
     * @param int $currentValue
     * @param int $depositAmount
     * 
     * @return int
     */
    private function getTotalAmount(int $currentBalance, int $depositAmount): int
    {
        return $currentBalance + $depositAmount;
    }
}
