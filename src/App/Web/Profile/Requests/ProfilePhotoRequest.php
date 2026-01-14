<?php

namespace App\Web\Profile\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfilePhotoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
            'caption' => ['nullable', 'string', 'max:120'],
        ];
    }
}
