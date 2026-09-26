<section class="mx-auto px-4 py-12 sm:px-6" style="max-width:var(--page_width,1280px);">
    <div class="mb-6 flex flex-wrap items-end justify-between gap-3">
        <div>
            <p class="text-xs font-black uppercase tracking-widest" style="color:var(--primary);">Firmalardan yeni fırsatlar</p>
            <h2 class="mt-2 text-2xl font-black sm:text-3xl" style="color:var(--text);">İş İlanları</h2>
        </div>
        <a href="{{ route('jobs.index') }}" class="text-sm font-black" style="color:var(--primary);">Tüm ilanlar →</a>
    </div>
    <div class="grid gap-4 md:grid-cols-3">
        @foreach($homeJobs as $job)
            <a href="{{ route('jobs.show', $job->slug) }}" class="block rounded-2xl border p-5 transition hover:-translate-y-0.5" style="border-color:var(--border);background:var(--bg_card);box-shadow:var(--card_shadow);">
                <span class="text-xs font-black uppercase" style="color:var(--primary);">{{ match($job->employment_type) {'part_time' => 'Yarı zamanlı', 'contract' => 'Sözleşmeli', 'internship' => 'Staj', default => 'Tam zamanlı'} }}</span>
                <h3 class="mt-2 text-lg font-black" style="color:var(--text);">{{ $job->title }}</h3>
                <p class="mt-2 text-sm" style="color:var(--text_muted);">{{ $job->company->name }} · {{ $job->location ?: $job->company->city?->name }}</p>
            </a>
        @endforeach
    </div>
</section>
