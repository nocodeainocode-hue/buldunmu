@php
    $elListTitle = $listTitle ?? 'Yayındaki firmalar';
    $elTotal = method_exists($companies, 'total') ? $companies->total() : $companies->count();
@endphp
<section>
    <div class="el-box__head">
        <h2>{{ $elListTitle }}</h2>
        <span class="el-box__note">{{ number_format($elTotal, 0, ',', '.') }} kayıt</span>
    </div>
    <div class="el-items">
        @forelse($companies as $company)
            <a class="el-item" href="{{ route('companies.show', $company->slug) }}">
                <span class="el-item__thumb">
                    @if($company->logo)
                        <img src="{{ asset('storage/'.$company->logo) }}" alt="{{ $company->name }} logosu" loading="lazy">
                    @else
                        {{ mb_substr($company->name, 0, 1) }}
                    @endif
                </span>
                <span class="el-item__body">
                    <span class="el-item__meta"><b>{{ $company->category?->name ?? 'İşletme' }}</b> · {{ $company->city?->name ?? 'Türkiye' }}{{ $company->district?->name ? ' · '.$company->district->name : '' }}</span>
                    <h3>{{ $company->name }}</h3>
                    <span class="el-item__text">{{ \Illuminate\Support\Str::limit($company->short_description ?: 'Firma profilini, iletişim ve konum bilgilerini inceleyin.', 150) }}</span>
                </span>
                <span class="el-item__side">
                    <span class="el-item__date">{{ $company->created_at?->format('d.m.Y') }}</span>
                    @if($company->is_premium)<span class="el-tag" style="position:static">Seçkin</span>@endif
                    <span class="el-item__go">Profili görün →</span>
                </span>
            </a>
        @empty
            <div class="el-empty">Bu seçimde henüz firma yok. <a href="{{ route('owner.register') }}">İlk firmayı siz ekleyin.</a></div>
        @endforelse
    </div>
    @if(method_exists($companies, 'hasPages') && $companies->hasPages())
        <div class="el-pag">{{ $companies->links() }}</div>
    @endif
</section>
