<div class="dep dep-page">
    @include('partials.departures.page-hero', [
        'crumb' => 'İletişim',
        'eyebrow' => 'Servis bürosu / Bize yazın',
        'title' => 'İletişim peronu',
        'description' => 'Sorularınız, önerileriniz ve paket talepleriniz için bize ulaşın.',
    ])
    <div class="dep-wrap dep-info">
        <article class="dep-panel">
            @if($content)
                <div class="dep-prose" style="margin-top:0;padding-top:0;border-top:0">{!! $content !!}</div>
            @endif

            @if(session('success'))
                <div class="dep-note" style="border-left-color:var(--mint)">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="dep-note" style="border-left-color:var(--signal)">{{ session('error') }}</div>
            @endif

            <form class="dep-contact-form" action="{{ route('contact.store') }}" method="POST">
                @csrf
                <label>Adınız *<input type="text" name="name" required value="{{ old('name') }}"></label>
                <label>E-posta<input type="email" name="email" value="{{ old('email') }}"></label>
                <label class="wide">Konu
                    <select name="subject">
                        <option value="">Seçiniz...</option>
                        @foreach(['Üyelik Paketleri', 'Reklam ve Sponsorluk', 'Firma Ekleme', 'Diğer'] as $subject)
                            <option value="{{ $subject }}" @selected(old('subject', request('subject') === 'uyelik' ? 'Üyelik Paketleri' : '') === $subject)>{{ $subject }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="wide">Mesajınız *<textarea name="message" rows="6" required>{{ old('message') }}</textarea></label>
                <label>{{ $a }} + {{ $b }} = ? *<input type="text" name="captcha" required inputmode="numeric" style="max-width:150px"></label>
                <div class="wide"><button class="dep-btn" type="submit">Mesajı gönder →</button></div>
            </form>
        </article>
    </div>
</div>
