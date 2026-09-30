@php
    $planned = (int) ($record->items_count ?? 0);
    $published = (int) ($record->published_items_count ?? 0);
    $percentage = $planned > 0 ? (int) round($published / $planned * 100) : 0;
    $color = $planned === 0 ? '#94a3b8' : ($percentage === 100 ? '#16a34a' : '#0d9488');
@endphp

<div style="min-width:150px" aria-label="{{ $published }} / {{ $planned }} rehberde yayınlandı, %{{ $percentage }} tamamlandı">
    <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;font-size:12px;font-weight:600">
        <span>{{ $published }} / {{ $planned }} yayınlandı</span>
        <strong style="color:{{ $color }}">%{{ $percentage }}</strong>
    </div>
    <div role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $percentage }}"
         style="height:6px;margin-top:6px;overflow:hidden;border-radius:999px;background:#e2e8f0">
        <div style="height:100%;width:{{ $percentage }}%;border-radius:999px;background:{{ $color }}"></div>
    </div>
</div>
