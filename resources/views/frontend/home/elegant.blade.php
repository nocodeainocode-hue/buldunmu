@extends('layouts.app')

@section('title', $settings->homepage_title ?? $settings->site_name ?? 'Firma Rehberi')
@section('meta_description', $settings->meta_description ?? 'Zarif bir rehberde seçkin işletmeler.')

@section('content')
<div class="el">
    {{-- ── Editöryal hero ── --}}
    <section style="background:var(--primary);color:#f3ede0;position:relative;overflow:hidden;">
        <div style="position:absolute;inset:0;background:linear-gradient(180deg,rgba(31,58,86,.4),var(--primary));"></div>
        <div class="el-wrap" style="position:relative;padding-block:88px 76px;">
            <span class="el-eyebrow" style="color:var(--accent)">{{ $settings->site_name ?? 'Elegant Rehber' }}</span>
            <h1 style="margin-top:22px;color:#fff;font-size:clamp(38px,6vw,68px);font-weight:500;line-height:1.05;max-width:16ch">{{ $settings->homepage_title ?? 'Zarafetle keşfedin' }}</h1>
            <p style="margin-top:22px;max-width:56ch;font-size:18px;font-weight:300;line-height:1.7;color:rgba(243,237,224,.82)">{{ $settings->homepage_subtitle ?? 'Seçkin işletmeleri tek bir zarif rehberde bir araya getiriyoruz.' }}</p>
            <form action="{{ route('search') }}" method="GET" role="search" class="el-find" style="margin-top:36px;max-width:720px">
                <label for="el-search" class="sr-only">Firma veya hizmet ara</label>
                <input id="el-search" name="q" placeholder="Firma, hizmet veya şehir yazın" style="background:#fff;border-color:#fff;color:var(--text)">
                <button class="el-btn el-btn--gold" style="border-color:var(--accent)">Keşfet</button>
            </form>
        </div>
    </section>

    {{-- ── İstatistik şeridi ── --}}
    <section class="el-wrap" style="padding-block:44px">
        <div class="el-stats">
            <div><b>{{ \App\Models\City::count() }}</b><span>Şehir</span></div>
            <div><b>{{ \App\Models\Company::count() }}</b><span>İşletme</span></div>
            <div><b>{{ \App\Models\Category::count() }}</b><span>Kategori</span></div>
            <div><b>{{ $homeJobs->count() }}</b><span>Açık Pozisyon</span></div>
        </div>
    </section>

    {{-- ── Asimetrik seçkin vitrin ── --}}
    @if($premiumCompanies->isNotEmpty())
        <section class="el-wrap" style="padding-block:24px 56px">
            <div style="display:flex;align-items:flex-end;justify-content:space-between;gap:16px;margin-bottom:32px">
                <div>
                    <span class="el-eyebrow">Seçkin</span>
                    <h2 style="font-size:clamp(30px,4vw,44px);font-weight:500;margin-top:12px">Öne çıkan işletmeler</h2>
                </div>
                <a class="el-btn el-btn--ghost" href="{{ route('companies.index') }}">Tüm firmalar</a>
            </div>
            <div class="el-grid">
                @foreach($premiumCompanies->take(6) as $company)
                    <a class="el-card" href="{{ route('companies.show', $company->slug) }}">
                        @if($company->is_premium)<span class="el-tag">Seçkin</span>@endif
                        <span class="el-card__logo">
                            @if($company->logo)<img src="{{ asset('storage/'.$company->logo) }}" alt="{{ $company->name }}" loading="lazy">@else{{ mb_substr($company->name, 0, 1) }}@endif
                        </span>
                        <span class="el-card__cat">{{ $company->category->name ?? 'İşletme' }}</span>
                        <h3>{{ $company->name }}</h3>
                        <p class="el-card__text">{{ $company->short_description ?: 'Firma profilini, iletişim ve konum bilgilerini inceleyin.' }}</p>
                        <span class="el-card__foot"><span>{{ $company->city->name ?? 'Türkiye' }}</span><span>Profili görün →</span></span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ── Numaralı kategori dizini ── --}}
    @if($categories->isNotEmpty())
        <section style="background:var(--bg_card);border-block:1px solid var(--border)">
            <div class="el-wrap" style="padding-block:56px">
                <span class="el-eyebrow">Dizin</span>
                <h2 style="font-size:clamp(28px,4vw,40px);font-weight:500;margin:12px 0 32px">Kategoriler</h2>
                <div class="el-catdir">
                    @foreach($categories->take(18) as $category)
                        <a href="{{ route('categories.show', $category->slug) }}" class="el-catdir__item">
                            <span class="el-idx">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="el-catdir__name">{{ $category->name }}</span>
                            <span class="el-catdir__count">{{ $category->companies_count }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ── Rehbere yeni eklenenler ── --}}
    @if($latestCompanies->isNotEmpty())
        <section class="el-wrap" style="padding-block:56px">
            <div style="display:flex;align-items:flex-end;justify-content:space-between;gap:16px;margin-bottom:32px">
                <div>
                    <span class="el-eyebrow">Yeniler</span>
                    <h2 style="font-size:clamp(28px,4vw,40px);font-weight:500;margin-top:12px">Rehbere yeni eklenenler</h2>
                </div>
            </div>
            <div class="el-items">
                @foreach($latestCompanies->take(6) as $company)
                    <a class="el-item" href="{{ route('companies.show', $company->slug) }}">
                        <span class="el-item__thumb">
                            @if($company->logo)<img src="{{ asset('storage/'.$company->logo) }}" alt="{{ $company->name }}" loading="lazy">@else{{ mb_substr($company->name, 0, 1) }}@endif
                        </span>
                        <span class="el-item__body">
                            <span class="el-item__meta"><b>{{ $company->category->name ?? 'İşletme' }}</b> · {{ $company->city->name ?? 'Türkiye' }}</span>
                            <h3>{{ $company->name }}</h3>
                            <span class="el-item__text">{{ \Illuminate\Support\Str::limit($company->short_description ?: 'Profilini ve iletişim bilgilerini inceleyin.', 150) }}</span>
                        </span>
                        <span class="el-item__side">
                            <span class="el-item__date">{{ $company->created_at?->format('d.m.Y') }}</span>
                            <span class="el-item__go">Profili görün →</span>
                        </span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ── Şehir keşfi ── --}}
    @if($cities->isNotEmpty())
        <section style="background:var(--bg_card);border-top:1px solid var(--border)">
            <div class="el-wrap" style="padding-block:56px">
                <span class="el-eyebrow">Coğrafya</span>
                <h2 style="font-size:clamp(28px,4vw,40px);font-weight:500;margin:12px 0 32px">Şehirlere göre keşfedin</h2>
                <div class="el-citygrid">
                    @foreach($cities->take(12) as $city)
                        <a href="{{ route('cities.show', $city->slug) }}" class="el-citygrid__item">
                            <span style="font-family:var(--font_heading);font-size:22px;font-weight:500;color:var(--primary)">{{ $city->name }}</span>
                            <span style="font-size:12px;letter-spacing:.1em;text-transform:uppercase;color:var(--accent)">{{ $city->companies_count }} işletme</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ── Editöryal yazılar ── --}}
    @if($posts->isNotEmpty())
        <section class="el-wrap" style="padding-block:56px">
            <div style="display:flex;align-items:flex-end;justify-content:space-between;gap:16px;margin-bottom:32px">
                <div>
                    <span class="el-eyebrow">Dergi</span>
                    <h2 style="font-size:clamp(28px,4vw,40px);font-weight:500;margin-top:12px">Rehber yazıları</h2>
                </div>
                <a class="el-btn el-btn--ghost" href="{{ route('blog.index') }}">Tüm yazılar</a>
            </div>
            <div class="el-grid">
                @foreach($posts->take(3) as $post)
                    <a class="el-card" href="{{ route('blog.show', $post->slug) }}" style="border-top-color:var(--border)">
                        <span class="el-card__cat">{{ $post->published_at?->format('d.m.Y') }}</span>
                        <h3 style="font-size:24px">{{ $post->title }}</h3>
                        <p class="el-card__text">{{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 130) }}</p>
                        <span class="el-card__foot"><span>{{ $post->author_name ?: 'Editör' }}</span><span>Okuyun →</span></span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ── Zarif CTA ── --}}
    <section class="el-cta">
        <div class="el-wrap">
            <span class="el-eyebrow" style="color:var(--accent);justify-content:center">Ayrıcalık</span>
            <h2 style="margin-top:16px">Firmanızı zarif rehberde öne çıkarın</h2>
            <p>Ücretsiz kayıt olun ya da seçkin paketlerimizle markanıza ayrıcalıklı bir konum kazandırın.</p>
            <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap">
                <a class="el-btn el-btn--gold" href="{{ route('owner.register') }}">Firma ekle</a>
                <a class="el-btn el-btn--ghost" style="border-color:rgba(243,237,224,.4);color:#f3ede0" href="{{ route('packages.index') }}">Paketler</a>
            </div>
        </div>
    </section>
</div>
@endsection
