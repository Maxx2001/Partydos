<?php

namespace App\Web\Polls\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePollRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'question' => ['sometimes', 'required', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:240'],
            'vote_mode' => ['sometimes', 'required', 'in:single,multiple'],
            'guests_can_add_options' => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('guests_can_add_options')) {
            $this->merge([
                'guests_can_add_options' => $this->boolean('guests_can_add_options'),
            ]);
        }
    }
}
