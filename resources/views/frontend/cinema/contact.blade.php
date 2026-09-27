<div class="cinema cinema-page">
    @include('partials.cinema.page-hero', ['crumb' => 'İletişim', 'eyebrow' => 'Sahne arkası / Bize ulaşın', 'title' => 'İletişim', 'description' => 'Sorularınız ve önerileriniz için bize yazın.'])
    <div class="cinema-wrap cinema-info"><div class="cinema-panel">
        @if($content)<div class="cinema-prose">{!! $content !!}</div>@endif
        @if(session('success'))<div style="margin-bottom:16px;padding:12px;background:#e8f5e9">{{ session('success') }}</div>@endif
        @if(session('error'))<div style="margin-bottom:16px;padding:12px;background:#fde8e6">{{ session('error') }}</div>@endif
        <form class="cinema-contact-form" action="{{ route('contact.store') }}" method="POST">@csrf
            <label>Adınız *<input name="name" required value="{{ old('name') }}"></label>
            <label>E-posta<input name="email" type="email" value="{{ old('email') }}"></label>
            <label class="wide">Konu<select name="subject"><option value="">Seçiniz...</option>@foreach(['Üyelik Paketleri','Reklam ve Sponsorluk','Firma Ekleme','Diğer'] as $subject)<option value="{{ $subject }}" @selected(old('subject', request('subject') === 'uyelik' ? 'Üyelik Paketleri' : '') === $subject)>{{ $subject }}</option>@endforeach</select></label>
            <label class="wide">Mesajınız *<textarea name="message" rows="6" required>{{ old('message') }}</textarea></label>
            <label>{{ $a }} + {{ $b }} = ? *<input name="captcha" required inputmode="numeric" style="max-width:145px"></label>
            <div class="wide"><button class="cinema-btn" type="submit">Mesajı gönder ↗</button></div>
        </form>
    </div></div>
</div>
