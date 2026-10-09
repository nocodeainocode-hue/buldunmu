@extends('layouts.app')

@section('title', 'Firmanızı Ücretsiz Ekleyin')
@section('meta_description', 'Firmanızı ücretsiz ekleyin. İşletme profilinizi oluşturun, iletişim bilgilerinizi yönetin ve müşterilerin sizi bulmasını kolaylaştırın.')
@section('canonical', route('owner.register'))

@section('content')
@php
    $accountErrors = $errors->hasAny(['name', 'email', 'password']);
    $initialStep = $accountErrors ? 2 : 1;
@endphp

<div class="ph-owner-form mx-auto max-w-6xl px-4 py-8 sm:px-6 sm:py-12">
    <div class="mx-auto mb-8 max-w-3xl text-center">
        <span class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-black" style="background:var(--primary_light);color:var(--primary);"><span aria-hidden="true">✓</span> {{ $content['badge'] }}</span>
        <h1 class="mt-4 text-3xl font-black sm:text-4xl" style="color:var(--text);">{{ $content['headline'] }}</h1>
        <p class="mx-auto mt-3 max-w-2xl text-sm leading-6 sm:text-base" style="color:var(--text_muted);">{{ $content['subheadline'] }}</p>
    </div>

    <div class="grid items-start gap-6 lg:grid-cols-[minmax(0,1fr)_20rem]">
        <form id="owner-registration-form" action="{{ route('owner.register.store') }}" method="POST" class="rounded-lg border p-5 sm:p-7" style="border-color:var(--border);background:var(--bg_card);box-shadow:var(--card_shadow);" data-initial-step="{{ $initialStep }}">
            @csrf
@include('partials.honeypot')

            <div class="mb-7" aria-label="Kayıt adımları">
                <div class="mb-3 flex items-center justify-between text-xs font-black">
                    <span id="registration-step-label" style="color:var(--primary);">1. Firma bilgileri</span>
                    <span id="registration-step-count" style="color:var(--text_muted);">1 / 2</span>
                </div>
                <div class="h-1.5 overflow-hidden rounded-full" style="background:var(--border);">
                    <div id="registration-progress" class="h-full rounded-full transition-all duration-300" style="width:50%;background:var(--primary);"></div>
                </div>
            </div>

            <section data-registration-step="1">
                <div class="mb-5">
                    <h2 class="text-xl font-black" style="color:var(--text);">Firmanızı tanıyalım</h2>
                    <p class="mt-1 text-sm" style="color:var(--text_muted);">Yıldızlı alanlar profilinizi oluşturmak için yeterli.</p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <label class="block text-sm font-bold sm:col-span-2" style="color:var(--text);">Firma adı *
                        <input name="company_name" value="{{ old('company_name') }}" required autocomplete="organization" placeholder="Örn. Lotus Su Arıtma" class="mt-1.5 w-full rounded-lg border px-4 py-3" style="border-color:var(--border);background:var(--bg);">
                        @error('company_name')<span class="mt-1 block text-xs text-red-600">{{ $message }}</span>@enderror
                    </label>

                    <label class="block text-sm font-bold" style="color:var(--text);">Kategori *
                        @if($categories->count() > 12)
                            <input id="category_search" type="search" placeholder="Kategori ara… (örn. su arıtma)" autocomplete="off" class="mt-1.5 w-full rounded-lg border px-4 py-2.5 text-sm" style="border-color:var(--border);background:var(--bg);">
                        @endif
                        <select id="category_select" name="category_id" required class="mt-1.5 w-full rounded-lg border px-4 py-3" style="border-color:var(--border);background:var(--bg);">
                            <option value="">Kategori seçin</option>
                            @foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>@endforeach
                            <option value="other" @selected(old('category_id') === 'other')>Kategorimi bulamadım</option>
                        </select>
                        @error('category_id')<span class="mt-1 block text-xs text-red-600">{{ $message }}</span>@enderror
                    </label>

                    <label id="requested_category_field" class="block text-sm font-bold sm:col-span-2" style="color:var(--text);">Firmanız hangi alanda hizmet veriyor? *
                        <input id="requested_category" name="requested_category" value="{{ old('requested_category') }}" maxlength="120" placeholder="Örn. Drone çekimi ve havadan görüntüleme" class="mt-1.5 w-full rounded-lg border px-4 py-3" style="border-color:var(--border);background:var(--bg);">
                        <span class="mt-1 block text-xs font-normal" style="color:var(--text_muted);">Başvurunuzu incelerken en uygun kategoriyle eşleştireceğiz.</span>
                        @error('requested_category')<span class="mt-1 block text-xs text-red-600">{{ $message }}</span>@enderror
                    </label>

                    <label class="block text-sm font-bold" style="color:var(--text);">Şehir *
                        <select id="city_select" name="city_id" required class="mt-1.5 w-full rounded-lg border px-4 py-3" style="border-color:var(--border);background:var(--bg);">
                            <option value="">Şehir seçin</option>
                            @foreach($cities as $city)<option value="{{ $city->id }}" @selected(old('city_id') == $city->id)>{{ $city->name }}</option>@endforeach
                        </select>
                        @error('city_id')<span class="mt-1 block text-xs text-red-600">{{ $message }}</span>@enderror
                    </label>

                    <label class="block text-sm font-bold" style="color:var(--text);">İlçe <span class="font-normal" style="color:var(--text_muted);">(isteğe bağlı)</span>
                        <select id="district_select" name="district_id" class="mt-1.5 w-full rounded-lg border px-4 py-3" style="border-color:var(--border);background:var(--bg);"></select>
                    </label>

                    <label class="block text-sm font-bold" style="color:var(--text);">Telefon *
                        <input name="phone" type="tel" value="{{ old('phone') }}" required inputmode="tel" autocomplete="tel" placeholder="05xx xxx xx xx" pattern="[0-9+()\s.\-]{10,20}" title="Geçerli bir telefon numarası girin, örn. 0532 123 45 67" class="mt-1.5 w-full rounded-lg border px-4 py-3" style="border-color:var(--border);background:var(--bg);">
                        @error('phone')<span class="mt-1 block text-xs text-red-600">{{ $message }}</span>@enderror
                    </label>

                    <div class="block text-sm font-bold" style="color:var(--text);">
                        <span>WhatsApp</span>
                        <label class="mt-1.5 flex items-center gap-2 rounded-lg border px-4 py-3 font-semibold" style="border-color:var(--border);background:var(--bg);cursor:pointer;">
                            <input type="checkbox" id="same_whatsapp" class="h-4 w-4" @checked(! old('whatsapp') || old('whatsapp') === old('phone'))>
                            <span>Bu numara WhatsApp'ta da var</span>
                        </label>
                    </div>
                    <label id="whatsapp_field" class="block text-sm font-bold sm:col-span-2" style="color:var(--text);" hidden>Farklı WhatsApp numarası
                        <input name="whatsapp" value="{{ old('whatsapp') }}" inputmode="tel" placeholder="05xx xxx xx xx" pattern="[0-9+()\s.\-]{10,20}" title="Geçerli bir telefon numarası girin, örn. 0532 123 45 67" class="mt-1.5 w-full rounded-lg border px-4 py-3" style="border-color:var(--border);background:var(--bg);">
                        @error('whatsapp')<span class="mt-1 block text-xs text-red-600">{{ $message }}</span>@enderror
                    </label>
                </div>

                <button type="button" data-next-step class="mt-6 flex w-full items-center justify-center gap-2 rounded-lg px-5 py-3.5 font-black text-white transition hover:opacity-90" style="background:var(--primary);">Devam Et <span aria-hidden="true">→</span></button>
                <p class="mt-3 text-center text-xs" style="color:var(--text_muted);">Kredi kartı gerekmez. Kayıt ücretsizdir.</p>
            </section>

            <section data-registration-step="2">
                <div class="mb-5">
                    <h2 class="text-xl font-black" style="color:var(--text);">Panel hesabınızı oluşturun</h2>
                    <p class="mt-1 text-sm" style="color:var(--text_muted);">Firma bilgilerinizi güncellemek ve başvurunuzu takip etmek için kullanacaksınız.</p>
                </div>

                <div class="space-y-4">
                    <label class="block text-sm font-bold" style="color:var(--text);">Ad soyad *
                        <input name="name" value="{{ old('name') }}" required autocomplete="name" class="mt-1.5 w-full rounded-lg border px-4 py-3" style="border-color:var(--border);background:var(--bg);">
                        @error('name')<span class="mt-1 block text-xs text-red-600">{{ $message }}</span>@enderror
                    </label>

                    <label class="block text-sm font-bold" style="color:var(--text);">E-posta *
                        <input name="email" type="email" value="{{ old('email') }}" required autocomplete="email" placeholder="ornek@firma.com" class="mt-1.5 w-full rounded-lg border px-4 py-3" style="border-color:var(--border);background:var(--bg);">
                        @error('email')<span class="mt-1 block text-xs text-red-600">{{ $message }}</span>@enderror
                    </label>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <label class="block text-sm font-bold" style="color:var(--text);">Şifre *
                            <input name="password" type="password" required minlength="8" autocomplete="new-password" class="mt-1.5 w-full rounded-lg border px-4 py-3" style="border-color:var(--border);background:var(--bg);">
                            @error('password')<span class="mt-1 block text-xs text-red-600">{{ $message }}</span>@enderror
                        </label>
                        <label class="block text-sm font-bold" style="color:var(--text);">Şifre tekrar *
                            <input name="password_confirmation" type="password" required minlength="8" autocomplete="new-password" class="mt-1.5 w-full rounded-lg border px-4 py-3" style="border-color:var(--border);background:var(--bg);">
                        </label>
                    </div>
                </div>

                <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row">
                    <button type="button" data-previous-step class="rounded-lg border px-5 py-3.5 font-black" style="border-color:var(--border);color:var(--text);">Geri</button>
                    <button type="submit" class="flex-1 rounded-lg px-5 py-3.5 font-black text-white transition hover:opacity-90" style="background:var(--primary);">{{ $content['button_text'] }}</button>
                </div>
                <p class="mt-4 text-center text-xs leading-5" style="color:var(--text_muted);">Kaydı tamamlayarak <a href="{{ route('pages.terms') }}" class="font-bold underline">kullanım şartlarını</a> ve <a href="{{ route('pages.privacy') }}" class="font-bold underline">gizlilik politikasını</a> kabul etmiş olursunuz. Profiliniz kısa bir incelemenin ardından yayınlanır.</p>
            </section>

            @if($errors->any())
                <div class="mt-5 rounded-lg border px-4 py-3 text-sm font-bold text-red-700" style="border-color:#fecaca;background:#fef2f2;">Bilgileriniz kaybolmadı. İşaretli alanları kontrol edip tekrar deneyin.</div>
            @endif
        </form>

        <aside class="space-y-4 lg:sticky lg:top-24">
            <div class="rounded-lg border p-5" style="border-color:var(--border);background:var(--bg_card);" aria-live="polite">
                <p class="text-xs font-black uppercase tracking-widest" style="color:var(--primary);">Profil önizlemesi</p>
                <div class="mt-3 rounded-lg border p-4" style="border-color:var(--border);background:var(--bg);">
                    <p id="preview-name" class="text-base font-black" style="color:var(--text);" data-empty="Firma adınız">Firma adınız</p>
                    <p id="preview-meta" class="mt-1 text-xs" style="color:var(--text_muted);" data-empty="Kategori · Şehir">Kategori · Şehir</p>
                    <div class="mt-3 flex gap-2 text-xs font-black">
                        <span class="rounded-lg px-3 py-2 text-white" style="background:var(--primary);">Ara <span id="preview-phone" class="font-semibold"></span></span>
                        <span id="preview-whatsapp" class="rounded-lg border px-3 py-2" style="border-color:var(--border);color:var(--text);" hidden>WhatsApp</span>
                    </div>
                </div>
                <p class="mt-2 text-xs" style="color:var(--text_muted);">Ziyaretçiler firmanızı böyle görecek. Yazdıkça güncellenir.</p>
            </div>

            <div class="rounded-lg border p-5" style="border-color:var(--border);background:var(--bg_card);">
                <p class="text-xs font-black uppercase tracking-widest" style="color:var(--primary);">Firma hesabınızla</p>
                <ul class="mt-4 space-y-4">
                    @foreach($content['benefits'] as $benefit)
                    <li class="flex gap-3"><span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg font-black" style="background:var(--primary_light);color:var(--primary);">✓</span><span><strong class="block text-sm" style="color:var(--text);">{{ $benefit['title'] }}</strong>@if(! empty($benefit['text']))<small class="mt-0.5 block leading-5" style="color:var(--text_muted);">{{ $benefit['text'] }}</small>@endif</span></li>
                    @endforeach
                </ul>
                @if($companyCount)
                    <p class="mt-4 border-t pt-4 text-xs font-bold" style="border-color:var(--border);color:var(--text_muted);">Bu rehberde <span style="color:var(--text);">{{ number_format($companyCount, 0, ',', '.') }}</span> firma yayında.</p>
                @endif
            </div>

            <div class="rounded-lg border p-5 text-center" style="border-color:var(--border);background:var(--bg);">
                <p class="text-sm" style="color:var(--text_muted);">Zaten hesabınız var mı?</p>
                <a href="{{ route('owner.login') }}" class="mt-2 inline-flex font-black" style="color:var(--primary);">Firma paneline giriş yapın →</a>
            </div>
        </aside>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('owner-registration-form');
    const panels = [...form.querySelectorAll('[data-registration-step]')];
    const progress = document.getElementById('registration-progress');
    const stepLabel = document.getElementById('registration-step-label');
    const stepCount = document.getElementById('registration-step-count');
    let currentStep = Number(form.dataset.initialStep || 1);

    const showStep = (step, shouldScroll = true) => {
        currentStep = step;
        panels.forEach(panel => panel.hidden = Number(panel.dataset.registrationStep) !== step);
        progress.style.width = step === 1 ? '50%' : '100%';
        stepLabel.textContent = step === 1 ? '1. Firma bilgileri' : '2. Panel hesabı';
        stepCount.textContent = `${step} / 2`;
        if (shouldScroll) form.scrollIntoView({ behavior: 'smooth', block: 'start' });
    };

    const firstStepIsValid = () => {
        const fields = panels[0].querySelectorAll('input, select, textarea');
        for (const field of fields) {
            if (!field.checkValidity()) {
                field.reportValidity();
                return false;
            }
        }
        return true;
    };

    // 1. adım geçildiğinde bilgileri "yarım başvuru" olarak kaydeder; başarısız olsa bile akış devam eder.
    const sendLead = () => {
        if (typeof syncWhatsapp === 'function') syncWhatsapp();
        const data = new FormData();
        data.append('_token', form.querySelector('input[name=_token]')?.value || '');
        ['company_name', 'category_id', 'requested_category', 'city_id', 'district_id', 'phone', 'whatsapp'].forEach((name) => {
            const field = form.elements[name];
            if (field && field.value !== '') data.append(name, field.value);
        });
        fetch(@json(route('owner.register.lead')), {
            method: 'POST', body: data, keepalive: true,
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        }).catch(() => {});
    };

    const goToSecondStep = () => {
        if (!firstStepIsValid()) return;
        sendLead();
        showStep(2);
    };

    form.querySelector('[data-next-step]').addEventListener('click', goToSecondStep);
    form.querySelector('[data-previous-step]').addEventListener('click', () => showStep(1));
    form.addEventListener('submit', (event) => {
        if (currentStep === 1) {
            event.preventDefault();
            goToSecondStep();
        }
    });

    const city = document.getElementById('city_select');
    const district = document.getElementById('district_select');
    const districts = @json($districts);
    const selectedDistrict = @json((string) old('district_id'));
    const refreshDistricts = () => {
        district.innerHTML = '<option value="">İlçe seçin</option>';
        districts.filter(item => Number(item.city_id) === Number(city.value)).forEach(item => district.add(new Option(item.name, item.id, false, String(item.id) === selectedDistrict)));
        district.disabled = !city.value;
    };

    city.addEventListener('change', refreshDistricts);
    refreshDistricts();

    const category = document.getElementById('category_select');
    const requestedCategoryField = document.getElementById('requested_category_field');
    const requestedCategory = document.getElementById('requested_category');
    const refreshRequestedCategory = () => {
        const isMissing = category.value === 'other';
        requestedCategoryField.hidden = !isMissing;
        requestedCategory.required = isMissing;
    };

    category.addEventListener('change', refreshRequestedCategory);
    refreshRequestedCategory();

    // Adım 1 sadeleştirme: WhatsApp = telefon, kategori araması ve canlı profil önizlemesi.
    const phoneInput = form.elements['phone'];
    const whatsappInput = form.elements['whatsapp'];
    const sameWhatsapp = document.getElementById('same_whatsapp');
    const whatsappField = document.getElementById('whatsapp_field');
    const syncWhatsapp = () => {
        whatsappField.hidden = sameWhatsapp.checked;
        if (sameWhatsapp.checked) whatsappInput.value = phoneInput.value;
    };
    sameWhatsapp.addEventListener('change', syncWhatsapp);
    phoneInput.addEventListener('input', () => { if (sameWhatsapp.checked) whatsappInput.value = phoneInput.value; });
    syncWhatsapp();

    const categorySearch = document.getElementById('category_search');
    if (categorySearch) {
        const allOptions = [...category.options].map(option => ({ value: option.value, label: option.text }));
        const fold = (text) => text.toLocaleLowerCase('tr').normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/ı/g, 'i');
        categorySearch.addEventListener('input', () => {
            const needle = fold(categorySearch.value.trim());
            const selected = category.value;
            category.innerHTML = '';
            allOptions.filter(option => option.value === '' || option.value === 'other' || option.value === selected || fold(option.label).includes(needle))
                .forEach(option => category.add(new Option(option.label, option.value, false, option.value === selected)));
            category.value = selected;
            if (needle && category.options.length === 3 && !selected) category.selectedIndex = 1;
            category.dispatchEvent(new Event('change'));
        });
    }

    const previewName = document.getElementById('preview-name');
    const previewMeta = document.getElementById('preview-meta');
    const previewPhone = document.getElementById('preview-phone');
    const previewWhatsapp = document.getElementById('preview-whatsapp');
    const updatePreview = () => {
        previewName.textContent = form.elements['company_name'].value.trim() || previewName.dataset.empty;
        const categoryText = category.value && category.value !== 'other' ? category.options[category.selectedIndex].text : (category.value === 'other' ? requestedCategory.value.trim() : '');
        const cityText = city.value ? city.options[city.selectedIndex].text : '';
        previewMeta.textContent = [categoryText, cityText].filter(Boolean).join(' · ') || previewMeta.dataset.empty;
        previewPhone.textContent = phoneInput.value.trim();
        previewWhatsapp.hidden = !(sameWhatsapp.checked ? phoneInput.value.trim() : whatsappInput.value.trim());
    };
    form.addEventListener('input', updatePreview);
    form.addEventListener('change', updatePreview);
    updatePreview();
    showStep(currentStep, false);
});
</script>
@endsection
