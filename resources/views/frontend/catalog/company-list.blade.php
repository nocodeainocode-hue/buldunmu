@php
    $katListTitle = $listTitle ?? 'Yayındaki firmalar';
    $katTotal = method_exists($companies, 'total') ? $companies->total() : $companies->count();
    $katOffset = method_exists($companies, 'firstItem') ? (($companies->firstItem() ?? 1) - 1) : 0;
@endphp
<section class="kat-panel">
    <div class="kat-panel__head">
        <h2>{{ $katListTitle }}</h2>
        <span>{{ number_format($katTotal, 0, ',', '.') }} kayıt</span>
    </div>
    <div class="kat-entries" style="border-top:0">
        @forelse($companies as $company)
            <article class="kat-entry">
                <span class="kat-entry__no">{{ str_pad((string) ($katOffset + $loop->iteration), 2, '0', STR_PAD_LEFT) }}</span>
                <a class="kat-entry__mark" href="{{ route('companies.show', $company->slug) }}" aria-label="{{ $company->name }} profilini aç">
                    @if($company->logo)
                        <img src="{{ asset('storage/'.$company->logo) }}" alt="{{ $company->name }} logosu" loading="lazy">
                    @else
                        {{ mb_strtoupper(mb_substr($company->name, 0, 1)) }}
                    @endif
                </a>
                <div>
                    <p class="kat-entry__cat">{{ $company->category?->name ?? 'İşletme' }} / {{ $company->city?->name ?? 'Türkiye' }}{{ $company->district?->name ? ' · '.$company->district->name : '' }}</p>
                    <h3><a href="{{ route('companies.show', $company->slug) }}">{{ $company->name }}</a></h3>
                    <p class="kat-entry__text">{{ \Illuminate\Support\Str::limit($company->short_description ?: 'Firma profilini, iletişim ve konum bilgilerini inceleyin.', 150) }}</p>
                </div>
                <a class="kat-entry__go" href="{{ route('companies.show', $company->slug) }}">Profili aç →</a>
            </article>
        @empty
            <div class="kat-empty">Bu seçimde henüz firma yok. <a href="{{ route('owner.register') }}">İlk firmayı siz ekleyin.</a></div>
        @endforelse
    </div>
    @if(method_exists($companies, 'hasPages') && $companies->hasPages())
        <div class="kat-pag">{{ $companies->links() }}</div>
    @endif
</section>
