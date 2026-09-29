<div class="atlas-page">
    <div class="atlas-intro">
        <div class="atlas-intro-copy">
            <span class="atlas-eyebrow"><span class="atlas-live-dot"></span> TÜRKİYE FİRMA ATLASI</span>
            <h1>İşletmeleri <em>haritada</em> keşfet.</h1>
            <p>81 il, tek harita. Bir ile dokun, konumu belli firmaların pinlerini incele ve sana uygun işletmeye ulaş.</p>
        </div>
        <div class="atlas-stats" aria-label="Rehber istatistikleri">
            <div><strong>81</strong><span>il</span></div>
            <div><strong>{{ number_format($totalCompanies, 0, ',', '.') }}</strong><span>firma</span></div>
            <div><strong>{{ number_format($mappedCompanies, 0, ',', '.') }}</strong><span>harita pini</span></div>
        </div>
    </div>

    <div class="atlas-workspace" id="atlas-workspace"
        data-geo-url="{{ asset('data/turkey-provinces.geojson') }}"
        data-companies-url="{{ route('atlas.companies') }}"
        data-list-url="{{ route('companies.index') }}">
        <div class="atlas-map-panel">
            <div class="atlas-toolbar">
                <div class="atlas-toolbar-title"><span class="atlas-toolbar-icon">⌖</span><span>Türkiye haritası <small>İlleri seçerek keşfet</small></span></div>
                <button type="button" class="atlas-reset" id="atlas-reset">↗ Tüm Türkiye</button>
            </div>
            <div class="atlas-map-wrap">
                <div id="atlas-map" role="application" aria-label="İl sınırları ve firma pinleriyle Türkiye haritası"></div>
                <div class="atlas-map-label" id="atlas-map-label">Bir il seç veya haritayı yakınlaştır</div>
                <div class="atlas-legend"><span class="atlas-legend-line"></span> İl sınırı <span class="atlas-legend-pin"></span> Firma konumu</div>
            </div>
            <div class="atlas-map-foot"><span id="atlas-map-status" aria-live="polite">Harita hazırlanıyor…</span><span>İl sınırları: <a href="https://www.geoboundaries.org/" target="_blank" rel="noopener noreferrer" style="color:inherit;text-decoration:underline">geoBoundaries</a> · Haritayı sürükleyip yakınlaştırabilirsin</span></div>
        </div>

        <aside class="atlas-sidebar">
            <div class="atlas-sidebar-head">
                <span class="atlas-sidebar-kicker">KEŞİF PANELİ</span>
                <h2 id="atlas-current-title">Türkiye genelinde</h2>
                <p id="atlas-current-subtitle">İl seçerek firmalara yaklaş.</p>
            </div>
            <div class="atlas-filters">
                <label for="atlas-city">İl</label>
                <select id="atlas-city">
                    <option value="">Tüm iller</option>
                    @foreach($provinces as $code => $province)
                        <option value="{{ $code }}">{{ $province['name'] }} ({{ number_format($province['count'], 0, ',', '.') }})</option>
                    @endforeach
                </select>
                <label for="atlas-category">Kategori</label>
                <select id="atlas-category">
                    <option value="">Tüm kategoriler</option>
                    @foreach($atlasCategories as $category)
                        <option value="{{ $category->slug }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="atlas-results-head"><strong>Haritadaki firmalar</strong><span id="atlas-result-count">—</span></div>
            <div class="atlas-results" id="atlas-results" aria-live="polite"><p class="atlas-empty">Firmalar yükleniyor…</p></div>
            <a class="atlas-all-link" id="atlas-all-link" href="{{ route('companies.index') }}">Tüm firmaları listele <span>→</span></a>
        </aside>
    </div>

    <section class="atlas-provinces" aria-labelledby="atlas-provinces-title">
        <div class="atlas-provinces-head"><div><span class="atlas-sidebar-kicker">81 İL • TEK REHBER</span><h2 id="atlas-provinces-title">İline doğrudan git</h2></div><p>Haritada gezinmek yerine şehrini seçebilirsin.</p></div>
        <div class="atlas-province-grid">
            @foreach($provinces as $code => $province)
                @if($province['url'])
                    <a href="{{ $province['url'] }}"><span>{{ $province['name'] }}</span><small>{{ number_format($province['count'], 0, ',', '.') }} firma</small></a>
                @else
                    <span class="atlas-province-unavailable"><span>{{ $province['name'] }}</span><small>Yakında</small></span>
                @endif
            @endforeach
        </div>
    </section>
</div>
