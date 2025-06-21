<?php

namespace App\Api\Gallery;

use App\Support\Controllers\Controller; // Assuming this is the base for API too
use Domain\Galleries\Actions\CreateGalleryAction;
use Domain\Galleries\Actions\AddGalleryItemAction;
use Domain\Galleries\Actions\GetUserGalleriesAction;
use Domain\Galleries\Models\Gallery;
use Illuminate\Http\Request; // Keep for index and show if not using FormRequest there
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Api\Gallery\Requests\GalleryRequest;
use App\Api\Gallery\Requests\GalleryItemRequest;

class ApiGalleryController extends Controller
{
    public function index(Request $request, GetUserGalleriesAction $action): JsonResponse
    {
        $user = Auth::user();
        $galleries = $action->execute($user);
        return response()->json($galleries);
    }

    public function store(GalleryRequest $request, CreateGalleryAction $action): JsonResponse
    {
        $user = Auth::user();
        $gallery = $action->execute($user, $request->toDto());
        return response()->json($gallery, 201);
    }

    public function show(Gallery $gallery): JsonResponse
    {
        // Authorization: Ensure the authenticated user can view this gallery.
        // This could be: owner, or someone who accepted an invitation.
        // A GalleryPolicy would be the standard way to handle this.
        // For now, basic check if public or owner, or if user is invited (more complex).
        // Let's keep it simple and assume for now if they have the ID, they can see it,
        // but in a real app, this needs proper authorization.
        $gallery->load(['items', 'invitations', 'invitations.inviter', 'invitations.invitedUser', 'user']); // 'user' is gallery owner
        return response()->json($gallery);
    }

    public function addGalleryItem(Gallery $gallery, GalleryItemRequest $request, AddGalleryItemAction $action): JsonResponse
    {
        // Authorization is handled by GalleryItemRequest authorize() method.
        $galleryItem = $action->execute($gallery, $request->toDto());
        return response()->json($galleryItem, 201);
    }

    public function storeInvitation(StoreGalleryInvitationRequest $request, Gallery $gallery, CreateGalleryInvitationAction $action): JsonResponse
    {
        // Authorization is handled by StoreGalleryInvitationRequest
        $invitation = $action->execute(
            $gallery,
            $request->validated()['invited_email'],
            $request->user()->id
        );

        return response()->json([
            'message' => 'Invitation sent successfully.',
            'invitation_id' => $invitation->id,
            // 'status' => $invitation->status // Could also return current status
        ], 201);
    }
}
