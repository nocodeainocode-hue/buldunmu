<div class="sig sig-page">
    @include('partials.signal.band', [
        'crumb' => 'İletişim',
        'eyebrow' => 'Bilgi merkezi / Bize yazın',
        'title' => 'İletişim ekranı',
        'description' => 'Sorularınız, önerileriniz ve paket talepleriniz için bize ulaşın.',
    ])
    <div class="sig-wrap" style="padding-top:26px;max-width:820px">
        <article class="sig-panel">
            <div class="sig-panel__body">
                @if($content)
                    <div class="sig-prose" style="margin-bottom:20px">{!! $content !!}</div>
                @endif

                @if(session('success'))
                    <div class="sig-note" style="border-left-color:var(--secondary);margin-bottom:16px">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="sig-note" style="border-left-color:var(--accent);margin-bottom:16px">{{ session('error') }}</div>
                @endif

                <form class="sig-form" action="{{ route('contact.store') }}" method="POST">
                    @csrf
                    <label>Adınız *<input type="text" name="name" required value="{{ old('name') }}"></label>
                    <label>E-posta<input type="email" name="email" value="{{ old('email') }}"></label>
                    <label class="sig-wide">Konu
                        <select name="subject">
                            <option value="">Seçiniz...</option>
                            @foreach(['Üyelik Paketleri', 'Reklam ve Sponsorluk', 'Firma Ekleme', 'Diğer'] as $subject)
                                <option value="{{ $subject }}" @selected(old('subject', request('subject') === 'uyelik' ? 'Üyelik Paketleri' : '') === $subject)>{{ $subject }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="sig-wide">Mesajınız *<textarea name="message" rows="6" required>{{ old('message') }}</textarea></label>
                    <label>{{ $a }} + {{ $b }} = ? *<input type="text" name="captcha" required inputmode="numeric"></label>
                    <div class="sig-wide"><button class="sig-btn" type="submit">Mesajı gönder →</button></div>
                </form>
            </div>
        </article>
    </div>
</div>
