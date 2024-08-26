<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRegisterGuestRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'  => 'required|string',
            'email' => 'required|email',
        ];
    }
}
