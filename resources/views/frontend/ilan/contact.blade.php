<div class="ib ib-page">
    @include('partials.ilan.band', [
        'crumb' => 'İletişim',
        'eyebrow' => 'Bilgi merkezi / Bize yazın',
        'title' => 'İletişim',
    ])
    <div class="ib-wrap">
        <article class="ib-box" style="margin-top:14px;max-width:860px;margin-inline:auto">
            <div class="ib-box__body">
                @if($content)
                    <div class="ib-prose" style="margin-bottom:16px">{!! $content !!}</div>
                @endif

                @if(session('success'))
                    <div class="ib-note" style="border-left-color:var(--secondary);margin-bottom:14px">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="ib-note" style="border-left-color:var(--accent);margin-bottom:14px">{{ session('error') }}</div>
                @endif

                <form class="ib-form" action="{{ route('contact.store') }}" method="POST">
                    @csrf
                    <label>Adınız *<input type="text" name="name" required value="{{ old('name') }}"></label>
                    <label>E-posta<input type="email" name="email" value="{{ old('email') }}"></label>
                    <label class="ib-wide">Konu
                        <select name="subject">
                            <option value="">Seçiniz...</option>
                            @foreach(['Üyelik Paketleri', 'Reklam ve Sponsorluk', 'Firma Ekleme', 'Diğer'] as $subject)
                                <option value="{{ $subject }}" @selected(old('subject', request('subject') === 'uyelik' ? 'Üyelik Paketleri' : '') === $subject)>{{ $subject }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="ib-wide">Mesajınız *<textarea name="message" rows="6" required>{{ old('message') }}</textarea></label>
                    <label>{{ $a }} + {{ $b }} = ? *<input type="text" name="captcha" required inputmode="numeric"></label>
                    <div class="ib-wide"><button class="ib-btn" type="submit">Mesajı gönder</button></div>
                </form>
            </div>
        </article>
    </div>
</div>
