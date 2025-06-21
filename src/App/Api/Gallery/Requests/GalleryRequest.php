<?php

namespace App\Api\Gallery\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Domain\Galleries\DataTransferObjects\GalleryData;

class GalleryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Assuming any authenticated user can create a gallery for themselves
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
        ];
    }

    /**
     * Get the DTO for the request.
     */
    public function toDto(): GalleryData
    {
        return new GalleryData(
            name: $this->validated('name'),
            description: $this->validated('description')
        );
    }
}
