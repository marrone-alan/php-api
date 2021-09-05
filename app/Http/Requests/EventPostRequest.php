<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Services\DepositService;

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
            'amount' => 'required|integer|min:'.DepositService::MIN_AMOUNT,
        ];

        switch (Request::input('type')) {
            case 'deposit':
                $rules['destination'] = 'required|string';
                break;
            case 'withdraw':
                break;
            case 'transfer':
                break;
            default:
                break;
        }

        return $rules;
    }
}
