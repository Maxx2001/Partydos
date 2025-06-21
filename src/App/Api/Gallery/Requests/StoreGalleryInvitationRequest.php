<?php

namespace App\Api\Gallery\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Domain\Galleries\Models\Gallery; // For route model binding type hint
use Illuminate\Support\Facades\Auth; // For Auth::user() or $this->user()

class StoreGalleryInvitationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        /** @var Gallery $gallery */
        $gallery = $this->route('gallery'); // Relies on route model binding

        // Ensure gallery exists and the authenticated user owns it.
        return $gallery && $this->user() && $gallery->user_id === $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'invited_email' => 'required|email|max:255',
            // You might also want to add a rule to prevent inviting oneself:
            // 'invited_email' => 'required|email|max:255|not_in:' . $this->user()->email,
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'invited_email.not_in' => 'You cannot invite yourself to your own gallery.',
        ];
    }
}
