@extends('layouts.app')
@section('title', 'Üyelik Paketleri')
@section('meta_description', $settings->meta_description ?? 'Üyelik paketlerimizi inceleyin.')
@section('canonical', route('packages.index'))

@push('head')
@include('partials.seo.json-ld', ['schema' => \App\Support\SeoSchema::staticPage(
    'Üyelik Paketleri',
    $settings->meta_description ?? 'Üyelik paketlerimizi inceleyin.',
    route('packages.index'),
    'Üyelik Paketleri'
)])
@endpush

@section('content')
<div class="mx-auto px-4 sm:px-6 lg:px-8 py-12" style="max-width:var(--page_width,1280px);">
    <x-breadcrumb :items="[['label' => 'Üyelik Paketleri']]" />

    <div class="mb-10 text-center">
        <h1 class="text-3xl font-black tracking-tight sm:text-4xl" style="color:var(--text);">Üyelik Paketleri</h1>
        <p class="mt-3 max-w-2xl mx-auto text-base" style="color:var(--text_muted);">
            İhtiyacınıza uygun paketi seçin, firmanızı öne çıkarın.
        </p>
    </div>

    @if($plans->isEmpty())
        <div class="text-center py-20" style="color:var(--text_muted);">
            <svg class="mx-auto h-16 w-16 mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            <p class="text-lg font-semibold">Henüz bir üyelik paketi bulunmuyor.</p>
            <p class="text-sm mt-1">Lütfen daha sonra tekrar kontrol edin.</p>
        </div>
    @else
        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            @foreach($plans as $plan)
                @php
                    $features = is_array($plan->features) ? $plan->features : [];
                    $isPopular = $loop->index === 1;
                @endphp
                <div class="relative flex flex-col rounded-2xl border p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg {{ $isPopular ? 'ring-2' : '' }}"
                     style="border-color:var(--border);background:var(--bg_card);{{ $isPopular ? '--tw-ring-color: var(--primary);' : '' }}">
                    @if($isPopular)
                        <span class="absolute -top-3 left-1/2 -translate-x-1/2 rounded-full px-4 py-1 text-xs font-black text-white shadow" style="background:var(--primary);">
                            En Popüler
                        </span>
                    @endif

                    <div class="mb-5">
                        <h3 class="text-xl font-black" style="color:var(--text);">{{ $plan->name }}</h3>
                        <p class="text-sm mt-1" style="color:var(--text_muted);">
                            {{ match($plan->billing_period) { 'monthly' => 'Aylık', 'yearly' => 'Yıllık', 'onetime' => 'Tek Seferlik', default => $plan->billing_period } }}
                        </p>
                    </div>

                    <div class="mb-6">
                        <span class="text-4xl font-black" style="color:var(--text);">
                            @if($plan->price > 0)
                                {{ match($plan->currency) { 'USD' => '$', 'EUR' => '€', default => '₺' } }}{{ number_format((float) $plan->price, $plan->currency === 'TRY' ? 0 : 2) }}
                            @else
                                Ücretsiz
                            @endif
                        </span>
                        @if($plan->price > 0 && $plan->billing_period !== 'onetime')
                            <span class="text-sm" style="color:var(--text_muted);">/ {{ $plan->billing_period === 'monthly' ? 'ay' : 'yıl' }}</span>
                        @endif
                    </div>

                    @if(!empty($features))
                        <ul class="mb-8 flex-1 space-y-3">
                            @foreach($features as $feature)
                                <li class="flex items-start gap-2 text-sm" style="color:var(--text);">
                                    <svg class="mt-0.5 h-4 w-4 shrink-0" style="color:var(--primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>
                                        <strong>{{ $feature['title'] ?? '' }}</strong>
                                        @if(!empty($feature['description']))
                                            <br><span style="color:var(--text_muted);">{{ $feature['description'] }}</span>
                                        @endif
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <a href="{{ route('pages.contact', ['subject' => 'uyelik']) }}"
                       class="mt-auto block w-full rounded-xl px-5 py-3 text-center text-sm font-black transition hover:opacity-90"
                       style="{{ $isPopular ? 'background:var(--primary);color:white;' : 'background:var(--bg);color:var(--text);border:1px solid var(--border);' }}">
                        {{ $plan->price > 0 ? 'Paketi Seç' : 'Başvur' }}
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
