<section class="relative overflow-hidden py-16" style="background:linear-gradient(135deg,var(--accent),var(--primary));">
    <div class="absolute inset-0 opacity-20" style="background:radial-gradient(circle at 20% 20%, #fff, transparent 22%), radial-gradient(circle at 80% 30%, #fff, transparent 18%);"></div>
    <div class="relative mx-auto px-4 text-center sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
        <div class="mx-auto max-w-3xl">
            <div class="mb-3 text-xs font-black uppercase tracking-widest" style="color:white;">Firma ekle</div>
            <h2 class="text-3xl font-black tracking-tight sm:text-4xl" style="color:white;">Firmanızı binlerce potansiyel müşteriyle tanıtın</h2>
            <p class="mx-auto mt-4 max-w-2xl text-sm leading-7 sm:text-base" style="color:rgba(255,255,255,.82);">Ücretsiz kayıt olun, işletmenizin telefon, WhatsApp, web sitesi ve adres bilgilerini rehberde görünür hale getirin.</p>
            <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                <a href="{{ route('listing.create') }}" class="rounded-xl bg-white px-7 py-3 text-sm font-black shadow-xl transition hover:-translate-y-0.5" style="color:var(--primary);">Hemen firma ekle</a>
                <a href="{{ route('companies.index') }}" class="rounded-xl border border-white/30 px-7 py-3 text-sm font-black transition hover:bg-white/10" style="color:white;">Firmaları incele</a>
            </div>
        </div>
    </div>
</section>
