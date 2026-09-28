<div class="el el-page">
    @include('partials.elegant.band', [
        'crumb' => 'Üyelik paketleri',
        'eyebrow' => 'Abonelik / Paketler',
        'title' => 'Üyelik paketleri',
        'description' => 'Firmanıza uygun paketi seçin; rehberde ayrıcalıklı bir konumda öne çıkın.',
    ])
    <div class="el-wrap">
        @if($plans->isEmpty())
            <div class="el-empty" style="border:1px solid var(--border);background:var(--bg_card);margin-top:34px">Şu anda tanımlı üyelik paketi yok. Bir süre sonra tekrar kontrol edin.</div>
        @else
            <div class="el-plans" style="margin-top:44px">
                @foreach($plans as $plan)
                    @php
                        $elFeatures = is_array($plan->features) ? $plan->features : [];
                        $elPopular = $loop->index === 1;
                    @endphp
                    <article class="el-plan {{ $elPopular ? 'el-plan--featured' : '' }}">
                        @if($elPopular)<span class="el-plan__badge">En çok tercih edilen</span>@endif
                        <span class="el-plan__cat">{{ match ($plan->billing_period) { 'monthly' => 'Aylık', 'yearly' => 'Yıllık', 'onetime' => 'Tek seferlik', default => $plan->billing_period } }}</span>
                        <h2>{{ $plan->name }}</h2>
                        <p class="el-plan__price">
                            @if($plan->price > 0)
                                {{ match ($plan->currency) { 'USD' => '$', 'EUR' => '€', default => '₺' } }}{{ number_format((float) $plan->price, $plan->currency === 'TRY' ? 0 : 2, ',', '.') }}
                                <small>{{ $plan->billing_period === 'monthly' ? 'aylık' : ($plan->billing_period === 'yearly' ? 'yıllık' : 'tek ödeme') }}</small>
                            @else
                                Ücretsiz
                                <small>standart kayıt</small>
                            @endif
                        </p>
                        @if(!empty($elFeatures))
                            <ul>
                                @foreach($elFeatures as $feature)
                                    <li><strong>{{ $feature['title'] ?? '' }}</strong>@if(!empty($feature['description'])) — {{ $feature['description'] }}@endif</li>
                                @endforeach
                            </ul>
                        @endif
                        <a class="el-btn {{ $elPopular ? '' : 'el-btn--ghost' }}" href="{{ route('pages.contact', ['subject' => 'uyelik']) }}">{{ $plan->price > 0 ? 'Paketi seçin' : 'Başvurun' }}</a>
                    </article>
                @endforeach
            </div>
            <p class="el-note" style="margin-top:34px">Paket içerikleri firmadan firmaya değişebilir; güncel koşullar için <a href="{{ route('pages.contact') }}" style="color:var(--primary);border-bottom:1px solid var(--accent);text-decoration:none">iletişim ekranına</a> yazın.</p>
        @endif
    </div>
</div>
