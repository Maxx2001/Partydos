<?php

namespace App\Http\Events\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title'         => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string'],
            'location'      => ['nullable', 'string'],
            'startDateTime' => ['required', 'date'],
            'endDateTime'   => ['nullable', 'date'],
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:255'],
        ];
    }
}
