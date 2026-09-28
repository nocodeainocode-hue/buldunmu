{{-- MOBİL UYGULAMA · uygulama başlığı (her ekranda ortak chrome) --}}
@php
    $apName = $directory?->name ?? config('app.name', 'Firma Rehberi');
    $apLetter = mb_substr(trim((string) $apName), 0, 1) ?: 'F';
    $apIsHome = request()->routeIs('home');
@endphp
<header class="ap-appbar">
    @if($apIsHome)
        <span class="ap-appbar__mark" aria-hidden="true">{{ mb_strtoupper($apLetter) }}</span>
        <a class="ap-appbar__brand" href="{{ route('home') }}"><span class="ap-appbar__name">{{ \Illuminate\Support\Str::limit($apName, 20) }}</span></a>
    @else
        <a class="ap-appbar__back" href="{{ url()->previous(route('home')) }}" aria-label="Geri">‹</a>
        <a class="ap-appbar__brand" href="{{ route('home') }}">
            <span class="ap-appbar__mark" aria-hidden="true">{{ mb_strtoupper($apLetter) }}</span>
            <span class="ap-appbar__name">{{ \Illuminate\Support\Str::limit($apName, 18) }}</span>
        </a>
    @endif
    <span class="ap-appbar__spacer"></span>
    @auth
        <a class="ap-appbar__btn" href="{{ route('owner.dashboard') }}">Panel</a>
    @else
        <a class="ap-appbar__btn" href="{{ route('owner.register') }}">Giriş</a>
    @endauth
</header>
