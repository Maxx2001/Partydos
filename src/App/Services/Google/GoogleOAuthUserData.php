<?php

namespace App\Services\Google;

use Carbon\Carbon;

class GoogleOAuthUserData
{
    public function __construct(
        public string $googleUserId,
        public ?string $email,
        public string $accessToken,
        public ?string $refreshToken,
        public ?Carbon $expiresAt,
    ) {
    }
}
