@extends(Auth::user() ? 'layouts.app' : 'layouts.guest') {{-- Base layout --}}

@section('content')
{{-- This div will be where the Vue application/components for the gallery index are mounted --}}
{{-- For Inertia, this file might not even be used directly if pages are full JS components --}}
{{-- Or it could be the root Blade file that Inertia uses via @inertia directive --}}

<div id="gallery-app-index"
     data-initial-galleries="{{ json_encode($initialGalleries ?? []) }}"
     data-user="{{ json_encode($user ?? null) }}">

    {{-- If not using Inertia's automatic page component rendering, Vue would mount here. --}}
    {{-- For example, <gallery-index-page :initial-galleries="{{ json_encode($initialGalleries ?? []) }}"></gallery-index-page> --}}

    <p>Loading gallery...</p> {{-- Placeholder content --}}

    {{-- The actual rendering will be handled by resources/js/Pages/Gallery/Index.vue --}}
    {{-- If using Inertia, the Gallery/Index.vue component will replace this page's body. --}}
    {{-- If using Vue without Inertia, you'd have a script here to mount your Vue app/component. --}}

</div>

{{-- Example of how to provide a link to the Google Photos albums page --}}
@if(Auth::check())
<div class="container mt-4">
    <p><a href="{{ route('gallery.google_albums') }}">View Google Photos Albums</a></p>
    <p><a href="{{ route('auth.google.redirect') }}">Link/Re-link Google Account</a></p>
</div>
@endif

@endsection

{{-- If this is the main Inertia host (e.g. app.blade.php), it would have @inertia --}}
{{-- If this is just a specific Blade view for a non-Inertia Vue app, you might load Vue scripts here or in the layout --}}
