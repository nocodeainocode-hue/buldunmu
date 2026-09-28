<div class="ap-sec">
    <div class="ap-sec__head"><h2>İletişim</h2><span style="font-size:12px;color:var(--text_muted);font-weight:600">Bize yazın</span></div>
</div>
@if($content)
    <div class="ap-prose" style="padding-bottom:6px">{!! $content !!}</div>
@endif
@if(session('success'))
    <div class="ap-note" style="border-left-color:var(--secondary)">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="ap-note" style="border-left-color:var(--accent)">{{ session('error') }}</div>
@endif
<form class="ap-form" action="{{ route('contact.store') }}" method="POST">
    @csrf
    <label>Adınız *<input type="text" name="name" required value="{{ old('name') }}"></label>
    <label>E-posta<input type="email" name="email" value="{{ old('email') }}"></label>
    <label>Konu
        <select name="subject">
            <option value="">Seçiniz...</option>
            @foreach(['Üyelik Paketleri', 'Reklam ve Sponsorluk', 'Firma Ekleme', 'Diğer'] as $subject)
                <option value="{{ $subject }}" @selected(old('subject', request('subject') === 'uyelik' ? 'Üyelik Paketleri' : '') === $subject)>{{ $subject }}</option>
            @endforeach
        </select>
    </label>
    <label>Mesajınız *<textarea name="message" rows="5" required>{{ old('message') }}</textarea></label>
    <label>{{ $a }} + {{ $b }} = ? *<input type="text" name="captcha" required inputmode="numeric"></label>
    <button class="ap-btn ap-btn--block" type="submit">Mesajı gönder</button>
</form>
