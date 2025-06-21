<?php

namespace App\Web\Gallery;

use App\Support\Controllers\Controller;
use Domain\Galleries\Actions\AcceptGalleryInvitationAction;
use Domain\Galleries\Actions\RejectGalleryInvitationAction;
use Illuminate\Http\RedirectResponse; // For type hinting
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GalleryInvitationController extends Controller
{
    public function accept(AcceptGalleryInvitationAction $action, string $token): RedirectResponse
    {
        try {
            $invitation = $action->execute($token);
            // If the user is logged in and is the one invited, redirect to the specific gallery
            // This requires more logic to find the user by invited_email and log them in or associate.
            // For now, generic success.
            if (auth()->check() && auth()->user()->email === $invitation->invited_email) {
                 return redirect()->route('gallery.show', ['gallery' => $invitation->gallery_id])
                                 ->with('success', 'Invitation accepted! Welcome to the gallery.');
            }
            return redirect()->route('gallery.index')->with('success', 'Invitation accepted successfully!');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('gallery.index')->with('error', 'Invalid or expired invitation token.');
        } catch (\Exception $e) {
            // Generic error for other unexpected issues
            logger()->error('Error accepting invitation: ' . $e->getMessage());
            return redirect()->route('gallery.index')->with('error', 'An unexpected error occurred.');
        }
    }

    public function reject(RejectGalleryInvitationAction $action, string $token): RedirectResponse
    {
        try {
            $action->execute($token);
            return redirect()->route('gallery.index')->with('success', 'Invitation rejected.');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('gallery.index')->with('error', 'Invalid or expired invitation token.');
        } catch (\Exception $e) {
            logger()->error('Error rejecting invitation: ' . $e->getMessage());
            return redirect()->route('gallery.index')->with('error', 'An unexpected error occurred.');
        }
    }
}
