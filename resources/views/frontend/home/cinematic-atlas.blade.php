@extends('layouts.app')

@section('title', $settings->homepage_title ?? $directory?->name ?? 'Sinematik Atlas')
@section('meta_description', $settings->meta_description ?? 'Şehrin işletmelerini, hizmetlerini, iş ilanlarını ve yazılarını keşfedin.')

@section('content')
<div class="cinema">
    @include('partials.cinema.header')
    <section class="cinema-hero">
        <div class="cinema-wrap">
            <div class="cinema-hero__frame" aria-hidden="true"></div>
            <span class="cinema-kicker">Şehrin sahnesi / {{ now()->format('Y') }}</span>
            <h1 class="cinema-title">Her işletmenin<br>bir hikâyesi var<span style="color:#e8aa62">.</span></h1>
            <p>Yakınınızdaki iyi işletmeleri, ustaları ve yeni fırsatları keşfedin. Aradığınız isim, kategori veya şehir bir sonraki sahnede.</p>
            <form class="cinema-search" action="{{ route('search') }}" method="GET"><input name="q" aria-label="Firma, hizmet veya şehir ara" placeholder="Firma, hizmet, kategori veya şehir ara…" required><button type="submit">Keşfet ↗</button></form>
            <div class="cinema-hero__actions"><a class="cinema-btn cinema-btn--ghost" href="{{ route('companies.index') }}">Tüm firmalar</a><a class="cinema-btn cinema-btn--ghost" href="{{ route('owner.register') }}">Firmanı ekle</a></div>
        </div>
    </section>
    <div class="cinema-wrap cinema-stats"><span><strong>{{ number_format(\App\Models\Company::active()->count(),0,',','.') }}</strong> firma</span><span><strong>{{ $categories->count() }}</strong> kategori</span><span><strong>{{ $cities->count() }}</strong> şehir rotası</span><span>Yerel keşif devam ediyor ↗</span></div>

    <section class="cinema-section cinema-wrap">
        <div class="cinema-section__head"><div><span class="cinema-kicker">01 / Başroldekiler</span><h2 class="cinema-small-title">Işıklar bu firmalarda.</h2><p>Öne çıkan işletmeleri yakından tanıyın; hizmetlerine ve iletişim bilgilerine ulaşın.</p></div><a href="{{ route('companies.index') }}">Tüm firmalar ↗</a></div>
        <div class="cinema-grid">@forelse($premiumCompanies->take(3) as $company)@include('frontend.cinema.company-card')@empty @foreach($latestCompanies->take(3) as $company)@include('frontend.cinema.company-card')@endforeach @endforelse</div>
    </section>

    <section class="cinema-section cinema-section--cream"><div class="cinema-wrap">
        <div class="cinema-section__head"><div><span class="cinema-kicker">02 / Sahne seçimi</span><h2 class="cinema-small-title">Neyi arıyorsunuz?</h2><p>İlgilendiğiniz sektörden başlayın, doğru işletmeye ulaşın.</p></div></div>
        <div class="cinema-category-grid">@forelse($categories->take(8) as $category)<a class="cinema-category" href="{{ route('categories.show',$category->slug) }}"><small>{{ str_pad($loop->iteration,2,'0',STR_PAD_LEFT) }} / KATEGORİ</small><strong>{{ $category->name }}</strong><span>{{ $category->companies_count }} firma ↗</span></a>@empty<div class="cinema-empty">Kategoriler yakında burada olacak.</div>@endforelse</div>
    </div></section>

    <section class="cinema-section cinema-wrap"><div class="cinema-section__head"><div><span class="cinema-kicker">03 / Yeni kareler</span><h2 class="cinema-small-title">Yeni keşifler.</h2><p>Rehbere yeni katılan işletmeler.</p></div><a href="{{ route('companies.index') }}">Dizini aç ↗</a></div><div class="cinema-grid">@forelse($latestCompanies->take(6) as $company)@include('frontend.cinema.company-card')@empty<div class="cinema-empty">İlk işletme kaydı için sahne hazır.</div>@endforelse</div></section>

    <section class="cinema-feature"><div class="cinema-feature__art" aria-hidden="true">✦</div><div class="cinema-feature__copy"><span class="cinema-kicker" style="color:#e8aa62">04 / Sıra sizin hikâyenizde</span><h2>Firmanızı sahneye çıkarın.</h2><p>Profilinizi oluşturun; ürünlerinizi, hizmetlerinizi ve iş ilanlarınızı ziyaretçilere gösterin.</p><a class="cinema-btn" href="{{ route('owner.register') }}">Firma kaydı oluştur ↗</a></div></section>

    <section class="cinema-section cinema-section--cream"><div class="cinema-wrap"><div class="cinema-section__head"><div><span class="cinema-kicker">05 / Şehir rotaları</span><h2 class="cinema-small-title">Şehrinizi seçin.</h2></div></div><div class="cinema-city-list">@forelse($cities as $city)<a href="{{ route('cities.show',$city->slug) }}">{{ $city->name }} ↗</a>@empty<span>Şehir bağlantıları hazırlanıyor.</span>@endforelse</div></div></section>

    <section class="cinema-section cinema-wrap"><div class="cinema-section__head"><div><span class="cinema-kicker">06 / Yeni fırsatlar</span><h2 class="cinema-small-title">Açık pozisyonlar.</h2></div><a href="{{ route('jobs.index') }}">Tüm ilanlar ↗</a></div><div class="cinema-grid">@forelse($homeJobs as $job)<article class="cinema-card"><div class="cinema-card__body"><span class="cinema-card__meta">İş ilanı / {{ $job->location ?: $job->company->city?->name }}</span><h3><a href="{{ route('jobs.show',$job->slug) }}">{{ $job->title }}</a></h3><p>{{ $job->company->name }} · {{ \Illuminate\Support\Str::limit($job->description,100) }}</p><div class="cinema-card__foot"><span>{{ $job->published_at?->format('d.m.Y') }}</span><a href="{{ route('jobs.show',$job->slug) }}">İlanı aç ↗</a></div></div></article>@empty<div class="cinema-empty">Yeni iş ilanları burada yayınlanacak.</div>@endforelse</div></section>

    @if($featuredOfferings->isNotEmpty())<section class="cinema-section cinema-section--cream"><div class="cinema-wrap"><div class="cinema-section__head"><div><span class="cinema-kicker">07 / Vitrinden</span><h2 class="cinema-small-title">Ürünler ve hizmetler.</h2></div></div><div class="cinema-grid">@foreach($featuredOfferings->take(3) as $offering)<article class="cinema-card"><div class="cinema-card__body"><span class="cinema-card__meta">{{ $offering->type === 'product' ? 'Ürün' : 'Hizmet' }} / {{ $offering->company->name }}</span><h3><a href="{{ route('companies.show',$offering->company->slug) }}#urunler-hizmetler">{{ $offering->name }}</a></h3><p>{{ \Illuminate\Support\Str::limit($offering->description ?? 'Firma vitrinindeki ürünü veya hizmeti inceleyin.',110) }}</p><div class="cinema-card__foot"><span>{{ $offering->price !== null ? number_format((float)$offering->price,2,',','.').' TL' : 'Detaylar profilde' }}</span><a href="{{ route('companies.show',$offering->company->slug) }}#urunler-hizmetler">İncele ↗</a></div></div></article>@endforeach</div></div></section>@endif

    <section class="cinema-section cinema-wrap"><div class="cinema-section__head"><div><span class="cinema-kicker">08 / Okuma arası</span><h2 class="cinema-small-title">Şehirden hikâyeler.</h2></div><a href="{{ route('blog.index') }}">Tüm yazılar ↗</a></div><div class="cinema-grid">@forelse($posts as $post)<article class="cinema-card"><div class="cinema-card__body"><span class="cinema-card__meta">Yazı / {{ $post->published_at?->format('d.m.Y') }}</span><h3><a href="{{ route('blog.show',$post->slug) }}">{{ $post->title }}</a></h3><p>{{ \Illuminate\Support\Str::limit($post->excerpt ?: strip_tags($post->content),125) }}</p><div class="cinema-card__foot"><span>Okuma köşesi</span><a href="{{ route('blog.show',$post->slug) }}">Oku ↗</a></div></div></article>@empty<div class="cinema-empty">Yeni yazılar yakında yayınlanacak.</div>@endforelse</div></section>
    @include('partials.cinema.footer')
</div>
@endsection
