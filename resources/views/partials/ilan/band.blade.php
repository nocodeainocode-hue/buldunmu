{{-- İlan Borsası · üst başlık bandı (tüm iç sayfalarda ortak) --}}
@php
    $ibEyebrow = $eyebrow ?? 'İlan borsası / Piyasa';
@endphp
<section class="ib-band">
    <div class="ib-wrap">
        <nav class="ib-crumb" aria-label="Gezinme yolu">
            <a href="{{ route('home') }}">Ana sayfa</a>
            <span class="ib-crumb__sep">/</span>
            <span>{{ $crumb ?? $title }}</span>
        </nav>
        <h1>{{ $title }}<small>{{ $ibEyebrow }}</small></h1>
        @if(!empty($description))
            <p style="margin-top:10px;max-width:70ch;font-size:13.5px;color:rgba(238,244,249,.8)">{{ $description }}</p>
        @endif
    </div>
</section>
