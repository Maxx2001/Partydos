<?php

namespace App\Web\Polls\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePollOptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'text' => ['required', 'string', 'max:140'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('text')) {
            $this->merge([
                'text' => trim((string) $this->input('text')),
            ]);
        }
    }
}
