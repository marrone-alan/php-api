<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class BalanceGetRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'account_id' => 'required|integer'
        ];
    }
}
