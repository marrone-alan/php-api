<?php

namespace App\Services;

use App\Services\WithdrawService;
use App\Services\DepositService;
use DB;

class TransferService
{
    private $origin;
    private $destination;
    private $amount;
    CONST MIN_AMOUNT = 1;

    /**
     * Create a new TransferService instance.
     * 
     * @param string $origin
     * @param string $destination
     * @param int $amount
     *
     * @return void
     */
    public function __construct(string $origin, string $destination, int $amount)
    {
        if ($amount < self::MIN_AMOUNT) {
            throw new \Exception('The amount must be at least ' . self::MIN_AMOUNT . '.');
        }
        
        $this->origin = $origin;
        $this->destination = $destination;
        $this->amount = $amount;
    }

    /**
     * execute transfer transaction
     * 
     * @return array
     */
    public function exec(): array
    {
        $withdrawService = new WithdrawService($this->origin, $this->amount);
        $depositService = new DepositService($this->destination, $this->amount);
        
        DB::beginTransaction();
        try {
            $withdrawResponse = $withdrawService->exec($this->destination);
            $depositService = $depositService->exec($this->destination);
            DB::commit();
        } catch (\Error $e) {
            DB::rollback();
            throw new \Exception('DB Transaction error');
        }

        $returnData = array_merge($withdrawResponse, $depositService);

        return $returnData;
    }
}
