<section class="bp-panel"><div class="bp-panel-head"><h2>{{ $listTitle ?? 'Firma kayıtları' }}</h2><small>{{ number_format($companies->total(), 0, ',', '.') }} kayıt</small></div>
    @forelse($companies as $company)
        <article class="bp-row"><a class="bp-initial" href="{{ route('companies.show', $company->slug) }}" aria-label="{{ $company->name }} profilini aç">{{ mb_substr($company->name, 0, 1) }}</a><div><a class="bp-row-title" href="{{ route('companies.show', $company->slug) }}">{{ $company->name }}</a><p>{{ $company->category?->name ?? ($category->name ?? 'İşletme') }} · {{ $company->city?->name ?? ($city->name ?? 'Türkiye') }}{{ $company->district?->name ? ' / '.$company->district->name : '' }}</p>@if($company->short_description)<p>{{ \Illuminate\Support\Str::limit($company->short_description, 155) }}</p>@endif@if($company->phone)<p>Telefon: {{ $company->phone }}</p>@endif</div>@if($company->hasActivePremium())<span class="bp-tag">Vitrin</span>@endif</article>
    @empty
        <div class="bp-empty">{{ $emptyMessage ?? 'Bu alanda henüz firma kaydı bulunmuyor.' }} <a href="{{ route('owner.register') }}">Firma ekleyin.</a></div>
    @endforelse
    @if($companies->hasPages())<div class="bp-pagination">{{ $companies->links() }}</div>@endif
</section>
