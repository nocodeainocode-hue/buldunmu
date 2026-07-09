<?php
// =============================================
// RICH COMPANY DETAIL PAGE - Full Setup
// Sunucuda calistir: php setup-detail.php
// =============================================

$ts = date('Y_m_d_His');

// ════════════════ MIGRATIONS ════════════════
$migrations = [
    "{$ts}_create_company_services_table" => <<<'PHP'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('company_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('company_services'); }
};
PHP,
    "{$ts}b_create_company_faqs_table" => <<<'PHP'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('company_faqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('question');
            $table->text('answer');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('company_faqs'); }
};
PHP,
    "{$ts}c_create_company_reviews_table" => <<<'PHP'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('company_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('reviewer_name');
            $table->integer('rating')->default(5);
            $table->text('comment');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('company_reviews'); }
};
PHP,
    "{$ts}d_create_company_keywords_table" => <<<'PHP'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('company_keywords', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('keyword');
            $table->string('target_url')->nullable();
            $table->string('target_type')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('company_keywords'); }
};
PHP,
];

foreach ($migrations as $name => $code) {
    file_put_contents("database/migrations/{$name}.php", $code);
}
echo "✅ 4 migration olusturuldu\n";

// ════════════════ MODELS ════════════════
$models = [
    'CompanyService' => 'company_id,title,description,sort_order',
    'CompanyFaq' => 'company_id,question,answer,sort_order',
    'CompanyReview' => 'company_id,reviewer_name,rating,comment,status',
    'CompanyKeyword' => 'company_id,keyword,target_url,target_type,sort_order',
];

foreach ($models as $name => $fillable) {
    $code = "<?php namespace App\Models;\nuse Illuminate\Database\Eloquent\Model;\n\nclass {$name} extends Model {\n    protected \$fillable = [{$fillable}];\n    public function company() { return \$this->belongsTo(Company::class); }\n}\n";
    file_put_contents("app/Models/{$name}.php", $code);
}
echo "✅ 4 model olusturuldu\n";

// ════════════════ UPDATE COMPANY MODEL ════════════════
$companyModel = file_get_contents('app/Models/Company.php');
if (!str_contains($companyModel, 'services()')) {
    $relations = "\n    public function services() { return \$this->hasMany(CompanyService::class)->orderBy('sort_order'); }\n    public function faqs() { return \$this->hasMany(CompanyFaq::class)->orderBy('sort_order'); }\n    public function reviews() { return \$this->hasMany(CompanyReview::class)->where('status','approved')->latest(); }\n    public function keywords() { return \$this->hasMany(CompanyKeyword::class)->orderBy('sort_order'); }\n    public function avgRating() { return \$this->reviews()->avg('rating') ?: 0; }\n";
    $companyModel = str_replace("}\n", $relations . "}\n", $companyModel);
    file_put_contents('app/Models/Company.php', $companyModel);
}
echo "✅ Company model guncellendi\n";

// ════════════════ SEED SAMPLE DATA ════════════════
$seedCode = <<<'PHP'
<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\{Company,CompanyService,CompanyFaq,CompanyKeyword};

$companies = Company::where('status','active')->take(10)->get();
if ($companies->isEmpty()) { echo "No companies found!\n"; exit; }

foreach ($companies as $c) {
    // Services
    if ($c->services()->count()===0) {
        foreach ([['Hizmet','Profesyonel hizmet'],['Urun Satisi','Kaliteli urunler'],['Randevu','Online randevu'],['Teknik Destek','7/24 destek'],['Danismanlik','Uzman kadro'],['Kurumsal Cozumler','Isletmelere ozel']] as $i=>[$t,$d]) {
            $c->services()->create(['title'=>$t,'description'=>$d,'sort_order'=>$i]);
        }
    }
    // FAQs
    if ($c->faqs()->count()===0) {
        foreach ([['Nasil iletisime gecebilirim?','Telefon, WhatsApp veya web sitesi uzerinden bize ulasabilirsiniz.'],['Calisma saatleri nedir?','Hafta ici 09:00-18:00 arasi hizmet vermekteyiz.'],['Hangi bolgelere hizmet veriyorsunuz?','Oncelikli olarak bulundugumuz sehir ve cevre ilcelere hizmet vermekteyiz.'],['Fiyat teklifi nasil alabilirim?','Ucretsiz kesif ve fiyat teklifi icin bizi arayabilirsiniz.'],['Referanslariniz var mi?','Daha once calistigimiz musterilerimizin yorumlarini profil sayfamizda bulabilirsiniz.']] as $i=>[$q,$a]) {
            $c->faqs()->create(['question'=>$q,'answer'=>$a,'sort_order'=>$i]);
        }
    }
    // Keywords
    if ($c->keywords()->count()===0) {
        $kw = [
            ($c->city->name??'Sehir').' '.($c->category->name??'firma'),
            ($c->district->name??'Ilce').' '.($c->category->name??'isletme'),
            ($c->category->name??'Kategori').' firmalari',
            ($c->city->name??'Sehir').' isletmeleri',
        ];
        foreach ($kw as $i=>$k) {
            $c->keywords()->create(['keyword'=>$k,'target_url'=>'/arama?q='.urlencode($k),'target_type'=>'search','sort_order'=>$i]);
        }
    }
}
echo "Sample data seeded for ".$companies->count()." companies!\n";
PHP;

file_put_contents('seed-detail-data.php', $seedCode);
echo "✅ Seed script olusturuldu (seed-detail-data.php)\n";

// ════════════════ REWRITE SHOW.BLADE.PHP ════════════════
$showBlade = <<<'BLADE'
@extends('layouts.app')

@section('title', $company->meta_title ?: $company->name . ' - ' . ($company->city->name ?? '') . ' ' . ($company->category->name ?? '') . ' | Firma Rehberi')
@section('meta_description', $company->meta_description ?: $company->short_description ?: $company->name . ' hakkinda detayli bilgi, iletisim ve yorumlar.')
@section('canonical', route('companies.show', $company->slug))

@push('head')
{{-- LocalBusiness Schema --}}
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "LocalBusiness",
    "name": "{{ $company->name }}",
    "description": "{{ $company->short_description ?? $company->name }}"
    @if($company->logo),"image": "{{ asset('storage/'.$company->logo) }}"@endif
    @if($company->phone),"telephone": "{{ $company->phone }}"@endif
    @if($company->email),"email": "{{ $company->email }}"@endif
    @if($company->website),"url": "{{ $company->website }}"@endif
    @if($company->address),"address": { "@@type": "PostalAddress", "streetAddress": "{{ $company->address }}", "addressLocality": "{{ $company->city->name ?? '' }}", "addressCountry": "TR" }@endif
    @if($company->category),"category": "{{ $company->category->name }}"@endif
    @if($company->reviews->isNotEmpty()),"aggregateRating": { "@@type": "AggregateRating", "ratingValue": "{{ number_format($company->avgRating(),1) }}", "reviewCount": "{{ $company->reviews->count() }}" }@endif
}
</script>

{{-- FAQPage Schema --}}
@if($company->faqs->isNotEmpty())
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "FAQPage",
    "mainEntity": [
        @foreach($company->faqs as $faq)
        { "@@type": "Question", "name": "{{ $faq->question }}", "acceptedAnswer": { "@@type": "Answer", "text": "{{ $faq->answer }}" } }@if(!$loop->last),@endif
        @endforeach
    ]
}
</script>
@endif
@endpush

@section('content')
<div class="mx-auto px-4 sm:px-6 lg:px-8 py-6" style="max-width: var(--page_width, 1280px);">

    {{-- Breadcrumb --}}
    <nav class="flex flex-wrap text-sm mb-6 gap-1" style="color: var(--text_muted);">
        <a href="{{ route('home') }}" class="hover:underline">Ana Sayfa</a>
        <span>/</span>
        @if($company->city)<a href="{{ route('cities.show', $company->city->slug) }}" class="hover:underline">{{ $company->city->name }}</a><span>/</span>@endif
        @if($company->category)<a href="{{ route('categories.show', $company->category->slug) }}" class="hover:underline">{{ $company->category->name }}</a><span>/</span>@endif
        <span class="font-medium" style="color: var(--text);">{{ $company->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ═══ LEFT COLUMN ═══ --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Hero Card --}}
            <div class="rounded-2xl border overflow-hidden" style="background: var(--bg_card); border-color: var(--border);">
                <div class="p-6 sm:p-8">
                    <div class="flex flex-col sm:flex-row gap-6">
                        <div class="shrink-0">
                            @if($company->logo)
                                <img src="{{ asset('storage/'.$company->logo) }}" alt="{{ $company->name }}" class="w-24 h-24 rounded-2xl object-cover">
                            @else
                                <div class="w-24 h-24 rounded-2xl flex items-center justify-center text-white font-bold text-4xl" style="background: linear-gradient(135deg, var(--primary), var(--secondary));">{{ mb_substr($company->name,0,1) }}</div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <h1 class="text-2xl sm:text-3xl font-bold" style="color: var(--text);">{{ $company->name }}</h1>
                                @if($company->is_premium)
                                    <span class="px-3 py-1 text-white text-xs font-bold rounded-full" style="background: var(--accent);">⭐ Premium</span>
                                @endif
                            </div>
                            <div class="flex flex-wrap items-center gap-3 text-sm mb-3" style="color: var(--text_muted);">
                                @if($company->category)<span class="px-2.5 py-1 rounded-full text-xs font-medium" style="background: var(--primary_light); color: var(--primary);">{{ $company->category->name }}</span>@endif
                                <span>📍 {{ $company->city->name ?? '' }}{{ $company->district ? ' / '.$company->district->name : '' }}</span>
                            </div>
                            @if($company->reviews->isNotEmpty())
                            <div class="flex items-center gap-1 mb-2">
                                <span class="text-yellow-500 text-sm">★ {{ number_format($company->avgRating(),1) }}</span>
                                <span class="text-xs" style="color: var(--text_muted);">({{ $company->reviews->count() }} degerlendirme)</span>
                            </div>
                            @endif
                            <p class="text-sm" style="color: var(--text_muted);">{{ $company->short_description }}</p>
                            <p class="text-xs mt-2" style="color: var(--text_muted);">Son guncelleme: {{ $company->updated_at->format('d.m.Y') }}</p>
                        </div>
                    </div>
                </div>
                {{-- Cover Image --}}
                @if($company->cover_image)
                    <img src="{{ asset('storage/'.$company->cover_image) }}" alt="{{ $company->name }}" class="w-full h-48 sm:h-64 object-cover">
                @endif
            </div>

            {{-- Description Section --}}
            <div class="rounded-2xl border p-6 sm:p-8" style="background: var(--bg_card); border-color: var(--border);">
                <h2 class="text-xl font-bold mb-4" style="color: var(--text);">{{ $company->city->name ?? 'Sehir' }} {{ $company->category->name ?? 'Firma' }}: {{ $company->name }}</h2>
                @if($company->description)
                    <div class="prose max-w-none text-sm leading-relaxed space-y-3" style="color: var(--text);">
                        {!! nl2br(e($company->description)) !!}
                    </div>
                @else
                    <p class="text-sm" style="color: var(--text_muted);">{{ $company->name }}, {{ $company->city->name ?? 'bolgede' }} hizmet veren bir {{ $company->category->name ?? 'isletmedir' }}. Firma ile ilgili detayli bilgiye asagidaki iletisim kanallarindan ulasabilirsiniz.</p>
                @endif
            </div>

            {{-- Keywords / Tags --}}
            @if($company->keywords->isNotEmpty())
            <div class="rounded-2xl border p-6" style="background: var(--bg_card); border-color: var(--border);">
                <h3 class="text-sm font-bold mb-3 uppercase tracking-wide" style="color: var(--text_muted);">Etiketler</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($company->keywords as $kw)
                        <a href="{{ $kw->target_url ?? route('search', ['q'=>$kw->keyword]) }}" class="px-3 py-1.5 rounded-full text-xs font-medium border transition hover:shadow-sm" style="background: var(--bg); border-color: var(--border); color: var(--text_muted);">{{ $kw->keyword }}</a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Services --}}
            @if($company->services->isNotEmpty())
            <div class="rounded-2xl border p-6 sm:p-8" style="background: var(--bg_card); border-color: var(--border);">
                <h2 class="text-xl font-bold mb-6" style="color: var(--text);">Hizmetlerimiz</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($company->services as $s)
                    <div class="p-4 rounded-xl border" style="border-color: var(--border); background: var(--bg);">
                        <h4 class="font-semibold text-sm" style="color: var(--text);">✓ {{ $s->title }}</h4>
                        <p class="text-xs mt-2" style="color: var(--text_muted);">{{ $s->description }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Why Choose Us --}}
            <div class="rounded-2xl border p-6 sm:p-8" style="background: var(--bg_card); border-color: var(--border);">
                <h2 class="text-xl font-bold mb-6" style="color: var(--text);">Neden Bu Firma?</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="text-center p-4 rounded-xl" style="background: var(--bg);">
                        <div class="text-3xl mb-2">📞</div>
                        <h4 class="font-semibold text-sm" style="color: var(--text);">Kolay Iletisim</h4>
                        <p class="text-xs mt-1" style="color: var(--text_muted);">Telefon ve WhatsApp ile dogrudan ulasin</p>
                    </div>
                    <div class="text-center p-4 rounded-xl" style="background: var(--bg);">
                        <div class="text-3xl mb-2">📍</div>
                        <h4 class="font-semibold text-sm" style="color: var(--text);">Yerel Firma</h4>
                        <p class="text-xs mt-1" style="color: var(--text_muted);">{{ $company->city->name ?? 'Sehrinizde' }} hizmet veren yerel isletme</p>
                    </div>
                    <div class="text-center p-4 rounded-xl" style="background: var(--bg);">
                        <div class="text-3xl mb-2">⭐</div>
                        <h4 class="font-semibold text-sm" style="color: var(--text);">Guvenilir</h4>
                        <p class="text-xs mt-1" style="color: var(--text_muted);">Dogrulanmis firma profili ve iletisim bilgileri</p>
                    </div>
                </div>
            </div>

            {{-- CTA --}}
            <div class="rounded-2xl p-6 sm:p-8 text-white text-center" style="background: linear-gradient(135deg, var(--hero_gradient_from), var(--hero_gradient_to));">
                <h3 class="text-lg font-bold mb-2">{{ $company->name }} ile Iletisime Gecin</h3>
                <p class="text-sm opacity-80 mb-4">Telefon, WhatsApp veya web sitesi uzerinden firmaya ulasabilirsiniz.</p>
                <div class="flex flex-wrap gap-3 justify-center">
                    @if($company->phone)<a href="tel:{{ $company->phone }}" class="px-5 py-2.5 bg-white rounded-lg font-semibold text-sm" style="color: var(--primary);">📞 Ara</a>@endif
                    @if($company->whatsapp)<a href="https://wa.me/{{ preg_replace('/[^0-9]/','',$company->whatsapp) }}" target="_blank" class="px-5 py-2.5 bg-white rounded-lg font-semibold text-sm" style="color: var(--primary);">💬 WhatsApp</a>@endif
                </div>
            </div>

            {{-- Gallery --}}
            @if($company->images->isNotEmpty())
            <div class="rounded-2xl border p-6" style="background: var(--bg_card); border-color: var(--border);">
                <h2 class="text-xl font-bold mb-4" style="color: var(--text);">Galeri</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($company->images as $img)
                        <a href="{{ asset('storage/'.$img->image_path) }}" target="_blank" class="rounded-xl overflow-hidden border" style="border-color: var(--border);">
                            <img src="{{ asset('storage/'.$img->image_path) }}" alt="{{ $company->name }}" class="w-full h-32 object-cover hover:scale-105 transition">
                        </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Map Placeholder --}}
            @if($company->address)
            <div class="rounded-2xl border p-6" style="background: var(--bg_card); border-color: var(--border);">
                <h2 class="text-xl font-bold mb-4" style="color: var(--text);">Konum</h2>
                <div class="rounded-xl h-64 flex items-center justify-center" style="background: var(--bg);">
                    <div class="text-center" style="color: var(--text_muted);">
                        <div class="text-4xl mb-2">🗺️</div>
                        <p class="text-sm">{{ $company->address }}</p>
                        <p class="text-xs mt-1">{{ $company->city->name ?? '' }} / {{ $company->district->name ?? '' }}</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Reviews --}}
            <div class="rounded-2xl border p-6 sm:p-8" style="background: var(--bg_card); border-color: var(--border);">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold" style="color: var(--text);">Degerlendirmeler</h2>
                    @if($company->reviews->isNotEmpty())
                    <span class="text-yellow-500 font-bold text-lg">★ {{ number_format($company->avgRating(),1) }}</span>
                    @endif
                </div>
                @if($company->reviews->isNotEmpty())
                    <div class="space-y-4">
                        @foreach($company->reviews->take(5) as $review)
                        <div class="p-4 rounded-xl" style="background: var(--bg);">
                            <div class="flex justify-between items-start mb-2">
                                <span class="font-semibold text-sm" style="color: var(--text);">{{ $review->reviewer_name }}</span>
                                <span class="text-yellow-500 text-sm">★ {{ $review->rating }}/5</span>
                            </div>
                            <p class="text-sm" style="color: var(--text_muted);">{{ $review->comment }}</p>
                            <p class="text-xs mt-2" style="color: var(--text_muted);">{{ $review->created_at->format('d.m.Y') }}</p>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-center py-8" style="color: var(--text_muted);">Bu firma icin henuz degerlendirme yapilmamis. Ilk degerlendirmeyi siz yapin!</p>
                @endif
                <div class="mt-6 text-center">
                    <a href="{{ route('listing.create') }}" class="inline-block px-5 py-2 rounded-lg text-sm font-medium text-white" style="background: var(--primary);">✍️ Degerlendirme Yap</a>
                </div>
            </div>

            {{-- FAQs --}}
            @if($company->faqs->isNotEmpty())
            <div class="rounded-2xl border p-6 sm:p-8" style="background: var(--bg_card); border-color: var(--border);">
                <h2 class="text-xl font-bold mb-6" style="color: var(--text);">Sikca Sorulan Sorular</h2>
                <div class="space-y-3">
                    @foreach($company->faqs as $faq)
                    <details class="group rounded-xl border p-4 cursor-pointer" style="border-color: var(--border); background: var(--bg);">
                        <summary class="font-medium text-sm flex justify-between items-center" style="color: var(--text);">{{ $faq->question }} <span class="text-lg group-open:rotate-45 transition">+</span></summary>
                        <p class="text-sm mt-3 pt-3 border-t" style="color: var(--text_muted); border-color: var(--border);">{{ $faq->answer }}</p>
                    </details>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Similar Companies --}}
            <div class="rounded-2xl border p-6" style="background: var(--bg_card); border-color: var(--border);">
                <h2 class="text-xl font-bold mb-4" style="color: var(--text);">Benzer Firmalar</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($similarCompanies->take(4) as $s)
                    <a href="{{ route('companies.show', $s->slug) }}" class="flex items-center gap-3 p-3 rounded-xl border hover:shadow-md transition" style="border-color: var(--border); background: var(--bg);">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white font-bold text-lg shrink-0" style="background: linear-gradient(135deg, var(--primary), var(--secondary));">{{ mb_substr($s->name,0,1) }}</div>
                        <div class="min-w-0"><div class="font-semibold text-sm truncate" style="color: var(--text);">{{ $s->name }}</div><div class="text-xs" style="color: var(--text_muted);">{{ $s->category->name ?? '' }} · {{ $s->city->name ?? '' }}</div></div>
                    </a>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- ═══ RIGHT COLUMN: Sticky Contact ═══ --}}
        <div class="lg:col-span-1">
            <div class="sticky top-20 space-y-4">
                <div class="rounded-2xl border p-6" style="background: var(--bg_card); border-color: var(--border);">
                    <h3 class="font-bold text-lg mb-4" style="color: var(--text);">Iletisim Bilgileri</h3>
                    <div class="space-y-3">
                        @if($company->phone)
                        <a href="tel:{{ $company->phone }}" class="flex items-center gap-3 p-3 rounded-xl transition hover:shadow-md" style="background: var(--bg);">
                            <span class="text-xl">📞</span>
                            <div><div class="text-sm font-semibold" style="color: var(--primary);">{{ $company->phone }}</div><div class="text-xs" style="color: var(--text_muted);">Hemen Ara</div></div>
                        </a>
                        @endif
                        @if($company->whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/','',$company->whatsapp) }}" target="_blank" class="flex items-center gap-3 p-3 rounded-xl transition hover:shadow-md" style="background: var(--bg);">
                            <span class="text-xl">💬</span>
                            <div><div class="text-sm font-semibold" style="color: var(--primary);">{{ $company->whatsapp }}</div><div class="text-xs" style="color: var(--text_muted);">WhatsApp</div></div>
                        </a>
                        @endif
                        @if($company->website)
                        <a href="{{ $company->website }}" target="_blank" rel="nofollow" class="flex items-center gap-3 p-3 rounded-xl transition hover:shadow-md" style="background: var(--bg);">
                            <span class="text-xl">🌐</span>
                            <div><div class="text-sm font-semibold" style="color: var(--primary);">Web Sitesi</div><div class="text-xs" style="color: var(--text_muted);">Ziyaret Et →</div></div>
                        </a>
                        @endif
                    </div>
                </div>

                <div class="rounded-2xl border p-6" style="background: var(--bg_card); border-color: var(--border);">
                    <h4 class="font-semibold text-sm mb-3" style="color: var(--text);">Firma Bilgileri</h4>
                    <dl class="space-y-3 text-sm">
                        @if($company->address)<div><dt style="color: var(--text_muted);">Adres</dt><dd class="font-medium" style="color: var(--text);">{{ $company->address }}</dd></div>@endif
                        @if($company->city)<div><dt style="color: var(--text_muted);">Sehir</dt><dd class="font-medium" style="color: var(--text);">{{ $company->city->name }}</dd></div>@endif
                        @if($company->district)<div><dt style="color: var(--text_muted);">Ilce</dt><dd class="font-medium" style="color: var(--text);">{{ $company->district->name }}</dd></div>@endif
                        @if($company->category)<div><dt style="color: var(--text_muted);">Kategori</dt><dd class="font-medium" style="color: var(--text);">{{ $company->category->name }}</dd></div>@endif
                    </dl>
                </div>

                <div class="rounded-2xl border p-4 text-center" style="background: var(--primary_light); border-color: var(--primary);">
                    <p class="text-xs font-medium" style="color: var(--primary);">Bu firmanin sahibi misiniz?</p>
                    <a href="{{ route('listing.create') }}" class="inline-block mt-2 px-4 py-1.5 rounded-lg text-xs font-bold text-white" style="background: var(--primary);">Bilgileri Guncelle</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
BLADE;

file_put_contents('resources/views/frontend/companies/show.blade.php', $showBlade);
echo "✅ Detail page rewritten\n";

// ════════════════ UPDATE COMPANIES CONTROLLER ════════════════
$controller = file_get_contents('app/Http/Controllers/Frontend/CompanyController.php');
if (!str_contains($controller, 'faqs')) {
    $controller = str_replace(
        "Company::active()->with(['category', 'city', 'district'])",
        "Company::active()->with(['category', 'city', 'district', 'services', 'faqs', 'reviews', 'keywords', 'images'])",
        $controller
    );
    $controller = str_replace(
        "->with(['category', 'city'])->latest()->take(6)",
        "->with(['category', 'city'])->latest()->take(4)",
        $controller
    );
    file_put_contents('app/Http/Controllers/Frontend/CompanyController.php', $controller);
}
echo "✅ CompanyController guncellendi (eager loading)\n";

echo "\n══════════════════════════════\n";
echo "  KURULUM TAMAMLANDI!\n";
echo "  php artisan migrate --force\n";
echo "  php seed-detail-data.php\n";
echo "  php artisan optimize:clear\n";
echo "══════════════════════════════\n";
