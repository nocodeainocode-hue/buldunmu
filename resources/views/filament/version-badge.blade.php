@php
    $versionLabel = \App\Support\ApplicationVersion::label();
    $versionName = \App\Support\ApplicationVersion::name();
    $releasedAt = \App\Support\ApplicationVersion::releasedAt();
    $versionHistory = \App\Support\ApplicationVersion::history();
@endphp

<div class="mx-3 mb-3 rounded-lg border border-gray-200 bg-white/70 px-3 py-2.5 text-xs shadow-sm dark:border-white/10 dark:bg-white/5">
    <div class="flex items-center justify-between gap-3">
        <span class="font-semibold text-gray-700 dark:text-gray-200">Sistem sürümü</span>
        <span class="font-mono text-gray-500 dark:text-gray-400">{{ $versionLabel }}</span>
    </div>
    <div class="mt-1 truncate text-gray-500 dark:text-gray-400" title="{{ $versionName }}">
        {{ $versionName }}
    </div>
    @if($releasedAt)
        <div class="mt-0.5 text-[10px] text-gray-400 dark:text-gray-500">
            Yayın: {{ $releasedAt }}
        </div>
    @endif
    @if($versionHistory)
        <details class="mt-2 border-t border-gray-200 pt-2 dark:border-white/10">
            <summary class="cursor-pointer font-semibold text-gray-600 dark:text-gray-300">Sürüm geçmişi</summary>
            <ol class="mt-2 space-y-3">
                @foreach($versionHistory as $release)
                    <li>
                        <div class="font-semibold text-gray-700 dark:text-gray-200">v{{ $release['number'] }} · {{ $release['name'] }}</div>
                        <div class="text-[10px] text-gray-400 dark:text-gray-500">{{ $release['released_at'] }}</div>
                        @if(!empty($release['changes']))
                            <ul class="mt-1 list-disc space-y-0.5 pl-4 text-gray-500 dark:text-gray-400">
                                @foreach($release['changes'] as $change)
                                    <li>{{ $change }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ol>
        </details>
    @endif
</div>
