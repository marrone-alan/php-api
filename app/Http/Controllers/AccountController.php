<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Requests\BalanceGetRequest;
use App\Services\AccountService;

class AccountController extends Controller
{
    /**
     * Create a new AccountController instance.
     *
     * @return void
     */
    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * Get balance from an account
     */
    public function balance(BalanceGetRequest $request)
    {   
        $response = null;

        try {
            $response = $this->accountService->getBalance($request->account_id);
        } catch (\Throwable $th) {
            return response()->json(0, Response::HTTP_NOT_FOUND);
        }

        return response()->json($response, Response::HTTP_OK);
    }
}
