<x-guest-layout :page-title="config('app.name') . ' - Home'">
    @push('social-meta')
        <meta name="description" content="LaravelCasts is the leading learning platform for Laravel developers.">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ route('pages.home') }}">
        <meta property="og:title" content="LaravelCasts">
        <meta property="og:description" content="LaravelCasts is the leading learning platform for Laravel developers.">
        <meta property="og:image" content="{{ asset('images/social.png') }}">

        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image">
    @endpush
    @guest()
    <a href="{{ route('login') }}">Login</a>
    @else
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Log out</button>
    </form>
    @endguest

    @foreach ($courses as $course)
    <a href="{{ route('pages.course-details', $course) }}">
        <h2>{{ $course->title }}</h2>
    </a>
    <p>{{ $course->description }}</p>
    @endforeach
</x-guest-layout>
