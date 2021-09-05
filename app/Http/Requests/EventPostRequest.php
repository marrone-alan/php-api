<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Services\DepositService;
use App\Services\WithdrawService;

class EventPostRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'type' => 'required|string',
        ];

        switch (Request::input('type')) {
            case 'deposit':
                $rules['destination'] = 'required|string';
                $rules['amount'] = 'required|integer|min:'.DepositService::MIN_AMOUNT;
                break;
            case 'withdraw':
                $rules['origin'] = 'required|string';
                $rules['amount'] = 'required|integer|min:'.WithdrawService::MIN_AMOUNT;
                break;
            case 'transfer':
                break;
            default:
                break;
        }

        return $rules;
    }
}
