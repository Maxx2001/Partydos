@extends(Auth::user() ? 'layouts.app' : 'layouts.guest') {{-- Assuming app/guest layouts --}}

@section('content')
<div class="container">
    <h1>Your Google Photos Albums</h1>

    @if(isset($albums) && count($albums) > 0)
        <p>Found {{ count($albums) }} album(s):</p>
        <ul>
            @foreach($albums as $album)
                <li>
                    <strong>{{ $album->getTitle() }}</strong> (ID: {{ $album->getId() }})
                    @if($album->getCoverPhotoBaseUrl())
                        <br>
                        <img src="{{ $album->getCoverPhotoBaseUrl() }}=w100-h100-c" alt="Cover photo for {{ $album->getTitle() }}" width="50" height="50">
                    @endif
                    <p>{{ $album->getMediaItemsCount() }} items</p>
                    @if($album->getProductUrl())
                        <a href="{{ $album->getProductUrl() }}" target="_blank">View on Google Photos</a>
                    @endif
                </li>
            @endforeach
        </ul>
    @elseif(isset($albums))
        <p>No albums found in your Google Photos account, or there was an issue fetching them.</p>
    @else
        <p>Could not load album data.</p>
    @endif

    <p><a href="{{ route('gallery.index') }}">Back to Galleries</a></p>
    <p><a href="{{ route('auth.google.redirect') }}">Re-link Google Account</a></p>

</div>
@endsection
