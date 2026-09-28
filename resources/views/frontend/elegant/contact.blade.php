<div class="el el-page">
    @include('partials.elegant.band', [
        'crumb' => 'İletişim',
        'eyebrow' => 'Bilgi merkezi / Bize yazın',
        'title' => 'İletişim',
        'description' => 'Sorularınız, önerileriniz ve üyelik talepleriniz için bize ulaşın.',
    ])
    <div class="el-wrap">
        <article class="el-box" style="margin-top:34px;max-width:820px;margin-inline:auto">
            @if($content)
                <div class="el-prose" style="margin-bottom:26px">{!! $content !!}</div>
            @endif

            @if(session('success'))
                <div class="el-note" style="margin-bottom:18px">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="el-note" style="margin-bottom:18px">{{ session('error') }}</div>
            @endif

            <form class="el-form" action="{{ route('contact.store') }}" method="POST">
                @csrf
                <label>Adınız *<input type="text" name="name" required value="{{ old('name') }}"></label>
                <label>E-posta<input type="email" name="email" value="{{ old('email') }}"></label>
                <label class="el-wide">Konu
                    <select name="subject">
                        <option value="">Seçiniz...</option>
                        @foreach(['Üyelik Paketleri', 'Reklam ve Sponsorluk', 'Firma Ekleme', 'Diğer'] as $subject)
                            <option value="{{ $subject }}" @selected(old('subject', request('subject') === 'uyelik' ? 'Üyelik Paketleri' : '') === $subject)>{{ $subject }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="el-wide">Mesajınız *<textarea name="message" rows="6" required>{{ old('message') }}</textarea></label>
                <label>{{ $a }} + {{ $b }} = ? *<input type="text" name="captcha" required inputmode="numeric"></label>
                <div class="el-wide" style="display:flex;align-items:end"><button class="el-btn" type="submit">Mesajı gönderin</button></div>
            </form>
        </article>
    </div>
</div>
