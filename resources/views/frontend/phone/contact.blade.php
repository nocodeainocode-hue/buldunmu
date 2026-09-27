{{-- CEP · iletişim sayfası --}}
<div class="ph-pagehero">
    <nav class="ph-crumb" aria-label="Gezinme">
        <a href="{{ route('home') }}">Ana Sayfa</a><span>/</span><span>İletişim</span>
    </nav>
    <span class="ph-eyebrow">Destek · Bize ulaşın</span>
    <h1>İletişim</h1>
    <p>Soruların, önerilerin ve firma ekleme taleplerin için buradan yaz.</p>
</div>

<section class="ph-sheet">
    <div class="ph-sheet__head"><h2>Mesaj formu</h2><span class="ph-meta">Yanıt 1 iş günü</span></div>
    <div style="padding:14px">
        @if(!empty($content))<div class="ph-prose" style="margin-bottom:14px">{!! $content !!}</div>@endif

        @if(session('success'))<div class="ph-note" style="margin:0 0 12px;border-left-color:var(--ph-ok)">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="ph-note" style="margin:0 0 12px;border-left-color:#ff5d5d">{{ session('error') }}</div>@endif

        @if($errors->any())
            <div class="ph-note" style="margin:0 0 12px;border-left-color:#ff5d5d">{{ $errors->first() }}</div>
        @endif

        <form class="ph-filter" style="padding:0; border:0" action="{{ route('contact.store') }}" method="POST">
            @csrf
            <label class="ph-field">Adınız *<input name="name" required value="{{ old('name') }}" autocomplete="name"></label>
            <label class="ph-field">E-posta<input name="email" type="email" value="{{ old('email') }}" autocomplete="email"></label>
            <label class="ph-field">Telefon<input name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel"></label>
            <label class="ph-field">Konu
                <select name="subject">
                    <option value="">Seçiniz…</option>
                    @foreach(['Üyelik Paketleri', 'Reklam ve Sponsorluk', 'Firma Ekleme', 'Bilgi Güncelleme', 'Diğer'] as $subject)
                        <option value="{{ $subject }}" @selected(old('subject', request('subject') === 'uyelik' ? 'Üyelik Paketleri' : '') === $subject)>{{ $subject }}</option>
                    @endforeach
                </select>
            </label>
            <label class="ph-field">Mesajınız *<textarea name="message" rows="6" required>{{ old('message') }}</textarea></label>
            <label class="ph-field">{{ $a }} + {{ $b }} = ? *<input name="captcha" required inputmode="numeric" autocomplete="off"></label>
            <button class="ph-btn ph-btn--wide" type="submit">Mesajı gönder →</button>
        </form>
    </div>
</section>

<div class="ph-quick">
    <a href="{{ route('listing.create') }}">Firma talep et</a>
    <a href="{{ route('packages.index') }}">Paketleri gör</a>
</div>

<div class="ph-note">Kişisel verilerin yalnızca bu talebi yanıtlamak için kullanılır; detaylar için gizlilik politikasına göz atabilirsin.</div>
