<div class="bd bd-page">
    <div class="bd-wrap" style="max-width:860px">
        @include('partials.board.band', [
            'crumb' => 'İletişim',
            'tag' => '✉️ Bize yaz',
            'title' => 'İletişim',
            'description' => 'Soru, öneri ve paket talepleriniz için buradayız.',
        ])
        <div class="bd-panel">
            @if($content)<div class="bd-prose" style="margin-bottom:22px">{!! $content !!}</div>@endif
            @if(session('success'))<div class="bd-note-box" style="margin-bottom:16px">{{ session('success') }}</div>@endif
            @if(session('error'))<div class="bd-note-box" style="margin-bottom:16px;border-left-color:var(--accent)">{{ session('error') }}</div>@endif
            <form class="bd-form" action="{{ route('contact.store') }}" method="POST">
                @csrf
                <label>Adın *<input type="text" name="name" required value="{{ old('name') }}"></label>
                <label>E-posta<input type="email" name="email" value="{{ old('email') }}"></label>
                <label class="bd-wide">Konu
                    <select name="subject">
                        <option value="">Seçiniz...</option>
                        @foreach(['Üyelik Paketleri', 'Reklam ve Sponsorluk', 'Firma Ekleme', 'Diğer'] as $subject)
                            <option value="{{ $subject }}" @selected(old('subject', request('subject') === 'uyelik' ? 'Üyelik Paketleri' : '') === $subject)>{{ $subject }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="bd-wide">Mesajın *<textarea name="message" rows="6" required>{{ old('message') }}</textarea></label>
                <label>{{ $a }} + {{ $b }} = ? *<input type="text" name="captcha" required inputmode="numeric"></label>
                <div class="bd-wide"><button class="bd-btn bd-btn--hot" type="submit">Mesajı gönder →</button></div>
            </form>
        </div>
    </div>
</div>
