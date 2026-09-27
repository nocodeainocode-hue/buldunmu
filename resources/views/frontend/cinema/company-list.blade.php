<section class="cinema-panel">
    <div class="cinema-panel__head"><h2>{{ $listTitle ?? 'Gösterimdeki firmalar' }}</h2><small>{{ number_format($companies->total(), 0, ',', '.') }} kayıt</small></div>
    @forelse($companies as $company)
        <article class="cinema-list-row">
            <a class="cinema-list-row__art" href="{{ route('companies.show', $company->slug) }}" aria-label="{{ $company->name }} profilini aç">
                @if($company->logo)<img src="{{ asset('storage/'.$company->logo) }}" alt="{{ $company->name }} logosu" loading="lazy">@else{{ mb_substr($company->name, 0, 1) }}@endif
            </a>
            <div><small>{{ $company->category?->name ?? 'İşletme' }} / {{ $company->city?->name ?? 'Türkiye' }}</small><h3><a href="{{ route('companies.show', $company->slug) }}">{{ $company->name }}</a></h3><p>{{ \Illuminate\Support\Str::limit($company->short_description ?: 'Firma profilini inceleyin.', 140) }}</p></div>
            <a class="cinema-list-row__go" href="{{ route('companies.show', $company->slug) }}">Sahneye git ↗</a>
        </article>
    @empty
        <div class="cinema-empty">Bu seçimde henüz firma yok. <a href="{{ route('owner.register') }}">İlk firmayı ekleyin.</a></div>
    @endforelse
    @if($companies->hasPages())<div class="cinema-pagination">{{ $companies->links() }}</div>@endif
</section>
