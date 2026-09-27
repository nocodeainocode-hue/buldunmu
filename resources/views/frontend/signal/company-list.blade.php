@php
    $sigListTitle = $listTitle ?? 'Yayındaki firmalar';
    $sigTotal = method_exists($companies, 'total') ? $companies->total() : $companies->count();
@endphp
<section class="sig-panel">
    <div class="sig-panel__head">
        <h2>{{ $sigListTitle }}</h2>
        <span class="sig-code">{{ number_format($sigTotal, 0, ',', '.') }} kayıt</span>
    </div>
    <div class="sig-rows">
        @forelse($companies as $company)
            <article class="sig-row">
                <a class="sig-row__mark" href="{{ route('companies.show', $company->slug) }}" aria-label="{{ $company->name }} profilini aç">
                    @if($company->logo)
                        <img src="{{ asset('storage/'.$company->logo) }}" alt="{{ $company->name }} logosu" loading="lazy">
                    @else
                        {{ mb_substr($company->name, 0, 1) }}
                    @endif
                </a>
                <div class="sig-row__txt">
                    <p class="sig-row__cat">{{ $company->category?->name ?? 'İşletme' }} / {{ $company->city?->name ?? 'Türkiye' }}{{ $company->district?->name ? ' · '.$company->district->name : '' }}</p>
                    <h3><a href="{{ route('companies.show', $company->slug) }}">{{ $company->name }}</a></h3>
                    <p class="sig-row__text">{{ \Illuminate\Support\Str::limit($company->short_description ?: 'Firma profilini, iletişim ve konum bilgilerini inceleyin.', 142) }}</p>
                </div>
                <a class="sig-row__go" href="{{ route('companies.show', $company->slug) }}">Profili aç →</a>
            </article>
        @empty
            <div class="sig-empty">Bu seçimde henüz firma yok. <a href="{{ route('owner.register') }}">İlk firmayı siz ekleyin.</a></div>
        @endforelse
    </div>
    @if(method_exists($companies, 'hasPages') && $companies->hasPages())
        <div class="sig-pag">{{ $companies->links() }}</div>
    @endif
</section>
