@php
    $apListTitle = $listTitle ?? 'Yayındaki firmalar';
    $apTotal = method_exists($companies, 'total') ? $companies->total() : $companies->count();
@endphp
<div class="ap-sec">
    <div class="ap-sec__head">
        <h2>{{ $apListTitle }}</h2>
        <span style="font-size:12px;color:var(--text_muted);font-weight:600">{{ number_format($apTotal, 0, ',', '.') }} kayıt</span>
    </div>
</div>
<div class="ap-list">
    @forelse($companies as $company)
        <a class="ap-row" href="{{ route('companies.show', $company->slug) }}">
            <span class="ap-row__av">
                @if($company->logo)<img src="{{ asset('storage/'.$company->logo) }}" alt="{{ $company->name }} logosu" loading="lazy">@else{{ mb_substr($company->name, 0, 1) }}@endif
            </span>
            <span class="ap-row__main">
                <h3>{{ $company->name }}@if($company->is_premium) ★@endif</h3>
                <p class="ap-row__meta">{{ $company->category?->name ?? 'İşletme' }} · {{ $company->city?->name ?? 'Türkiye' }}{{ $company->district?->name ? ' · '.$company->district->name : '' }}</p>
            </span>
            <span class="ap-row__chev">›</span>
        </a>
    @empty
        <div class="ap-empty">Bu seçimde henüz firma yok. <a href="{{ route('owner.register') }}">İlk firmayı siz ekleyin.</a></div>
    @endforelse
</div>
@if(method_exists($companies, 'hasPages') && $companies->hasPages())
    <div class="ap-pag">{{ $companies->links() }}</div>
@endif
