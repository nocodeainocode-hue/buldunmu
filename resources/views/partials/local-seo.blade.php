{{-- Local SEO Content Partial --}}
{{-- Usage: @include('partials.local-seo', ['type'=>'city|category|home', 'name'=>'İstanbul', 'companyCount'=>42]) --}}
@props(['type' => 'home', 'name' => '', 'companyCount' => 0, 'subName' => ''])

@if($type === 'city')
<section class="py-12" style="background:var(--bg);">
    <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
        <div class="grid gap-8 lg:grid-cols-2">
            <div>
                <h2 class="text-xl font-black mb-3" style="color:var(--text);">{{ $name }} Firma Rehberi</h2>
                <p class="text-sm leading-7" style="color:var(--text_muted);">
                    {{ $name }} ilinde faaliyet gösteren <strong>{{ $companyCount }}+ firma</strong> ile Türkiye'nin en kapsamlı işletme rehberlerinden birine hoş geldiniz. 
                    Telefon, WhatsApp, adres, web sitesi ve kullanıcı yorumlarıyla {{ $name }} merkezli firmaları tek platformda keşfedin.
                </p>
                <p class="mt-2 text-sm leading-7" style="color:var(--text_muted);">
                    {{ $name }} ve ilçelerinde hizmet veren işletmelerin güncel iletişim bilgilerine, konumlarına ve müşteri değerlendirmelerine sayfamızdan ulaşabilir; 
                    ihtiyacınız olan hizmeti en hızlı şekilde bulabilirsiniz.
                </p>
            </div>
            <div class="rounded-2xl border p-5" style="border-color:var(--border);background:var(--bg_card);">
                <h3 class="font-bold mb-3 text-sm" style="color:var(--text);">{{ $name }} için popüler kategoriler</h3>
                <div class="flex flex-wrap gap-1.5">
                    @php
                        $popularCats = \App\Models\Category::active()->whereHas('companies', fn($q) => $q->whereHas('city', fn($c) => $c->where('name', $name)))->withCount('companies')->orderByDesc('companies_count')->take(8)->get();
                    @endphp
                    @foreach($popularCats as $pcat)
                        <a href="{{ route('categories.show', $pcat->slug) }}" class="px-3 py-1.5 rounded-full text-xs font-medium border transition hover:shadow-sm" style="border-color:var(--border);color:var(--text_muted);">{{ $pcat->name }} ({{ $pcat->companies_count }})</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif

@if($type === 'category')
<section class="py-12" style="background:var(--bg);">
    <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
        <div class="grid gap-8 lg:grid-cols-2">
            <div>
                <h2 class="text-xl font-black mb-3" style="color:var(--text);">{{ $name }} Kategorisi Hakkında</h2>
                <p class="text-sm leading-7" style="color:var(--text_muted);">
                    {{ $name }} kategorisinde <strong>{{ $companyCount }}+ işletme</strong> ile hizmetinizdeyiz. 
                    {{ $name }} sektöründe faaliyet gösteren firmaların telefon, WhatsApp, adres ve web sitesi bilgilerini güncel olarak sunuyoruz.
                </p>
                <p class="mt-2 text-sm leading-7" style="color:var(--text_muted);">
                    {{ $name }} firmaları arasında arama yapabilir, şehir ve ilçeye göre filtreleyebilir, 
                    kullanıcı yorumlarını inceleyerek size en uygun işletmeyi seçebilirsiniz.
                    @if($subName)
                        <strong>{{ $subName }}</strong> başta olmak üzere Türkiye'nin dört bir yanında {{ $name }} hizmeti veren firmalar rehberimizde yer almaktadır.
                    @endif
                </p>
            </div>
            <div class="rounded-2xl border p-5" style="border-color:var(--border);background:var(--bg_card);">
                <h3 class="font-bold mb-3 text-sm" style="color:var(--text);">{{ $name }} firmalarının bulunduğu şehirler</h3>
                <div class="flex flex-wrap gap-1.5">
                    @php
                        $popularCities = \App\Models\City::whereHas('companies', fn($q) => $q->whereHas('category', fn($c) => $c->where('name', $name)))->withCount('companies')->orderByDesc('companies_count')->take(8)->get();
                    @endphp
                    @foreach($popularCities as $pct)
                        <a href="{{ route('cities.show', $pct->slug) }}" class="px-3 py-1.5 rounded-full text-xs font-medium border transition hover:shadow-sm" style="border-color:var(--border);color:var(--text_muted);">{{ $pct->name }} ({{ $pct->companies_count }})</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif

@if($type === 'home')
<section class="py-12" style="background:var(--bg_card);">
    <div class="mx-auto grid gap-8 px-4 sm:px-6 lg:grid-cols-3 lg:px-8" style="max-width:var(--page_width,1280px);">
        <div>
            <h2 class="text-xl font-black mb-3" style="color:var(--text);">Güvenilir Firma Rehberi</h2>
            <p class="text-sm leading-7" style="color:var(--text_muted);">
                {{ $name ?: 'Firma Rehberi' }}; kategori, şehir ve işletme bilgilerini tek yerde toplayarak kullanıcıların doğru firmaya daha hızlı ulaşmasını sağlar. 
                Telefon, WhatsApp, web sitesi, adres ve firma açıklamalarıyla yerel arama niyetine uygun, okunabilir ve sade bir rehber yapısı sunar.
            </p>
        </div>
        <div>
            <h3 class="font-bold mb-3 text-sm" style="color:var(--text);">Neden Bizi Tercih Etmelisiniz?</h3>
            <ul class="space-y-2 text-sm" style="color:var(--text_muted);">
                <li class="flex gap-2"><span class="text-green-500 font-bold">✓</span> Güncel ve doğrulanmış firma bilgileri</li>
                <li class="flex gap-2"><span class="text-green-500 font-bold">✓</span> Kategori ve şehir bazlı kolay filtreleme</li>
                <li class="flex gap-2"><span class="text-green-500 font-bold">✓</span> Kullanıcı yorumları ve puanlamalar</li>
                <li class="flex gap-2"><span class="text-green-500 font-bold">✓</span> Ücretsiz firma ekleme imkanı</li>
                <li class="flex gap-2"><span class="text-green-500 font-bold">✓</span> Mobil uyumlu, hızlı ve sade arayüz</li>
            </ul>
        </div>
        <div>
            <h3 class="font-bold mb-3 text-sm" style="color:var(--text);">Popüler Aramalar</h3>
            <div class="flex flex-wrap gap-1.5">
                @php
                    $topCategories = \App\Models\Category::active()->withCount('companies')->orderByDesc('companies_count')->take(10)->get();
                @endphp
                @foreach($topCategories as $tcat)
                    <a href="{{ route('categories.show', $tcat->slug) }}" class="px-3 py-1.5 rounded-full text-xs font-medium border transition hover:shadow-sm" style="border-color:var(--border);color:var(--text_muted);">{{ $tcat->name }}</a>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif
