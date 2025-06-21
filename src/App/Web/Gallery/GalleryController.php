<?php

namespace App\Web\Gallery;

use App\Support\Controllers\Controller; // Correct base controller
use Domain\Galleries\Actions\GetUserGalleriesAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GalleryController extends Controller
{
    public function __invoke(Request $request, GetUserGalleriesAction $getUserGalleriesAction)
    {
        // Authentication will be handled by middleware specified in the route file.
        // If Auth::user() is null here, it means the middleware was not applied or failed,
        // or the user is genuinely not logged in.
        $user = Auth::user(); // Auth is handled by middleware

        // Pass initial galleries data as props to the Inertia page
        // This avoids an immediate API call on page load for the initial set.
        $galleries = [];
        if ($user) {
            $galleries = $getUserGalleriesAction->execute($user);
        }

        // Assuming Inertia is set up
        // return \Inertia\Inertia::render('Gallery/Index', ['initialGalleries' => $galleries, 'user' => $user]);

        // If not using Inertia directly or it's a Blade view that mounts Vue:
        return view('gallery.index', ['initialGalleries' => $galleries, 'user' => $user]);
    }

    // Add showGalleryPage method for Inertia
    public function showGalleryPage(Gallery $gallery) // Route model binding
    {
        $user = Auth::user();
        // Basic authorization: check if user owns the gallery
        // More complex logic might involve GalleryPolicy
        if (!$user || $gallery->user_id !== $user->id) {
            // Or redirect to a generic error page / gallery index with an error
            abort(403, 'You do not have access to this gallery.');
        }

        // return \Inertia\Inertia::render('Gallery/Show', ['galleryId' => $gallery->id, 'galleryName' => $gallery->name]);
        // Or pass the full gallery object if it's not too large and doesn't expose sensitive data not needed by frontend.
        // For API-first approach, galleryId is usually enough, and Vue component fetches details.
        // Let's pass some basic info and let Vue fetch details if more is needed.
        return view('gallery.show_page', ['galleryId' => $gallery->id, 'galleryName' => $gallery->name, 'user' => $user]);
    }

    // Placeholder for future methods like store, show, etc.
    // public function store(Request $request, \Domain\Galleries\Actions\CreateGalleryAction $createGalleryAction)
    // {
    //     // $user = Auth::user();
    //     // $galleryData = \Domain\Galleries\DataTransferObjects\GalleryData::from($request->all());
    //     // $gallery = $createGalleryAction->execute($user, $galleryData);
    //     // return redirect()->route('gallery.show', $gallery); // Assuming a gallery.show route
    // }

    public function listMyGooglePhotosAlbums(Request $request)
    {
        $user = Auth::user();
        if (empty($user->google_photos_access_token)) {
            return redirect()->route('auth.google.redirect')->with('info', 'Please link your Google Photos account first.');
        }

        try {
            // GooglePhotosService should be injected or resolved from container for better practice
            // $googlePhotosService = new \Domain\Galleries\Services\GooglePhotosService($user);
            $googlePhotosService = resolve(\Domain\Galleries\Services\GooglePhotosService::class, ['user' => $user]);
            $albums = $googlePhotosService->listAlbums(['pageSize' => 10]); // Small page size for testing

            // Pass albums to a view
            return view('gallery.google_albums', ['albums' => $albums, 'user' => $user]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to fetch Google Photos albums for user ' . $user->id . ': ' . $e->getMessage());
            return redirect()->route('gallery.index')->with('error', 'Could not fetch Google Photos albums: ' . $e->getMessage());
        }
    }
}
// Make sure Domain\Galleries\Models\Gallery is imported if not already.
// use Domain\Galleries\Models\Gallery;
