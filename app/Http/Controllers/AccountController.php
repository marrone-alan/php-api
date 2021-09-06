<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Requests\BalanceGetRequest;
use App\Http\Requests\EventPostRequest;
use App\Services\AccountService;
use App\Services\DepositService;
use App\Services\WithdrawService;
use App\Services\TransferService;

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
     * reset to the initial state
     */
    public function reset()
    {   
        $response = null;

        try {
            $response = $this->accountService->reset();
        } catch (\Throwable $th) {
            return response()->json(0, Response::HTTP_NOT_FOUND);
        }

        return response()->json($response, Response::HTTP_OK);
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

    /**
     * Account transactions
     */
    public function event(EventPostRequest $request)
    {   
        $response = null;

        switch ($request->type) {
            case 'deposit':
                try {
                    $depositService = new DepositService($request->destination, $request->amount);
                    $response = $depositService->exec();
                } catch (\Throwable $th) {
                    return response()->json(0, Response::HTTP_NOT_FOUND);
                }
                break;
            case 'withdraw':
                try {
                    $withdrawService = new WithdrawService($request->origin, $request->amount);
                    $response = $withdrawService->exec();
                } catch (\Throwable $th) {
                    return response()->json(0, Response::HTTP_NOT_FOUND);
                }
                break;
            case 'transfer':
                try {
                    $transferService = new TransferService(
                        $request->origin,
                        $request->destination,
                        $request->amount
                    );

                    $response = $transferService->exec();
                } catch (\Throwable $th) {
                    return response()->json(0, Response::HTTP_NOT_FOUND);
                }
                break;
            default:
                return response()->json('invalid type', Response::HTTP_NOT_FOUND);
                break;
        }

        return response()->json($response, Response::HTTP_CREATED);
    }
}
