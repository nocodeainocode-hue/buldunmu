<div class="kat kat-page">
    @include('partials.catalog.band', [
        'crumb' => 'İletişim',
        'eyebrow' => 'Künye / Bize yazın',
        'title' => 'İletişim',
        'description' => 'Sorularınız, önerileriniz ve paket talepleriniz için bize ulaşın.',
    ])
    <div class="kat-wrap" style="padding-top:44px;max-width:860px">
        @if($content)
            <div class="kat-prose" style="margin-bottom:34px">{!! $content !!}</div>
        @endif

        @if(session('success'))
            <div class="kat-note" style="margin-bottom:22px">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="kat-note" style="border-left-color:var(--accent);margin-bottom:22px">{{ session('error') }}</div>
        @endif

        <form class="kat-form" action="{{ route('contact.store') }}" method="POST">
            @csrf
            <label>Adınız *<input type="text" name="name" required value="{{ old('name') }}"></label>
            <label>E-posta<input type="email" name="email" value="{{ old('email') }}"></label>
            <label class="kat-wide">Konu
                <select name="subject">
                    <option value="">Seçiniz...</option>
                    @foreach(['Üyelik Paketleri', 'Reklam ve Sponsorluk', 'Firma Ekleme', 'Diğer'] as $subject)
                        <option value="{{ $subject }}" @selected(old('subject', request('subject') === 'uyelik' ? 'Üyelik Paketleri' : '') === $subject)>{{ $subject }}</option>
                    @endforeach
                </select>
            </label>
            <label class="kat-wide">Mesajınız *<textarea name="message" rows="6" required>{{ old('message') }}</textarea></label>
            <label>{{ $a }} + {{ $b }} = ? *<input type="text" name="captcha" required inputmode="numeric"></label>
            <div class="kat-wide"><button class="kat-btn" type="submit">Mesajı gönder →</button></div>
        </form>
    </div>
</div>
