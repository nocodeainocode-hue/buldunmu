{{-- Levha (firma kartı): $company, $plateNo, isteğe bağlı $lead --}}
@php $katImage = $company->cover_image ?: $company->logo; @endphp
<a class="kat-plate {{ !empty($lead) ? 'kat-plate--lead' : '' }}" href="{{ route('companies.show', $company->slug) }}">
    <div class="kat-plate__fig">
        <span class="kat-plate__folio">Nº {{ str_pad((string) $plateNo, 2, '0', STR_PAD_LEFT) }}</span>
        @if($company->hasActivePremium())<span class="kat-plate__flag">Seçki</span>@endif
        @if($katImage)
            <img src="{{ asset('storage/'.$katImage) }}" alt="{{ $company->name }}" loading="lazy">
        @else
            <span class="kat-plate__init" aria-hidden="true">{{ mb_strtoupper(mb_substr($company->name, 0, 1)) }}</span>
        @endif
    </div>
    <div class="kat-plate__body">
        <span class="kat-plate__cat">{{ $company->category?->name ?? 'İşletme' }}</span>
        <h3>{{ $company->name }}</h3>
        <p class="kat-plate__text">{{ \Illuminate\Support\Str::limit($company->short_description ?: 'Hizmetleri, iletişim ve konum bilgileri için profili açın.', 130) }}</p>
        <div class="kat-plate__foot">
            <span>{{ $company->city?->name ?? 'Türkiye' }}{{ $company->district?->name ? ' · '.$company->district->name : '' }}</span>
            <b>Sayfayı aç →</b>
        </div>
    </div>
</a>
