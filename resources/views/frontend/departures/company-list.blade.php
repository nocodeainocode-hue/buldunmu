@php
    $depListTitle = $listTitle ?? 'Kalkıştaki firmalar';
    $depTotal = method_exists($companies, 'total') ? $companies->total() : $companies->count();
@endphp
<section class="dep-panel">
    <div class="dep-panel__head">
        <h2>{{ $depListTitle }}</h2>
        <span class="dep-code">{{ number_format($depTotal, 0, ',', '.') }} kayıt</span>
    </div>
    @forelse($companies as $company)
        <article class="dep-row">
            <a class="dep-row__mark" href="{{ route('companies.show', $company->slug) }}" aria-label="{{ $company->name }} profilini aç">
                @if($company->logo)
                    <img src="{{ asset('storage/'.$company->logo) }}" alt="{{ $company->name }} logosu" loading="lazy">
                @else
                    {{ mb_substr($company->name, 0, 1) }}
                @endif
            </a>
            <div>
                <p class="dep-code" style="color:var(--amber-deep)">{{ $company->category?->name ?? 'İşletme' }} / {{ $company->city?->name ?? 'Türkiye' }}{{ $company->district?->name ? ' · '.$company->district->name : '' }}</p>
                <h3 class="dep-h3" style="margin:5px 0 5px"><a href="{{ route('companies.show', $company->slug) }}" style="text-decoration:none">{{ $company->name }}</a></h3>
                <p class="dep-card__text">{{ \Illuminate\Support\Str::limit($company->short_description ?: 'Firma profilini, iletişim ve konum bilgilerini inceleyin.', 142) }}</p>
            </div>
            <a class="dep-row__go" href="{{ route('companies.show', $company->slug) }}">Perona git →</a>
        </article>
    @empty
        <div class="dep-empty">Bu seçimde henüz firma yok. <a href="{{ route('owner.register') }}">İlk firmayı siz ekleyin.</a></div>
    @endforelse
    @if(method_exists($companies, 'hasPages') && $companies->hasPages())
        <div class="dep-pagination">{{ $companies->links() }}</div>
    @endif
</section>
