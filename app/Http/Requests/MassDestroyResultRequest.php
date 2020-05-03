<?php

namespace App\Http\Requests;

use App\Result;
use Gate;
use Illuminate\Foundation\Http\FormRequest;

class MassDestroyResultRequest extends FormRequest
{
    public function authorize()
    {
        return abort_if(Gate::denies('result_delete'), 403, '403 Forbidden') ?? true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:results,id',
        ];
    }
}
