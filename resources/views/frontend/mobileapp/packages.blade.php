<div class="ap-sec">
    <div class="ap-sec__head">
        <h2>Üyelik paketleri</h2>
        <span style="font-size:12px;color:var(--text_muted);font-weight:600">Abonelik</span>
    </div>
</div>
@if($plans->isEmpty())
    <div class="ap-empty">Şu anda tanımlı üyelik paketi yok. Bir süre sonra tekrar kontrol edin.</div>
@else
    <div class="ap-plans">
        @foreach($plans as $plan)
            @php
                $apFeatures = is_array($plan->features) ? $plan->features : [];
                $apPopular = $plan->slug === 'gold';
            @endphp
            <article class="ap-plan {{ $apPopular ? 'ap-plan--featured' : '' }}">
                @if($apPopular)<span class="ap-plan__badge">En Popüler</span>@endif
                <h2>{{ $plan->name }}</h2>
                <p class="ap-plan__price">
                    @if($plan->price > 0)
                        {{ match ($plan->currency) { 'USD' => '$', 'EUR' => '€', default => '₺' } }}{{ number_format((float) $plan->price, $plan->currency === 'TRY' ? 0 : 2, ',', '.') }}
                        <small>{{ $plan->billing_period === 'monthly' ? 'aylık' : ($plan->billing_period === 'yearly' ? 'yıllık' : 'tek ödeme') }}</small>
                    @else
                        Ücretsiz
                        <small>standart kayıt</small>
                    @endif
                </p>
                @if(!empty($apFeatures))
                    <ul>
                        @foreach($apFeatures as $feature)
                            <li><strong>{{ $feature['title'] ?? '' }}</strong>@if(!empty($feature['description'])) — {{ $feature['description'] }}@endif</li>
                        @endforeach
                    </ul>
                @endif
                <a class="ap-btn {{ $apPopular ? '' : 'ap-btn--ghost' }}" href="{{ route('pages.contact', ['subject' => 'uyelik']) }}">{{ $plan->price > 0 ? 'Paketi seç' : 'Başvur' }}</a>
            </article>
        @endforeach
    </div>
    <div class="ap-note">Paketler hakkında sorularınız için <a href="{{ route('pages.contact') }}" style="color:var(--primary);font-weight:700">iletişim ekranına</a> yazın.</div>
@endif
