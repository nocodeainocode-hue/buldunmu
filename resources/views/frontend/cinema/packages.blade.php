<div class="cinema cinema-page">
    @include('partials.cinema.page-hero', ['crumb' => 'Üyelik paketleri', 'eyebrow' => 'Vitrin / Paketler', 'title' => 'Sahnedeki yerinizi seçin', 'description' => 'Firmanız için uygun üyelik paketini keşfedin.'])
    <div class="cinema-wrap cinema-page__body">
        @if($plans->isEmpty())<div class="cinema-empty">Şu anda üyelik paketi bulunmuyor. Daha sonra tekrar kontrol edin.</div>@else
        <div class="cinema-grid">@foreach($plans as $plan)<article class="cinema-plan"><span class="cinema-plan__number">PAKET / {{ str_pad($loop->iteration,2,'0',STR_PAD_LEFT) }}</span><h2>{{ $plan->name }}</h2><p style="color:#6c625a;font-size:12px;margin-top:6px">{{ match($plan->billing_period){'monthly'=>'Aylık','yearly'=>'Yıllık','onetime'=>'Tek seferlik',default=>$plan->billing_period} }}</p><div class="cinema-plan__price">@if($plan->price > 0){{ match($plan->currency){'USD'=>'$','EUR'=>'€',default=>'₺'} }}{{ number_format((float)$plan->price,$plan->currency === 'TRY' ? 0 : 2,',','.') }}@else Ücretsiz @endif</div><ul>@foreach(is_array($plan->features) ? $plan->features : [] as $feature)<li><strong>{{ $feature['title'] ?? '' }}</strong>@if(!empty($feature['description']))<br>{{ $feature['description'] }}@endif</li>@endforeach</ul><a class="cinema-btn" href="{{ route('pages.contact',['subject'=>'uyelik']) }}">{{ $plan->price > 0 ? 'Paketi seç' : 'Başvur' }} ↗</a></article>@endforeach</div>
        @endif
    </div>
</div>
