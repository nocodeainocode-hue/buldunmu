<div class="sig sig-page">
    @include('partials.signal.band', [
        'crumb' => 'Üyelik paketleri',
        'eyebrow' => 'Abonelik / Paketler',
        'title' => 'Hangi pakette yer alıyorsunuz?',
        'description' => 'Firmanıza uygun paketi seçin; sinyalde öne çıkın.',
    ])
    <div class="sig-wrap" style="padding-top:26px">
        @if($plans->isEmpty())
            <div class="sig-empty" style="border:1px solid var(--border);border-radius:.125rem;background:var(--bg_card)">Şu anda tanımlı üyelik paketi yok. Bir süre sonra tekrar kontrol edin.</div>
        @else
            <div class="sig-plans">
                @foreach($plans as $plan)
                    @php
                        $sigFeatures = is_array($plan->features) ? $plan->features : [];
                        $sigPopular = $loop->index === 1;
                    @endphp
                    <article class="sig-plan {{ $sigPopular ? 'sig-plan--featured' : '' }}">
                        <span class="sig-plan__cat">Paket {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }} · {{ match ($plan->billing_period) { 'monthly' => 'Aylık', 'yearly' => 'Yıllık', 'onetime' => 'Tek seferlik', default => $plan->billing_period } }}</span>
                        @if($sigPopular)<span class="sig-plan__badge">Önerilen</span>@endif
                        <h2>{{ $plan->name }}</h2>
                        <p class="sig-plan__price">
                            @if($plan->price > 0)
                                {{ match ($plan->currency) { 'USD' => '$', 'EUR' => '€', default => '₺' } }}{{ number_format((float) $plan->price, $plan->currency === 'TRY' ? 0 : 2, ',', '.') }}
                                <small>{{ $plan->billing_period === 'monthly' ? 'aylık' : ($plan->billing_period === 'yearly' ? 'yıllık' : 'tek ödeme') }}</small>
                            @else
                                Ücretsiz
                                <small>standart kayıt</small>
                            @endif
                        </p>
                        @if(!empty($sigFeatures))
                            <ul>
                                @foreach($sigFeatures as $feature)
                                    <li><strong>{{ $feature['title'] ?? '' }}</strong>@if(!empty($feature['description'])) — {{ $feature['description'] }}@endif</li>
                                @endforeach
                            </ul>
                        @endif
                        <a class="sig-btn {{ $sigPopular ? '' : 'sig-btn--ghost' }}" href="{{ route('pages.contact', ['subject' => 'uyelik']) }}">{{ $plan->price > 0 ? 'Paketi seç' : 'Başvur' }} →</a>
                    </article>
                @endforeach
            </div>
            <p class="sig-note" style="margin-top:22px">Paket içerikleri firmadan firmaya değişebilir; güncel koşullar için <a href="{{ route('pages.contact') }}" style="color:var(--primary);font-weight:800">iletişim ekranına</a> yazın.</p>
        @endif
    </div>
</div>
