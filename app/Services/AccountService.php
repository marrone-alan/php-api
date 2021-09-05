<?php

namespace App\Services;

use App\Models\Account;

class AccountService
{
    /**
     * Fetch an account balance
     */
    public function getBalance(int $account_id)
    {
        $accountModel = new Account();

        $account = $accountModel->findOrFail($account_id);
        
        return $account->balance;
    }
}
