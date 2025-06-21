@extends(Auth::user() ? 'layouts.app' : 'layouts.guest') {{-- Base layout --}}

@section('content')
{{-- This div will be where the Vue application/components for showing a single gallery are mounted --}}
{{-- Similar to index.blade.php, this is a host for the Vue component if not using full Inertia rendering --}}

<div id="gallery-app-show"
     data-gallery-id="{{ $galleryId }}"
     data-gallery-name="{{ $galleryName }}"
     data-user="{{ json_encode($user ?? null) }}">

    <p>Loading gallery details for: {{ $galleryName }}...</p> {{-- Placeholder content --}}

    {{-- The actual rendering will be handled by resources/js/Pages/Gallery/Show.vue --}}
</div>

<div class="container mt-4">
    <p><a href="{{ route('gallery.index') }}">Back to Galleries List</a></p>
</div>
@endsection
