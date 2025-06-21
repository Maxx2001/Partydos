<?php

namespace App\Api\Me;

use App\Support\Controllers\Controller;
use Illuminate\Http\Request;
use Domain\Galleries\Models\GalleryInvitation;
use Illuminate\Http\JsonResponse; // For type hinting

class MyGalleryInvitationsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user(); // Authenticated user via auth:sanctum

        if (!$user) {
            // This should ideally not be reached if auth:sanctum is working
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $invitations = GalleryInvitation::where('invited_email', $user->email)
                                    ->with(['gallery', 'inviter']) // Eager load relations
                                    ->orderBy('created_at', 'desc')
                                    ->get();

        // You might want to transform this data using API Resources for consistency
        return response()->json($invitations);
    }
}
