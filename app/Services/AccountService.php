<?php

namespace App\Services;

use App\Models\Account;

class AccountService
{

    /**
     * reset account table
     * 
     * @return void
     */
    public function reset()
    {
        $accountModel = new Account();
        $accountModel->truncate();

        return 'OK';
    }

    /**
     * Fetch an account balance
     * 
     * @param string $account_id
     * 
     * @return int
     */
    public function getBalance(string $account_id): int
    {
        $accountModel = new Account();

        $account = $accountModel->findOrFail($account_id);
        
        return $account->balance;
    }

    /**
     * Fetch or create a new account
     * 
     * @param string $account_id
     * 
     * @return Account
     */
    public function fetchCreateAccount(string $id_account): Account
    {
        $accountModel = new Account();

        return $accountModel->firstOrCreate(['id' => $id_account]);
    }
}
