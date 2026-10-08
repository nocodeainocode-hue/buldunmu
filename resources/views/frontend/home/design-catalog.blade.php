@extends('layouts.app')

@section('title', $settings->homepage_title ?? $directory?->name ?? $settings->site_name ?? 'Firma Rehberi')
@section('meta_description', $settings->meta_description ?? 'Şehrinizdeki işletmeleri kategori, şehir ve yorumlarla keşfedin.')

@section('content')
@php
    $katPlates = $premiumCompanies->isNotEmpty()
        ? $premiumCompanies->concat($latestCompanies->whereNotIn('id', $premiumCompanies->pluck('id')))->take(5)
        : $latestCompanies->take(5);
    $katCompanyCount = \App\Models\Company::active()->count();
    $katJobTypes = ['part_time' => 'Yarı zamanlı', 'contract' => 'Sözleşmeli', 'internship' => 'Staj'];
@endphp
<div class="kat">
    @include('partials.catalog.header')

    <section class="kat-hero">
        <div class="kat-wrap kat-hero__grid">
            <div>
                <span class="kat-kicker">Nº {{ now()->format('Y') }} · Yerel katalog</span>
                <h1>{{ $settings->homepage_title ?? 'Şehrin en iyi işletmeleri, tek katalogda.' }}</h1>
                <p class="kat-hero__lede">{{ $settings->homepage_subtitle ?? 'Hizmeti seçin, bölgenizdeki işletmeleri inceleyin ve doğrudan iletişime geçin.' }}</p>
                <form class="kat-search" action="{{ route('search') }}" method="GET" role="search">
                    <label for="kat-q" class="sr-only" style="position:absolute;left:-9999px">Firma, hizmet veya şehir ara</label>
                    <input id="kat-q" name="q" type="search" placeholder="Ne arıyorsunuz?" autocomplete="off">
                    <button type="submit" aria-label="Ara">→</button>
                </form>
                @if($cities->isNotEmpty())
                    <div class="kat-hero__tags">
                        <span>Sık aranan:</span>
                        @foreach($cities->take(5) as $city)
                            <a href="{{ route('cities.show', $city->slug) }}">{{ $city->name }}</a>
                        @endforeach
                    </div>
                @endif
            </div>
            <aside class="kat-toc" aria-label="İçindekiler">
                <div class="kat-toc__head"><h2>İçindekiler</h2><span class="kat-caps kat-muted">Kategoriler</span></div>
                @foreach($categories->take(8) as $i => $category)
                    <a href="{{ route('categories.show', $category->slug) }}">
                        <span class="kat-toc__no">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="kat-toc__name">{{ $category->name }}</span>
                        <span class="kat-toc__dots"></span>
                        <span class="kat-toc__n">{{ $category->companies_count }}</span>
                    </a>
                @endforeach
                <a class="kat-toc__foot kat-link" style="border-bottom:0;border-top:1px solid var(--text);padding-top:14px" href="{{ route('companies.index') }}">Tüm firmaları gör →</a>
            </aside>
        </div>
    </section>

    <div class="kat-wrap">
        <div class="kat-figures">
            <div><strong>{{ number_format($katCompanyCount, 0, ',', '.') }}</strong><span>Kayıtlı firma</span></div>
            <div><strong>{{ $categories->count() }}</strong><span>Hizmet kategorisi</span></div>
            <div><strong>{{ $cities->count() }}</strong><span>Şehir sayfası</span></div>
            <div><strong>{{ $posts->count() + $homeJobs->count() }}</strong><span>Yeni yazı ve ilan</span></div>
        </div>
    </div>

    <section class="kat-sec">
        <div class="kat-wrap">
            <div class="kat-sec__head">
                <div class="kat-sec__title">
                    <span class="kat-sec__no">01</span>
                    <div><h2>Editörün <em>seçkisi</em></h2><p>Öne çıkan ve en son eklenen işletmeler.</p></div>
                </div>
                <a class="kat-link" href="{{ route('companies.index') }}">Tüm firmalar →</a>
            </div>
            @if($katPlates->isEmpty())
                <div class="kat-empty">Henüz firma kaydı yok. <a href="{{ route('owner.register') }}">İlk firmayı siz ekleyin.</a></div>
            @else
                <div class="kat-plates">
                    @foreach($katPlates as $company)
                        @include('partials.catalog.plate', ['company' => $company, 'plateNo' => $loop->iteration, 'lead' => $loop->first && $katPlates->count() >= 3])
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <section class="kat-sec">
        <div class="kat-wrap">
            <div class="kat-sec__head">
                <div class="kat-sec__title">
                    <span class="kat-sec__no">02</span>
                    <div><h2>Hizmet <em>dizini</em></h2><p>İhtiyacınız olan hizmeti kategorilere göre bulun.</p></div>
                </div>
            </div>
            <div class="kat-index">
                @foreach($categories as $i => $category)
                    <a href="{{ route('categories.show', $category->slug) }}">
                        <span class="kat-index__no">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="kat-index__name">{{ $category->name }}</span>
                        <span class="kat-index__meta"><span>{{ $category->companies_count }} firma</span><span>→</span></span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    @if($cities->isNotEmpty())
        <section class="kat-sec">
            <div class="kat-wrap">
                <div class="kat-sec__head">
                    <div class="kat-sec__title">
                        <span class="kat-sec__no">03</span>
                        <div><h2>Şehir <em>rehberi</em></h2><p>Bulunduğunuz yere yakın işletmelere ulaşın.</p></div>
                    </div>
                </div>
                <div class="kat-cities">
                    @foreach($cities->take(12) as $city)
                        <a href="{{ route('cities.show', $city->slug) }}"><span class="kat-cities__name">{{ $city->name }}</span><span class="kat-cities__n">{{ $city->companies_count }} firma</span></a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if($posts->isNotEmpty())
        <section class="kat-sec">
            <div class="kat-wrap">
                <div class="kat-sec__head">
                    <div class="kat-sec__title">
                        <span class="kat-sec__no">04</span>
                        <div><h2>Yazılar <em>&amp; rehberler</em></h2><p>Doğru işletmeyi seçmenize yardımcı olan içerikler.</p></div>
                    </div>
                    <a class="kat-link" href="{{ route('blog.index') }}">Tüm yazılar →</a>
                </div>
                <div class="kat-journal">
                    @foreach($posts as $post)
                        <a class="kat-article" href="{{ route('blog.show', $post->slug) }}">
                            <div class="kat-article__fig">
                                @if($post->image)<img src="{{ asset('storage/'.$post->image) }}" alt="{{ $post->title }}" loading="lazy">@else<span aria-hidden="true">{{ mb_substr($post->title, 0, 1) }}</span>@endif
                            </div>
                            <span class="kat-caps kat-muted">{{ $post->published_at?->format('d.m.Y') }}</span>
                            <h3>{{ $post->title }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit($post->excerpt ?: strip_tags($post->content), 120) }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if($homeJobs->isNotEmpty())
        <section class="kat-sec">
            <div class="kat-wrap">
                <div class="kat-sec__head">
                    <div class="kat-sec__title">
                        <span class="kat-sec__no">05</span>
                        <div><h2>Açık <em>pozisyonlar</em></h2><p>Firmalardan güncel iş ilanları.</p></div>
                    </div>
                    <a class="kat-link" href="{{ route('jobs.index') }}">Tüm ilanlar →</a>
                </div>
                <div class="kat-entries">
                    @foreach($homeJobs as $job)
                        <article class="kat-entry kat-entry--job">
                            <span class="kat-entry__no">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <div>
                                <p class="kat-entry__cat">{{ $katJobTypes[$job->employment_type] ?? 'Tam zamanlı' }} / {{ $job->location ?: ($job->company?->city?->name ?? 'Türkiye') }}</p>
                                <h3><a href="{{ route('jobs.show', $job->slug) }}">{{ $job->title }}</a></h3>
                                <p class="kat-entry__text">{{ $job->company?->name }}</p>
                            </div>
                            <a class="kat-entry__go" href="{{ route('jobs.show', $job->slug) }}">İlanı aç →</a>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @include('partials.catalog.cta')
    @include('partials.catalog.footer')
</div>
@endsection
