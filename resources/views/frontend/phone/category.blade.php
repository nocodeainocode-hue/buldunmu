{{-- CEP · kategori detayı --}}
<div class="ph-pagehero">
    <nav class="ph-crumb" aria-label="Gezinme">
        <a href="{{ route('home') }}">Ana Sayfa</a><span>/</span><a href="{{ route('companies.index') }}">Firmalar</a><span>/</span><span>{{ $category->name }}</span>
    </nav>
    <span class="ph-eyebrow">Kategori · {{ $totalInCategory }} firma</span>
    <h1>{{ $category->name }}</h1>
    <p>{{ $category->description ?: $category->name . ' alanındaki işletmeleri ve hizmetleri keşfedin.' }}</p>
</div>

@if($popularCities->isNotEmpty())
    <div class="ph-chips">
        <a class="ph-chip {{ request('city') ? '' : 'is-on' }}" href="{{ url()->current() }}">Tüm şehirler</a>
        @foreach($popularCities->take(10) as $ct)
            <a class="ph-chip {{ request('city') == $ct->slug ? 'is-on' : '' }}" href="{{ url()->current() }}?city={{ $ct->slug }}">{{ $ct->name }} ({{ $ct->companies_count }})</a>
        @endforeach
    </div>
@endif

@include('frontend.phone.company-list', ['listTitle' => $category->name . ' seçkisi'])

@if(!empty($seoContent))
    <section class="ph-sheet">
        <div class="ph-sheet__head"><h2>{{ $category->name }} hakkında</h2><span class="ph-meta">Rehber</span></div>
        <div class="ph-prose" style="padding:14px">@foreach(explode("\n\n", $seoContent) as $paragraph)<p>{{ $paragraph }}</p>@endforeach</div>
    </section>
@endif

@if($relatedCategories->isNotEmpty())
    <section class="ph-sheet">
        <div class="ph-sheet__head"><h2>İlgili kategoriler</h2><span class="ph-meta">Devam et</span></div>
        <div class="ph-chips" style="padding:12px 12px 14px">
            @foreach($relatedCategories as $related)<a class="ph-chip" href="{{ route('categories.show', $related->slug) }}">{{ $related->name }}</a>@endforeach
        </div>
    </section>
@endif

@if($posts->isNotEmpty())
    <section class="ph-sheet">
        <div class="ph-sheet__head"><h2>Bu kategoriden yazılar</h2></div>
        <div class="ph-list" style="padding:12px 12px 14px">
            @foreach($posts as $post)
                <a class="ph-row" href="{{ route('blog.show', $post->slug) }}">
                    <span class="ph-row__av">✦</span>
                    <div class="ph-row__txt"><strong>{{ $post->title }}</strong><span>{{ $post->published_at?->format('d.m.Y') }}</span></div>
                    <span class="ph-row__go">›</span>
                </a>
            @endforeach
        </div>
    </section>
@endif

<div class="ph-cta">
    <h2>Bu alanda mısın?</h2>
    <p>{{ $category->name }} listelerinde yer al, şehirdeki müşteriler sana doğrudan ulaşsın.</p>
    <a class="ph-btn" href="{{ route('owner.register') }}">Firma ekle →</a>
</div>
