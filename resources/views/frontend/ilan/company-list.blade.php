@php
    $ibListTitle = $listTitle ?? 'Yayındaki firmalar';
    $ibTotal = method_exists($companies, 'total') ? $companies->total() : $companies->count();
@endphp
<section class="ib-box">
    <div class="ib-box__head">
        <h2>{{ $ibListTitle }}</h2>
        <span class="ib-box__note">{{ number_format($ibTotal, 0, ',', '.') }} kayıt</span>
    </div>
    <div class="ib-items">
        @forelse($companies as $company)
            <article class="ib-item">
                <a class="ib-item__thumb" href="{{ route('companies.show', $company->slug) }}" aria-label="{{ $company->name }} profilini aç">
                    @if($company->logo)
                        <img src="{{ asset('storage/'.$company->logo) }}" alt="{{ $company->name }} logosu" loading="lazy">
                    @else
                        {{ mb_substr($company->name, 0, 1) }}
                    @endif
                </a>
                <div class="ib-item__body">
                    <p class="ib-item__meta"><b>{{ $company->category?->name ?? 'İşletme' }}</b> / {{ $company->city?->name ?? 'Türkiye' }}{{ $company->district?->name ? ' · '.$company->district->name : '' }}</p>
                    <h3><a href="{{ route('companies.show', $company->slug) }}">{{ $company->name }}</a>@if($company->is_premium) <span class="ib-tag">Spot</span>@endif</h3>
                    <p class="ib-item__text">{{ \Illuminate\Support\Str::limit($company->short_description ?: 'Firma profilini, iletişim ve konum bilgilerini inceleyin.', 150) }}</p>
                </div>
                <div class="ib-item__side">
                    <span class="ib-item__date">{{ $company->created_at?->format('d.m.Y') }}</span>
                    <a class="ib-item__go" href="{{ route('companies.show', $company->slug) }}">Profili aç ›</a>
                </div>
            </article>
        @empty
            <div class="ib-empty">Bu seçimde henüz firma yok. <a href="{{ route('owner.register') }}">İlk firmayı siz ekleyin.</a></div>
        @endforelse
    </div>
    @if(method_exists($companies, 'hasPages') && $companies->hasPages())
        <div class="ib-pag">{{ $companies->links() }}</div>
    @endif
</section>
