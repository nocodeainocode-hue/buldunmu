{{-- Elegant Premium · editöryal başlık bandı (tüm iç sayfalarda ortak) --}}
@php
    $elEyebrow = $eyebrow ?? 'Zarif keşif';
@endphp
<section class="el-band">
    <div class="el-wrap">
        <nav class="el-crumb" aria-label="Gezinme yolu">
            <a href="{{ route('home') }}">Ana sayfa</a>
            <span class="el-crumb__sep">/</span>
            <span>{{ $crumb ?? $title }}</span>
        </nav>
        <span class="el-eyebrow">{{ $elEyebrow }}</span>
        <h1 style="margin-top:14px">{{ $title }}</h1>
        @if(!empty($description))
            <p class="el-band__desc">{{ $description }}</p>
        @endif
    </div>
</section>
