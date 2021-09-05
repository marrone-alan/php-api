<?php

namespace App\Services;

use App\Models\Account;
use DB;

class WithdrawService
{
    private $origin;
    private $amount;
    CONST MIN_AMOUNT = 1;

    /**
     * Create a new WithdrawService instance.
     * 
     * @param string $origin
     * @param int $amount
     *
     * @return void
     */
    public function __construct(string $origin, int $amount)
    {
        if ($amount < self::MIN_AMOUNT) {
            throw new \Exception('The amount must be at least ' . self::MIN_AMOUNT . '.');
        }

        $this->origin = $origin;
        $this->amount = $amount;
    }

    /**
     * execute withdraw transaction
     * 
     * @return array
     */
    public function exec(): array
    {
        $returnData = [];
        $account = Account::findOrFail($this->origin);

        $this->checkBalance($account);

        DB::beginTransaction();
        try {
            $account->balance = $this->getTotalAmount($account->balance, $this->amount);
            $account->save();
    
            DB::commit();
        } catch (\Error $e) {
            DB::rollback();
            throw new \Exception('DB Transaction error');
        }
        
        $returnData = [
            'origin' => [
                'id' => $account->id,
                'balance' => $account->balance,
            ]
        ];

        return $returnData;
    }

    /**
     * Calculates new balance
     * 
     * @param int $currentValue
     * @param int $withdrawAmount
     * 
     * @return int
     */
    private function getTotalAmount(int $currentBalance, int $withdrawAmount): int
    {
        return $currentBalance - $withdrawAmount;
    }

    /**
     * check if account has enough balance
     * 
     * @param Account $account
     * 
     * @return void
     */
    private function checkBalance(Account $account)
    {
        if ($account->balance < $this->amount) {
            throw new \Exception('insufficient balance.');
        }
    }
}
