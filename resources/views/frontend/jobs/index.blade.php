@extends('layouts.app')

@section('title', 'İş İlanları')
@section('meta_description', 'Bu rehberdeki firmaların güncel iş ilanlarını keşfedin ve doğrudan başvurun.')
@section('canonical', route('jobs.index'))

@section('content')
<div class="mx-auto px-4 py-10 sm:px-6" style="max-width:var(--page_width,1280px);">
    <x-breadcrumb :items="[['label' => 'İş İlanları']]" />
    <div class="mt-5 flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-xs font-black uppercase tracking-widest" style="color:var(--primary);">{{ $directory?->name ?? 'Firma Rehberi' }}</p>
            <h1 class="mt-2 text-3xl font-black sm:text-4xl" style="color:var(--text);">İş ilanları</h1>
            <p class="mt-3" style="color:var(--text_muted);">Firmaların açık pozisyonlarına göz atın.</p>
        </div>
        <a href="{{ route('owner.dashboard') }}" class="rounded-xl px-5 py-3 text-sm font-black text-white" style="background:var(--primary);">Firmanız için ilan yayınlayın</a>
    </div>

    <form action="{{ route('jobs.index') }}" method="GET" class="mt-8 flex max-w-2xl gap-2">
        <input name="q" value="{{ request('q') }}" aria-label="İlan veya firma ara" placeholder="Pozisyon veya firma ara" class="min-w-0 flex-1 rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg_card);color:var(--text);">
        <button class="rounded-xl px-5 py-3 text-sm font-black text-white" style="background:var(--primary);">Ara</button>
    </form>

    <div class="mt-8 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        @forelse($jobs as $job)
            <article class="flex flex-col rounded-2xl border p-5 transition hover:-translate-y-0.5" style="border-color:var(--border);background:var(--bg_card);box-shadow:var(--card_shadow);">
                <p class="text-xs font-black uppercase" style="color:var(--primary);">{{ match($job->employment_type) {'part_time' => 'Yarı zamanlı', 'contract' => 'Sözleşmeli', 'internship' => 'Staj', default => 'Tam zamanlı'} }}</p>
                <h2 class="mt-2 text-xl font-black" style="color:var(--text);"><a href="{{ route('jobs.show', $job->slug) }}">{{ $job->title }}</a></h2>
                <p class="mt-2 text-sm font-bold" style="color:var(--text_muted);">{{ $job->company->name }} · {{ $job->location ?: $job->company->city?->name }}</p>
                <p class="mt-4 line-clamp-3 flex-1 text-sm leading-6" style="color:var(--text_muted);">{{ $job->description }}</p>
                <div class="mt-5 flex items-center justify-between border-t pt-4 text-xs" style="border-color:var(--border);color:var(--text_muted);">
                    <span>{{ $job->published_at?->format('d.m.Y') }}</span>
                    <a href="{{ route('jobs.show', $job->slug) }}" class="font-black" style="color:var(--primary);">İlanı incele →</a>
                </div>
            </article>
        @empty
            <div class="rounded-2xl border p-8 text-center md:col-span-2 lg:col-span-3" style="border-color:var(--border);background:var(--bg_card);color:var(--text_muted);">Şu anda uygun bir iş ilanı bulunmuyor.</div>
        @endforelse
    </div>

    <div class="mt-8">{{ $jobs->links() }}</div>
</div>
@endsection
