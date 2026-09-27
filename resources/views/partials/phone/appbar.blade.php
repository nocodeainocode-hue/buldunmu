{{-- CEP KABUĞU · uygulama başlığı (her alt sayfada aynı chrome) --}}
@php
    $phName = $directory?->name ?? config('app.name', 'Firma Rehberi');
    $phLetter = mb_substr(trim((string) $phName), 0, 1) ?: 'F';
@endphp
<header class="ph-appbar">
    @unless(request()->routeIs('home'))
        <a class="ph-appbar__back" href="{{ route('home') }}" aria-label="Ana sayfaya dön">‹</a>
    @endunless
    <a class="ph-appbar__logo" href="{{ route('home') }}">
        <span class="ph-appbar__mark" aria-hidden="true">{{ mb_strtoupper($phLetter) }}</span>
        <span>{{ \Illuminate\Support\Str::limit($phName, 18) }}</span>
    </a>
    <span class="ph-appbar__spacer"></span>
    <a class="ph-appbar__btn" href="{{ route('search') }}" aria-label="Firma ara">Ara</a>
    @auth
        <a class="ph-appbar__btn ph-appbar__btn--go" href="{{ route('owner.dashboard') }}">Panel</a>
    @else
        <a class="ph-appbar__btn ph-appbar__btn--go" href="{{ route('owner.register') }}">Kayıt</a>
    @endauth
</header>
