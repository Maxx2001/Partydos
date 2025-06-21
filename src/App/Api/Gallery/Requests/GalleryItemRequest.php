<?php

namespace App\Api\Gallery\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Domain\Galleries\DataTransferObjects\GalleryItemData;

class GalleryItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Check if the authenticated user owns the gallery the item is being added to.
        // The Gallery model instance is available via $this->route('gallery')
        $gallery = $this->route('gallery');
        return Auth::check() && $gallery && $gallery->user_id == Auth::id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'external_photo_id' => 'required|string|max:255',
            'type' => 'required|string|max:50', // e.g., 'photo', 'album_cover'
            'metadata' => 'nullable|array',
        ];
    }

    /**
     * Get the DTO for the request.
     */
    public function toDto(): GalleryItemData
    {
        return new GalleryItemData(
            external_photo_id: $this->validated('external_photo_id'),
            type: $this->validated('type'),
            metadata: $this->validated('metadata')
        );
    }
}
