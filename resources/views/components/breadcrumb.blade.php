@props(['items' => []])
<nav class="mb-6 flex flex-wrap gap-2 text-sm" style="color:var(--text_muted);" aria-label="Breadcrumb">
    <a href="{{ route('home') }}" class="hover:underline transition" style="color:var(--text_muted);">Ana Sayfa</a>
    @foreach($items as $item)
        <span aria-hidden="true">/</span>
        @if(!$loop->last && isset($item['url']))
            <a href="{{ $item['url'] }}" class="hover:underline transition" style="color:var(--text_muted);">{{ $item['label'] }}</a>
        @else
            <span style="color:var(--text);" @if($loop->last) aria-current="page" @endif>{{ $item['label'] }}</span>
        @endif
    @endforeach
</nav>
