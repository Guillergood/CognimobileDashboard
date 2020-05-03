<?php

namespace App\Http\Requests;

use App\Result;
use Illuminate\Foundation\Http\FormRequest;

class StoreResultRequest extends FormRequest
{
    public function authorize()
    {
        return \Gate::allows('result_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
            ],
        ];
    }
}
