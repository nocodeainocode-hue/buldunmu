@php
    $bdTotal = method_exists($companies, 'total') ? $companies->total() : $companies->count();
@endphp
<section>
    <div class="bd-view">
        <input type="radio" name="bdview" id="bdv-list" checked>
        <input type="radio" name="bdview" id="bdv-grid">
        <div class="bd-view__bar">
            <span class="bd-view__count">{{ $listTitle ?? 'İlanlar' }} · <strong>{{ number_format($bdTotal, 0, ',', '.') }}</strong> sonuç</span>
            <div class="bd-view__toggle" role="group" aria-label="Görünüm"><label for="bdv-list">☰ Liste</label><label for="bdv-grid">▦ Galeri</label></div>
        </div>
        <div class="bd-items">
            @forelse($companies as $company)
                @include('partials.board.item', ['company' => $company])
            @empty
                <div class="bd-empty">Bu seçimde henüz ilan yok. <a href="{{ route('owner.register') }}">İlk ilanı sen ver.</a></div>
            @endforelse
        </div>
    </div>
    @if(method_exists($companies, 'hasPages') && $companies->hasPages())
        <div class="bd-pag">{{ $companies->links() }}</div>
    @endif
</section>
