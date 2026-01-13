<?php

namespace App\Web\Polls\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePollRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'question' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:240'],
            'vote_mode' => ['required', 'in:single,multiple'],
            'guests_can_add_options' => ['boolean'],
            'options' => ['required', 'array', 'min:2', 'max:20'],
            'options.*' => ['required', 'string', 'max:140'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'guests_can_add_options' => $this->boolean('guests_can_add_options'),
        ]);
    }
}
