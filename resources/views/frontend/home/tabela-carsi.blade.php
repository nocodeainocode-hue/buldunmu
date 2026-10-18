@extends('layouts.app')

@section('title', $homeSeo['title'] ?? ($directory->meta_title ?? ($directory->name ?? 'Firma Rehberi').' — Firma ve Hizmet Rehberi'))
@section('meta_description', ($settings->meta_description ?? null) ?: 'Mahallendeki ustayı, dükkânı ve hizmeti bul; numaraya dokun, doğrudan ara ya da WhatsApp\'tan yaz. Firmanı ücretsiz ekle.')

@section('content')
@php
    $tcTotal = (int) ($totalCompanies ?? 0);
    $tcFirms = $premiumCompanies->concat($latestCompanies)->unique('id')->take(9)->values();
    $tcN = $tcFirms->count();
    // "Yer boş" kartı yalnızca vitrinin son sırasındaki boşluğu doldurur (masaüstü 3, tablet 2 sütun); tam sırada basılmaz.
    $tcGapDesk = $tcN > 0 ? (3 - $tcN % 3) % 3 : 0;
    $tcGapTab = $tcN > 0 ? $tcN % 2 : 0;
    $tcTone = fn ($slug) => 'tc-c'.(crc32((string) $slug) % 6);
    $tcWalls = ['#f1d9c9', '#dfe7d3', '#f3e2b3', '#d6e3e6', '#e9dccd', '#e4d6e4'];
    $tcStreet = $categories->take(6)->values();
    // Cadde en az yedi cepheyle tam genişlik dolar: kategoriler + "Tabelanı bekliyor" dükkânı + gerekirse dükkânsız evler.
    $tcHouses = max(0, 6 - $tcStreet->count());
    $tcSearchCities = $cities->where('slug', '!=', 'diger-iller');
    $tcLocal = ($directory->geography_mode ?? 'national') !== 'national';
    $tcCitiesOnWall = $cities->isNotEmpty() && $cities->count() < 4;
    $tcSiteWa = preg_replace('/\D/', '', (string) ($settings->whatsapp ?? ''));
    if (str_starts_with($tcSiteWa, '00')) { $tcSiteWa = substr($tcSiteWa, 2); }
    if (str_starts_with($tcSiteWa, '0')) { $tcSiteWa = '9'.$tcSiteWa; }
    elseif (strlen($tcSiteWa) === 10 && str_starts_with($tcSiteWa, '5')) { $tcSiteWa = '90'.$tcSiteWa; }
@endphp
<div class="tc-home">
    {{-- Çarşı başı --}}
    <section class="tc-hero">
        <div class="tc-wrap tc-hero__head">
            <span class="tc-plate">Hoş geldiniz</span>
            <h1 class="tc-h1">{{ $settings->homepage_title ?: 'Çarşıdaki her esnaf, tek tabelada' }}</h1>
            <p class="tc-sub">{{ $settings->homepage_subtitle ?: 'Mahallendeki ustayı, dükkânı ve hizmeti bul; ara, yaz, kapısını çal.' }}</p>

            <div class="tc-hang">
                <div class="tc-hang__rail" aria-hidden="true"></div>
                <form id="tc-ara" class="tc-board tc-board--sway tc-bolts" action="{{ route('search') }}" method="GET" role="search" aria-label="Firma ara">
                    <div class="tc-board__strip" aria-hidden="true">Esnafa sor</div>
                    <div class="tc-board__fields">
                        <label class="tc-field" for="tc-q">
                            <span class="tc-sr">Ne arıyorsunuz?</span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.6-3.6"/></svg>
                            <input id="tc-q" class="tc-input" type="search" name="q" placeholder="Ne lazım? ör. tesisatçı, kuaför, firma adı" autocomplete="off" enterkeyhint="search">
                        </label>
                        <label for="tc-city">
                            <span class="tc-sr">{{ $tcLocal ? 'Hangi semtte?' : 'Hangi şehirde?' }}</span>
                            <select id="tc-city" class="tc-select" name="city">
                                <option value="">{{ $tcLocal ? 'Tüm semtler' : 'Tüm şehirler' }}</option>
                                @foreach($tcSearchCities as $tcCity)
                                    <option value="{{ $tcCity->slug }}">{{ $tcCity->name }}</option>
                                @endforeach
                            </select>
                        </label>
                        <button class="tc-bul" type="submit">Bul</button>
                    </div>
                </form>
            </div>

            @if($categories->isNotEmpty())
                <p class="tc-asked">Çok sorulan:
                    @foreach($categories->take(4) as $tcCat)
                        <a href="{{ route('categories.show', $tcCat->slug) }}">{{ $tcCat->name }}</a>@if(!$loop->last)<i aria-hidden="true">·</i>@endif
                    @endforeach
                </p>
            @endif
        </div>

        {{-- Çarşı cephesi: her meslek bir dükkân; kepenkler açık, vitrinde firma sayısı. Son dükkân tabela bekliyor. --}}
        <div class="tc-street-wrap">
            <ul class="tc-street" role="list" aria-label="Çarşıdaki dükkânlar">
                @foreach($tcStreet as $tcCat)
                    @php $tcCatN = (int) ($tcCat->companies_count ?? 0); @endphp
                    <li class="tc-front {{ $tcTone($tcCat->slug) }}" style="--wall: {{ $tcWalls[$loop->index % 6] }}">
                        <a class="tc-front__link" href="{{ route('categories.show', $tcCat->slug) }}" aria-label="{{ $tcCatN > 0 ? $tcCat->name.', '.$tcCatN.' firma' : $tcCat->name }}">
                            <span class="tc-front__upper" aria-hidden="true"><i class="tc-win"></i><i class="tc-win"></i></span>
                            <span class="tc-front__parapet" aria-hidden="true"></span>
                            <span class="tc-fsign tc-bolts"><span>{{ $tcCat->name }}</span></span>
                            <span class="tc-awn" aria-hidden="true"><span class="tc-tente"></span></span>
                            <span class="tc-front__ground" aria-hidden="true">
                                <span class="tc-door">
                                    <span class="tc-roll"></span>
                                    @if($tcCatN > 0)
                                        <span class="tc-door__count"><b>{{ number_format($tcCatN, 0, ',', '.') }}</b>firma</span>
                                    @else
                                        <span class="tc-door__count">Gir →</span>
                                    @endif
                                </span>
                                <span class="tc-side"><span class="tc-glass">@if($tcCat->icon)<span class="tc-glass__decal">{{ $tcCat->icon }}</span>@endif</span></span>
                            </span>
                            <span class="tc-front__base" aria-hidden="true"></span>
                        </a>
                    </li>
                @endforeach
                <li class="tc-front is-blank" style="--wall: {{ $tcWalls[$tcStreet->count() % 6] }}">
                    <a class="tc-front__link" href="{{ route('owner.register') }}" aria-label="Tabelanı bekliyor: dükkânını ücretsiz ekle">
                        <span class="tc-front__upper" aria-hidden="true"><i class="tc-win"></i><i class="tc-win"></i></span>
                        <span class="tc-front__parapet" aria-hidden="true"></span>
                        <span class="tc-fsign"><span>{{ $categories->isEmpty() ? 'Yakında açılıyor' : 'Tabelanı bekliyor' }}</span></span>
                        <span class="tc-awn" aria-hidden="true"><span class="tc-tente"></span></span>
                        <span class="tc-front__ground" aria-hidden="true">
                            <span class="tc-door">
                                <span class="tc-kepenk"></span>
                                <span class="tc-door__count tc-door__count--blank">Tabelanı as</span>
                            </span>
                            <span class="tc-side"><span class="tc-glass"><span class="tc-note">Yer<br>boş</span></span></span>
                        </span>
                        <span class="tc-front__base" aria-hidden="true"></span>
                    </a>
                </li>
                @for($tcH = 0; $tcH < $tcHouses; $tcH++)
                    <li class="tc-front is-house" style="--wall: {{ $tcWalls[($tcStreet->count() + 1 + $tcH) % 6] }}" aria-hidden="true">
                        <span class="tc-front__upper"><i class="tc-win"></i><i class="tc-win"></i></span>
                        <span class="tc-front__parapet"></span>
                        <span class="tc-front__ground"><span class="tc-house-door"></span><span class="tc-side"><span class="tc-glass tc-glass--home"></span></span></span>
                        <span class="tc-front__base"></span>
                    </li>
                @endfor
            </ul>
            <div class="tc-walk">
                @if($tcTotal >= 25)
                    <p class="tc-stat"><span class="tc-plate tc-plate--big"><b>{{ number_format($tcTotal, 0, ',', '.') }}</b>&nbsp;esnaf çarşıda</span></p>
                @endif
                <span class="tc-swipe" aria-hidden="true">kaydır →</span>
            </div>
        </div>
    </section>

    {{-- Çarşının vitrini --}}
    <section id="tc-vitrin" class="tc-sec">
        <div class="tc-wrap">
            <div class="tc-head">
                <div>
                    <h2 class="tc-h2">Çarşının vitrini</h2>
                    <p class="tc-lead">Öne çıkan ve yeni tabela asan dükkânlar. Numaraya dokun, doğrudan ara.</p>
                </div>
                @if($tcN > 0)<a class="tc-more" href="{{ route('companies.index') }}">Tüm dükkânlar @if($tcTotal >= 25)({{ number_format($tcTotal, 0, ',', '.') }}) @endif→</a>@endif
            </div>
            @if($tcN > 0)
                <ul class="tc-grid" role="list">
                    @foreach($tcFirms as $tcCo)
                        <li>@include('partials.tabela-carsi.card', ['company' => $tcCo])</li>
                    @endforeach
                    @if($tcGapDesk > 0 || $tcGapTab > 0)
                        <li class="tc-slot tc-slot--d{{ $tcGapDesk }} tc-slot--t{{ $tcGapTab }}">
                            <a class="tc-shop tc-shop--empty" href="{{ route('owner.register') }}">
                                <span class="tc-shop__sign tc-bolts"><span class="tc-shop__name">Yer boş</span></span>
                                <span class="tc-awn" aria-hidden="true"><span class="tc-tente"></span></span>
                                <span class="tc-shop__body">
                                    <strong class="tc-empty__title">Bu vitrin senin olsun</strong>
                                    <span class="tc-empty__text">Dükkânını ekle; adın, numaran ve WhatsApp butonun bu vitrinde dursun.</span>
                                    <span class="tc-btn tc-btn--plum">Tabelanı as</span>
                                </span>
                            </a>
                        </li>
                    @endif
                </ul>
            @else
                <div class="tc-empty">
                    <span class="tc-shop__sign tc-bolts tc-empty__sign"><span class="tc-shop__name">Çarşı yeni kuruluyor</span></span>
                    <div class="tc-awn" aria-hidden="true"><div class="tc-tente"></div></div>
                    <div class="tc-empty__body">
                        <p class="tc-empty__title">Henüz dükkân yok: ilk tabelayı sen as</p>
                        <p class="tc-empty__text">Firmanı ücretsiz ekle; müşterin seni numarandan ya da WhatsApp'tan bulsun.</p>
                        <a class="tc-btn tc-btn--plum tc-btn--lg" href="{{ route('owner.register') }}">Tabelanı as</a>
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- Tabela duvarı: tüm meslekler (üst menü #tc-sokaklar için bölüm her zaman basılır) --}}
    <section id="tc-sokaklar" class="tc-wall">
        <div class="tc-wrap">
            <div class="tc-head">
                <div>
                    <h2 class="tc-h2">Meslek sokakları</h2>
                    <p class="tc-lead">Her meslek bir tabela. Tabelaya dokun, o sokaktaki dükkânlara bak.</p>
                </div>
            </div>
            @if($categories->isNotEmpty())
                <ul class="tc-signs" role="list">
                    @foreach($categories as $tcCat)
                        @php $tcCatN = (int) ($tcCat->companies_count ?? 0); @endphp
                        <li>
                            <a class="tc-wsign tc-bolts {{ $tcTone($tcCat->slug) }}" href="{{ route('categories.show', $tcCat->slug) }}" aria-label="{{ $tcCatN > 0 ? $tcCat->name.', '.$tcCatN.' firma' : $tcCat->name }}">
                                <span class="tc-wsign__name">{{ $tcCat->name }}</span>
                                @if($tcCatN > 0)<b>{{ number_format($tcCatN, 0, ',', '.') }}</b>@endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="tc-empty-line">Tabela duvarı henüz boş. <a class="tc-more" href="{{ route('owner.register') }}">İlk tabelayı sen as →</a></p>
            @endif
            @if($tcCitiesOnWall)
                {{-- Az şehirde levhalar ayrı, yarı boş bir bölüm açmaz; aynı duvara asılır. --}}
                <div id="tc-mahalleler" class="tc-wall__cities">
                    <h2 class="tc-h3">{{ $tcLocal ? 'Semt levhaları' : 'Şehir levhaları' }}</h2>
                    @include('partials.tabela-carsi.city-plates', ['tcInline' => true])
                </div>
            @endif
        </div>
    </section>

    {{-- Şehirler (üst menü #tc-mahalleler için bölüm her zaman basılır) --}}
    @unless($tcCitiesOnWall)
        <section id="tc-mahalleler" class="tc-sec">
            <div class="tc-wrap">
                <div class="tc-head">
                    <div>
                        <h2 class="tc-h2">{{ $tcLocal ? 'Semtler' : 'Şehirler' }}</h2>
                        <p class="tc-lead">Sokak levhasını seç, oradaki esnafa bak.</p>
                    </div>
                </div>
                @if($cities->isNotEmpty())
                    @include('partials.tabela-carsi.city-plates')
                @else
                    <p class="tc-empty-line">Levhalar henüz asılmadı. <a class="tc-more" href="{{ route('companies.index') }}">Tüm dükkânlar →</a></p>
                @endif
            </div>
        </section>
    @endunless

    {{-- Eleman aranıyor --}}
    @if(isset($homeJobs) && $homeJobs->isNotEmpty())
        <section class="tc-sec">
            <div class="tc-wrap">
                <div class="tc-head">
                    <div>
                        <h2 class="tc-h2">Eleman aranıyor</h2>
                        <p class="tc-lead">Dükkân camlarına asılan son ilanlar.</p>
                    </div>
                    <a class="tc-more" href="{{ route('jobs.index') }}">Tüm ilanlar →</a>
                </div>
                <div class="tc-window">
                    <ul class="tc-notes tc-n{{ min(3, $homeJobs->count()) }}" role="list">
                        @foreach($homeJobs as $tcJob)
                            <li>
                                <a class="tc-jnote" href="{{ route('jobs.show', $tcJob->slug) }}">
                                    <span class="tc-jnote__head" aria-hidden="true">Eleman aranıyor</span>
                                    <span class="tc-jnote__title">{{ $tcJob->title }}</span>
                                    <span class="tc-jnote__meta">{{ $tcJob->company->name ?? '' }} · {{ $tcJob->location ?: ($tcJob->company->city->name ?? '') }}</span>
                                    <span class="tc-jnote__type">{{ match($tcJob->employment_type) {'part_time' => 'Yarı zamanlı', 'contract' => 'Sözleşmeli', 'internship' => 'Staj', default => 'Tam zamanlı'} }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </section>
    @endif

    {{-- Çarşıdan notlar --}}
    @if($posts->isNotEmpty())
        <section class="tc-sec">
            <div class="tc-wrap">
                <div class="tc-head">
                    <div>
                        <h2 class="tc-h2">Çarşıdan notlar</h2>
                        <p class="tc-lead">Usta seçerken işine yarayacak kısa yazılar.</p>
                    </div>
                    <a class="tc-more" href="{{ route('blog.index') }}">Tüm notlar →</a>
                </div>
                <ul class="tc-posts tc-n{{ min(3, $posts->count()) }}" role="list">
                    @foreach($posts as $tcPost)
                        <li>
                            <article class="tc-post">
                                <h3><a href="{{ route('blog.show', $tcPost->slug) }}">{{ $tcPost->title }}</a></h3>
                                <p>{{ \Illuminate\Support\Str::limit($tcPost->excerpt ?: strip_tags((string) $tcPost->content), 140) }}</p>
                                @if($tcPost->published_at)<time datetime="{{ $tcPost->published_at->toDateString() }}">{{ $tcPost->published_at->format('d.m.Y') }}</time>@endif
                            </article>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    {{-- Tabelanı as --}}
    <section id="tc-tabelani-as" class="tc-cta">
        <div class="tc-wrap">
            <div class="tc-hang tc-hang--cta">
                <div class="tc-hang__rail" aria-hidden="true"></div>
                <form class="tc-board tc-bolts" action="{{ route('owner.register') }}" method="GET">
                    <div class="tc-board__strip" aria-hidden="true">Tabelanı as</div>
                    <div class="tc-board__inner">
                        <label class="tc-blank" for="tc-firma">
                            <span class="tc-sr">Dükkânının adı</span>
                            <input id="tc-firma" name="company_name" maxlength="120" autocomplete="organization" placeholder="Senin tabelan burada dursun">
                        </label>
                        <h2 class="tc-cta__title">Dükkânını çarşıya taşı</h2>
                        <p class="tc-cta__text">Başvuru ücretsiz. Tabelan numaran, WhatsApp butonun ve adresinle asılır; ekibimiz seninle WhatsApp'tan iletişime geçer.</p>
                        <div class="tc-cta__btns">
                            <button class="tc-btn tc-btn--plum tc-btn--lg" type="submit">Tabelanı as</button>
                            @if($tcSiteWa !== '')
                                <a class="tc-btn tc-btn--wa tc-btn--lg" href="https://wa.me/{{ $tcSiteWa }}" target="_blank" rel="noopener">WhatsApp'tan sor</a>
                            @endif
                        </div>
                        <a class="tc-more tc-cta__panel" href="{{ route('owner.dashboard') }}">Tabelan zaten asılı mı? Firma paneline gir →</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <div class="tc-dock"><div class="tc-dock__bar"><a class="tc-btn tc-btn--line" href="#tc-ara">Esnafa sor</a><a class="tc-btn tc-btn--plum" href="{{ route('owner.register') }}">Tabelanı as</a></div></div>
</div>
<script>(()=>{const d=document.querySelector('.tc-dock'),f=document.getElementById('tc-ara');if(!d||!f)return;if(!('IntersectionObserver'in window)){d.classList.add('is-on');return;}new IntersectionObserver(([e])=>d.classList.toggle('is-on',!e.isIntersecting)).observe(f);})();</script>
@endsection
