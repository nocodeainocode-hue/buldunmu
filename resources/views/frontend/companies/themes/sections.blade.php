<section class="td-section" id="hakkinda">
    <div class="td-section-head"><span class="td-index">01</span><h2>{{ $company->name }} hakkında</h2></div>
    <div class="td-prose">
        @if($company->description)
            {!! \App\Support\HtmlSanitizer::clean($company->description) !!}
        @else
            <p><strong>{{ $company->name }}</strong>, {{ $cityName }}{{ $districtName ? ' / '.$districtName : '' }} bölgesinde hizmet veren bir {{ $categoryName }} firmasıdır. İletişim ve konum bilgilerini bu sayfada bulabilirsiniz.</p>
        @endif
    </div>
    <div class="td-share">@include('partials.share-buttons', ['url' => route('companies.show', $company->slug), 'title' => $company->name])</div>
</section>

@if($offerings->isNotEmpty())
<section class="td-section" id="urunler-hizmetler">
    <div class="td-section-head"><span class="td-index">02</span><h2>Ürünler ve hizmetler</h2><span class="td-count">{{ $offerings->count() }} kayıt</span></div>
    <div class="td-card-grid">
        @foreach($offerings as $offering)
            <article class="td-card">
                @if($offering->image_path)<img class="td-card-image" src="{{ asset('storage/'.$offering->image_path) }}" alt="{{ $offering->name }}" loading="lazy">@endif
                <div class="td-card-body"><small>{{ $offering->type === 'product' ? 'ÜRÜN' : 'HİZMET' }}</small><h3>{{ $offering->name }}</h3>
                    @if($offering->description)<p class="td-linebreak">{{ $offering->description }}</p>@endif
                    @if($offering->price !== null)<strong class="td-price">{{ number_format((float) $offering->price, 2, ',', '.') }} TL</strong>@endif
                </div>
            </article>
        @endforeach
    </div>
</section>
@endif

@if($recentJobs->isNotEmpty())
<section class="td-section" id="is-ilanlari">
    <div class="td-section-head"><span class="td-index">03</span><h2>Açık iş ilanları</h2><a href="{{ route('jobs.index') }}">Tüm ilanlar ↗</a></div>
    <div class="td-card-grid">
        @foreach($recentJobs as $job)
            <a class="td-card td-job" href="{{ route('jobs.show', $job->slug) }}"><small>İŞ FIRSATI</small><h3>{{ $job->title }}</h3><p>{{ $job->location ?: $cityName }} · {{ $job->expires_at ? 'Son başvuru '.$job->expires_at->format('d.m.Y') : 'Başvurular açık' }}</p><strong>İlanı gör ↗</strong></a>
        @endforeach
    </div>
</section>
@endif

@if(!empty($company->services))
<section class="td-section"><div class="td-section-head"><span class="td-index">✳</span><h2>Hizmet alanları</h2></div><div class="td-card-grid">
    @foreach($company->services as $service)<div class="td-card td-card-body"><h3>{{ $service['title'] ?? '' }}</h3></div>@endforeach
</div></section>
@endif

@if(!empty($company->why_us_items))
<section class="td-section"><div class="td-section-head"><span class="td-index">✳</span><h2>Neden {{ $company->name }}?</h2></div><div class="td-card-grid">
    @foreach($company->why_us_items as $item)<div class="td-card td-card-body"><h3>{{ $item['title'] ?? '' }}</h3><p>{{ $item['description'] ?? '' }}</p></div>@endforeach
</div></section>
@endif

@if($company->images->isNotEmpty())
<section class="td-section" id="galeri"><div class="td-section-head"><span class="td-index">✳</span><h2>Fotoğraflar</h2></div><div class="td-gallery">
    @foreach($company->images as $image)<a href="{{ asset('storage/'.$image->image_path) }}" target="_blank" rel="noopener noreferrer" aria-label="Fotoğrafı büyüt"><img src="{{ asset('storage/'.$image->image_path) }}" alt="{{ $image->alt_text ?: $company->name.' fotoğrafı' }}" loading="lazy"></a>@endforeach
</div></section>
@endif

@if($googleMapsEmbedSrc)
<section class="td-section" id="konum"><div class="td-section-head"><span class="td-index">✳</span><h2>Konum</h2></div><div class="td-map"><iframe src="{{ $googleMapsEmbedSrc }}" loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade" title="{{ $company->name }} harita konumu"></iframe></div></section>
@endif

@if($company->opening_hours)
<section class="td-section"><div class="td-section-head"><span class="td-index">✳</span><h2>Çalışma saatleri</h2></div><div class="td-prose">{!! nl2br(e($company->opening_hours)) !!}</div></section>
@endif

@if(!empty($company->external_links))
<section class="td-section"><div class="td-section-head"><span class="td-index">↗</span><h2>Dış bağlantılar</h2></div><div class="td-card-grid">
    @foreach($company->external_links as $link)<a class="td-card td-card-body" href="{{ $link['url'] ?? '#' }}" target="_blank" rel="noopener noreferrer"><h3>{{ $link['label'] ?? 'Bağlantı' }} ↗</h3>@if(!empty($link['description']))<p>{{ $link['description'] }}</p>@endif</a>@endforeach
</div></section>
@endif

<section class="td-section" id="yorumlar">
    <div class="td-section-head"><span class="td-index">★</span><h2>Değerlendirmeler</h2><span class="td-count">{{ $reviewCount }} yorum</span></div>
    @if(session('review_success'))<p class="td-message">{{ session('review_success') }}</p>@endif
    @if($errors->any())<div class="td-message" role="alert">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
    <div class="td-reviews">
        @forelse($approvedReviews->take(5) as $review)
            <article class="td-review"><div><strong>{{ $review->name }}</strong><span>{{ $review->created_at->format('d.m.Y') }}</span></div><p class="td-stars" aria-label="5 üzerinden {{ $review->rating }} yıldız">{{ str_repeat('★', (int) $review->rating) }}{{ str_repeat('☆', 5 - (int) $review->rating) }}</p><p>{{ $review->comment }}</p></article>
        @empty<p class="td-empty">Henüz değerlendirme yok. İlk yorumu siz yazabilirsiniz.</p>@endforelse
    </div>
    <h3 class="td-form-title">Yorum bırak</h3>
    <form class="td-review-form" action="{{ route('companies.reviews.store', $company->slug) }}" method="POST">
        @csrf
@include('partials.honeypot')
        <input name="name" value="{{ old('name') }}" placeholder="Adınız Soyadınız" aria-label="Adınız Soyadınız" required>
        <input name="email" value="{{ old('email') }}" type="email" placeholder="E-posta (isteğe bağlı)" aria-label="E-posta">
        <select name="rating" required aria-label="Puanınız"><option value="">Puanınız</option>@for($i=5;$i>=1;$i--)<option value="{{ $i }}" @selected(old('rating') == $i)>{{ $i }} yıldız</option>@endfor</select>
        <textarea name="comment" rows="4" placeholder="Yorumunuzu yazın..." aria-label="Yorumunuz" required>{{ old('comment') }}</textarea>
        <button type="submit">Yorumu gönder ↗</button>
    </form>
</section>

<section class="td-section"><div class="td-section-head"><span class="td-index">?</span><h2>Sık sorulanlar</h2></div>
    <details class="td-faq"><summary>{{ $company->name }} ile nasıl iletişime geçebilirim?</summary><p>Sayfadaki telefon, WhatsApp, e-posta ve web sitesi bağlantılarını kullanabilirsiniz.</p></details>
    <details class="td-faq"><summary>Firma hangi bölgede hizmet veriyor?</summary><p>{{ $company->name }}, {{ $cityName }}{{ $districtName ? ' / '.$districtName : '' }} bölgesinde yer almaktadır{{ $company->address ? '. Adres: '.$company->address : '' }}.</p></details>
    <details class="td-faq"><summary>Firma bilgileri güncel mi?</summary><p>Profil bilgileri firma yetkilileri ve site yönetimi tarafından güncellenebilir. Son güncelleme: {{ $company->updated_at?->format('d.m.Y') }}.</p></details>
</section>

@if($relatedPosts->isNotEmpty())
<section class="td-section"><div class="td-section-head"><span class="td-index">✳</span><h2>İlgili yazılar</h2></div><div class="td-card-grid">
    @foreach($relatedPosts as $post)<a class="td-card td-card-body" href="{{ route('blog.show', $post->slug) }}"><small>{{ $post->published_at?->format('d.m.Y') }}</small><h3>{{ $post->title }}</h3>@if($post->excerpt)<p>{{ $post->excerpt }}</p>@endif</a>@endforeach
</div></section>
@endif
