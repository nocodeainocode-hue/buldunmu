{{-- CEP · ortak firma listesi (tema varyantına göre kart dili değişir) --}}
@php
    $phVariant = match($directory?->template) {
        'pocket-stories', 'social-feed' => 'pocket',
        'swipe-cards' => 'swipe',
        'pull-drawer' => 'drawer',
        'radar-scope' => 'blip',
        'index-rally' => 'rally',
        default => 'reels',
    };
    $phWrapClass = match($phVariant) {
        'pocket' => 'ph-grid',
        'swipe' => 'ph-list',
        'drawer' => 'ph-pull',
        'blip' => 'ph-blips',
        'rally' => 'ph-rally',
        default => 'ph-reels',
    };
    $phTotal = method_exists($companies, 'total') ? $companies->total() : $companies->count();
@endphp
<section class="ph-sheet">
    <div class="ph-sheet__head">
        <h2>{{ $listTitle ?? 'Firmalar' }}</h2>
        <span class="ph-meta">{{ number_format((int) $phTotal, 0, ',', '.') }} kayıt</span>
    </div>
    <div class="{{ $phWrapClass }}" style="padding:12px 12px 14px">
        @forelse($companies as $company)
            @include('frontend.phone.cards.' . $phVariant)
        @empty
            <div class="ph-empty" style="grid-column:1/-1">Bu seçimde henüz firma yok. <a href="{{ route('owner.register') }}" style="color:var(--ph-primary);font-weight:700">İlk firmayı ekleyin.</a></div>
        @endforelse
    </div>
    @if(method_exists($companies, 'hasPages') && $companies->hasPages())
        <div class="ph-pagination">{{ $companies->links() }}</div>
    @endif
</section>
