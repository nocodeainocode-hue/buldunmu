@extends('layouts.app')

@section('title', 'Firmanızı Ücretsiz Ekleyin')
@section('robots', 'noindex,follow')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-10 sm:px-6">
    <div class="mb-7">
        <p class="text-xs font-black uppercase tracking-widest" style="color:var(--primary);">Ücretsiz firma kaydı</p>
        <h1 class="mt-2 text-3xl font-black sm:text-4xl" style="color:var(--text);">Firmanızı ekleyin, profilinizi yönetin</h1>
        <p class="mt-3 max-w-2xl leading-7" style="color:var(--text_muted);">Bu rehber için hesabınız ve firma profiliniz birlikte oluşturulur. Yayın öncesi kısa bir inceleme yapılır.</p>
    </div>

    <form action="{{ route('owner.register.store') }}" method="POST" class="grid gap-6 lg:grid-cols-[1fr_1.35fr]">
        @csrf
        <section class="rounded-3xl border p-6" style="border-color:var(--border);background:var(--bg_card);box-shadow:var(--card_shadow);">
            <h2 class="text-xl font-black" style="color:var(--text);">Hesabınız</h2>
            <div class="mt-5 space-y-4">
                <label class="block text-sm font-bold" style="color:var(--text);">Ad soyad *<input name="name" value="{{ old('name') }}" required class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);"></label>
                <label class="block text-sm font-bold" style="color:var(--text);">E-posta *<input name="email" type="email" value="{{ old('email') }}" required class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);"></label>
                <label class="block text-sm font-bold" style="color:var(--text);">Şifre *<input name="password" type="password" required class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);"></label>
                <label class="block text-sm font-bold" style="color:var(--text);">Şifre tekrar *<input name="password_confirmation" type="password" required class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);"></label>
            </div>
        </section>

        <section class="rounded-3xl border p-6" style="border-color:var(--border);background:var(--bg_card);box-shadow:var(--card_shadow);">
            <h2 class="text-xl font-black" style="color:var(--text);">Firma bilgileri</h2>
            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                <label class="block text-sm font-bold sm:col-span-2" style="color:var(--text);">Firma adı *<input name="company_name" value="{{ old('company_name') }}" required class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);"></label>
                <label class="block text-sm font-bold" style="color:var(--text);">Kategori *<select name="category_id" required class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);"><option value="">Seçiniz</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>@endforeach</select></label>
                <label class="block text-sm font-bold" style="color:var(--text);">Şehir *<select id="city_select" name="city_id" required class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);"><option value="">Seçiniz</option>@foreach($cities as $city)<option value="{{ $city->id }}" @selected(old('city_id') == $city->id)>{{ $city->name }}</option>@endforeach</select></label>
                <label class="block text-sm font-bold" style="color:var(--text);">İlçe<select id="district_select" name="district_id" class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);"></select></label>
                <label class="block text-sm font-bold" style="color:var(--text);">Telefon<input name="phone" value="{{ old('phone') }}" class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);"></label>
                <label class="block text-sm font-bold" style="color:var(--text);">WhatsApp<input name="whatsapp" value="{{ old('whatsapp') }}" class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);"></label>
                <label class="block text-sm font-bold" style="color:var(--text);">Firma e-postası<input name="company_email" type="email" value="{{ old('company_email') }}" class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);"></label>
                <label class="block text-sm font-bold" style="color:var(--text);">Web sitesi<input name="website" type="url" value="{{ old('website') }}" placeholder="https://" class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);"></label>
                <label class="block text-sm font-bold sm:col-span-2" style="color:var(--text);">Adres<textarea name="address" rows="2" class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);">{{ old('address') }}</textarea></label>
                <label class="block text-sm font-bold sm:col-span-2" style="color:var(--text);">Kısa açıklama<textarea name="short_description" rows="3" class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);">{{ old('short_description') }}</textarea></label>
            </div>
            @if($errors->any())<p class="mt-4 text-sm font-bold text-red-600">Lütfen zorunlu alanları ve girdiğiniz bilgileri kontrol edin.</p>@endif
            <button class="mt-6 w-full rounded-xl px-5 py-3 font-black text-white" style="background:var(--primary);">Hesabımı ve Firma Profilimi Oluştur</button>
        </section>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const city = document.getElementById('city_select');
    const district = document.getElementById('district_select');
    const districts = @json($districts);
    const selected = @json((string) old('district_id'));
    const refresh = () => {
        district.innerHTML = '<option value="">Seçiniz</option>';
        districts.filter(item => Number(item.city_id) === Number(city.value)).forEach(item => district.add(new Option(item.name, item.id, false, String(item.id) === selected)));
        district.disabled = !city.value;
    };
    city.addEventListener('change', refresh); refresh();
});
</script>
@endsection
