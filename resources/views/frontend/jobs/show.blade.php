@extends('layouts.app')

@section('title', $job->title.' - '.$job->company->name)
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($job->description), 155))
@section('canonical', route('jobs.show', $job->slug))

@section('content')
<div class="mx-auto max-w-4xl px-4 py-10 sm:px-6">
    <x-breadcrumb :items="[['label' => 'İş İlanları', 'url' => route('jobs.index')], ['label' => $job->title]]" />
    <article class="mt-5 rounded-3xl border p-6 sm:p-9" style="border-color:var(--border);background:var(--bg_card);box-shadow:var(--card_shadow);">
        <p class="text-xs font-black uppercase tracking-widest" style="color:var(--primary);">İş ilanı · {{ match($job->employment_type) {'part_time' => 'Yarı zamanlı', 'contract' => 'Sözleşmeli', 'internship' => 'Staj', default => 'Tam zamanlı'} }}</p>
        <h1 class="mt-3 text-3xl font-black sm:text-4xl" style="color:var(--text);">{{ $job->title }}</h1>
        <div class="mt-5 flex flex-wrap gap-x-5 gap-y-2 text-sm" style="color:var(--text_muted);">
            <a href="{{ route('companies.show', $job->company->slug) }}" class="font-black" style="color:var(--primary);">{{ $job->company->name }}</a>
            <span>{{ $job->location ?: $job->company->city?->name }}</span>
            <span>Yayın: {{ $job->published_at?->format('d.m.Y') }}</span>
            @if($job->expires_at)<span>Son başvuru: {{ $job->expires_at->format('d.m.Y') }}</span>@endif
        </div>
        <div class="mt-8 border-t pt-8 text-base leading-8 whitespace-pre-line" style="border-color:var(--border);color:var(--text);">{{ $job->description }}</div>
        <div class="mt-8 border-t pt-6" style="border-color:var(--border);">
            @if($job->apply_url)
                <a href="{{ $job->apply_url }}" target="_blank" rel="noopener noreferrer" class="inline-block rounded-xl px-6 py-3 text-sm font-black text-white" style="background:var(--primary);">Başvuru sayfasına git ↗</a>
            @elseif($job->apply_email)
                <a href="mailto:{{ $job->apply_email }}?subject={{ rawurlencode($job->title.' iş ilanı başvurusu') }}" class="inline-block rounded-xl px-6 py-3 text-sm font-black text-white" style="background:var(--primary);">E-posta ile başvur</a>
            @endif
        </div>
    </article>
</div>
@endsection
