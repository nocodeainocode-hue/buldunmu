@php
    $mapId = $mapId ?? 'company-map-' . uniqid();
    $height = $height ?? '520px';
    $mapCompanies = collect($companies ?? [])
        ->filter(fn($company) => $company->latitude && $company->longitude)
        ->values()
        ->map(fn($company) => [
            'name' => $company->name,
            'slug' => $company->slug,
            'category' => $company->category->name ?? null,
            'city' => $company->city->name ?? null,
            'district' => $company->district->name ?? null,
            'phone' => $company->phone,
            'lat' => (float) $company->latitude,
            'lng' => (float) $company->longitude,
            'url' => route('companies.show', $company->slug),
        ]);
@endphp

@once
    @push('head')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" defer></script>
    @endpush
@endonce

<div class="overflow-hidden rounded-3xl border bg-white shadow-xl" style="border-color:var(--border);">
    @if($mapCompanies->isNotEmpty())
        <div id="{{ $mapId }}" style="height:{{ $height }};min-height:360px;"></div>
    @else
        <div class="flex items-center justify-center p-10 text-center" style="height:{{ $height }};min-height:360px;background:var(--bg);">
            <div>
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl text-2xl font-black text-white" style="background:var(--primary);">M</div>
                <h3 class="text-lg font-black" style="color:var(--text);">Harita için koordinat bekleniyor</h3>
                <p class="mt-2 max-w-md text-sm" style="color:var(--text_muted);">Admin panelde firmalara Google Maps iframe kodu eklediğinde pinler otomatik olarak burada görünecek.</p>
            </div>
        </div>
    @endif
</div>

@if($mapCompanies->isNotEmpty())
    @push('scripts')
        <script>
            window.addEventListener('load', function () {
                const companies = @json($mapCompanies);
                const mapElement = document.getElementById(@json($mapId));

                if (!mapElement || !window.L || !companies.length) {
                    return;
                }

                const escapeHtml = (value) => String(value || '').replace(/[&<>"']/g, (char) => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;',
                }[char]));

                const map = L.map(mapElement, {
                    scrollWheelZoom: false,
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap',
                }).addTo(map);

                const bounds = [];

                companies.forEach((company) => {
                    const marker = L.marker([company.lat, company.lng]).addTo(map);
                    bounds.push([company.lat, company.lng]);
                    const location = [company.category, company.city, company.district].filter(Boolean).map(escapeHtml).join(' / ');

                    marker.bindPopup(`
                        <div style="min-width:190px">
                            <strong>${escapeHtml(company.name)}</strong>
                            ${location ? `<div style="margin-top:4px;color:#64748b;font-size:12px">${location}</div>` : ''}
                            ${company.phone ? `<div style="margin-top:6px;font-size:12px">Tel: ${escapeHtml(company.phone)}</div>` : ''}
                            <a href="${escapeHtml(company.url)}" style="display:inline-block;margin-top:8px;font-weight:700;color:#0d9488">Detaya git</a>
                        </div>
                    `);
                });

                if (bounds.length === 1) {
                    map.setView(bounds[0], 14);
                } else {
                    map.fitBounds(bounds, { padding: [30, 30] });
                }
            });
        </script>
    @endpush
@endif
