<?php

namespace App\Web\Profile\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'display_name' => ['required', 'string', 'max:40'],
            'tagline' => ['nullable', 'string', 'max:80'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'city' => ['nullable', 'string', 'max:60'],
            'birthdate' => ['nullable', 'date'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'tiktok_url' => ['nullable', 'url', 'max:255'],
            'spotify_url' => ['nullable', 'url', 'max:255'],
            'interests' => ['nullable', 'array', 'max:15'],
            'interests.*' => ['string', 'max:40'],
            'favorite_music' => ['nullable', 'array', 'max:15'],
            'favorite_music.*' => ['string', 'max:40'],
            'party_style_tags' => ['nullable', 'array', 'max:15'],
            'party_style_tags.*' => ['string', 'max:40'],
            'privacy_profile' => ['required', 'in:public,friends,private'],
            'privacy_guestbook' => ['required', 'in:public,friends,private'],
            'privacy_photos' => ['required', 'in:public,friends,private'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
        ];
    }
}
