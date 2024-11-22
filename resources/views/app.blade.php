<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Meta Tags -->
        <meta name="description" content="{{ $description ?? 'Effortlessly plan events, send personalized invites, manage attendees, and organize your schedule with dynamic polls with Partydos!' }}">
        <meta property="og:title" content="{{ $ogTitle ?? 'Partydos.nl' }}">
        <meta property="og:description" content="{{ $ogDescription ?? 'Effortlessly plan events, send personalized invites, manage attendees, and organize your schedule with dynamic polls Partydos!' }}">

        <!-- Use the asset() helper to correctly reference the image in the public directory -->
        <meta property="og:image" content="{{ asset('Partydos.png') }}">
        <meta property="og:url" content="{{ url()->current() }}">

        <!-- Routes, Scripts, and Inertia -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
    @inertia
    </body>
</html>
