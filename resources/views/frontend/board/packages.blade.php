<div class="bd bd-page">
    <div class="bd-wrap">
        @include('partials.board.band', [
            'crumb' => 'Paketler',
            'tag' => '⭐ Üyelik',
            'title' => 'İlanını öne çıkar',
            'description' => 'İşletmene uygun paketi seç; panoda sabitlen, daha çok kişiye ulaş.',
        ])
        @if($plans->isEmpty())
            <div class="bd-empty">Şu anda tanımlı üyelik paketi yok. Bir süre sonra tekrar kontrol et.</div>
        @else
            <div class="bd-plans" style="padding-top:14px">
                @foreach($plans as $plan)
                    @php
                        $bdFeatures = is_array($plan->features) ? $plan->features : [];
                        $bdHot = $loop->index === 1;
                    @endphp
                    <article class="bd-plan {{ $bdHot ? 'bd-plan--hot' : '' }}">
                        @if($bdHot)<span class="bd-plan__flag">📌 En çok tercih edilen</span>@endif
                        <span class="bd-muted" style="font-size:13px;font-weight:600">{{ match ($plan->billing_period) { 'monthly' => 'Aylık', 'yearly' => 'Yıllık', 'onetime' => 'Tek seferlik', default => $plan->billing_period } }}</span>
                        <h2>{{ $plan->name }}</h2>
                        <p class="bd-plan__price">
                            @if($plan->price > 0)
                                {{ match ($plan->currency) { 'USD' => '$', 'EUR' => '€', default => '₺' } }}{{ number_format((float) $plan->price, $plan->currency === 'TRY' ? 0 : 2, ',', '.') }}
                                <small>{{ $plan->billing_period === 'monthly' ? 'aylık' : ($plan->billing_period === 'yearly' ? 'yıllık' : 'tek ödeme') }}</small>
                            @else
                                Ücretsiz<small>standart kayıt</small>
                            @endif
                        </p>
                        @if(!empty($bdFeatures))
                            <ul>@foreach($bdFeatures as $feature)<li><strong>{{ $feature['title'] ?? '' }}</strong>@if(!empty($feature['description'])) — {{ $feature['description'] }}@endif</li>@endforeach</ul>
                        @endif
                        <a class="bd-btn {{ $bdHot ? '' : 'bd-btn--ghost' }}" href="{{ route('pages.contact', ['subject' => 'uyelik']) }}">{{ $plan->price > 0 ? 'Paketi seç' : 'Başvur' }} →</a>
                    </article>
                @endforeach
            </div>
            <p class="bd-note-box" style="margin-top:28px">Paket içerikleri değişebilir; güncel koşullar için <a href="{{ route('pages.contact') }}" style="color:var(--secondary);font-weight:700;text-decoration:underline">iletişim sayfasına</a> yaz.</p>
        @endif
    </div>
</div>
