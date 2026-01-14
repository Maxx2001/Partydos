<?php

namespace App\Web\Profile\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuestbookEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'max:280'],
        ];
    }
}
