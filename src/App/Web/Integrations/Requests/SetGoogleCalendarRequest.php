<?php

namespace App\Web\Integrations\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetGoogleCalendarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /** @return array<string, array<int, string>> */
    public function rules(): array
    {
        return [
            'calendar_id' => ['required', 'string', 'max:255'],
        ];
    }
}
