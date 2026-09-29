(() => {
    const root = document.getElementById('atlas-workspace');
    if (!root) return;

    const citySelect = document.getElementById('atlas-city');
    const categorySelect = document.getElementById('atlas-category');
    const title = document.getElementById('atlas-current-title');
    const subtitle = document.getElementById('atlas-current-subtitle');
    const status = document.getElementById('atlas-map-status');
    const label = document.getElementById('atlas-map-label');
    const count = document.getElementById('atlas-result-count');
    const results = document.getElementById('atlas-results');
    const allLink = document.getElementById('atlas-all-link');
    const provinces = JSON.parse(document.getElementById('atlas-provinces-data').textContent);
    const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, char => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[char]));

    if (!window.L) {
        status.textContent = 'Harita yüklenemedi. Aşağıdaki il bağlantılarını kullanabilirsin.';
        results.innerHTML = '<p class="atlas-empty">Harita şu anda açılamıyor. İllere göre firma bağlantıları aşağıda.</p>';
        return;
    }

    const map = L.map('atlas-map', {scrollWheelZoom:false, zoomControl:false, zoomSnap:.25, minZoom:3, maxZoom:17});
    L.control.zoom({position:'bottomright'}).addTo(map);
    const markers = L.layerGroup().addTo(map);
    const layers = new Map();
    let provinceLayer;
    let turkeyBounds;
    let selectedCode = '';
    let pending;
    let requestNumber = 0;

    const fitTurkey = () => {
        map.invalidateSize();
        map.fitBounds(turkeyBounds, {padding:[20,20]});
    };

    const baseStyle = feature => {
        const data = provinces[feature.properties.shapeISO];
        return {
            color: selectedCode === feature.properties.shapeISO ? '#075d5d' : '#418a85',
            weight: selectedCode === feature.properties.shapeISO ? 2.3 : 1,
            fillColor: selectedCode === feature.properties.shapeISO ? '#69d3ad' : data?.count ? '#a9e0ca' : '#cce6da',
            fillOpacity: selectedCode === feature.properties.shapeISO ? .94 : .82,
        };
    };

    const updateCity = (code, fit = true) => {
        selectedCode = code;
        citySelect.value = code;
        provinceLayer?.setStyle(baseStyle);
        if (code && provinces[code]) {
            const city = provinces[code];
            title.textContent = city.name;
            subtitle.textContent = `${new Intl.NumberFormat('tr-TR').format(city.count)} kayıtlı firma · Pinler konumu olan firmaları gösterir.`;
            label.textContent = `${city.name} · firmaları keşfet`;
            if (fit && layers.has(code)) map.fitBounds(layers.get(code).getBounds(), {padding:[40,40], maxZoom:10});
        } else {
            title.textContent = 'Türkiye genelinde';
            subtitle.textContent = 'İl seçerek firmalara yaklaş.';
            label.textContent = 'Bir il seç veya haritayı yakınlaştır';
            if (fit && turkeyBounds) fitTurkey();
        }
        const url = new URL(root.dataset.listUrl, location.origin);
        if (code && provinces[code]) url.searchParams.set('city', provinces[code].slug);
        if (categorySelect.value) url.searchParams.set('category', categorySelect.value);
        allLink.href = url.toString();
    };

    const renderCompanies = (companies, total) => {
        count.textContent = new Intl.NumberFormat('tr-TR').format(total);
        markers.clearLayers();
        if (!companies.length) {
            results.innerHTML = '<p class="atlas-empty">Bu görünümde konumu kayıtlı firma bulunamadı. İli veya kategoriyi değiştirerek yeniden deneyebilirsin.</p>';
            return;
        }
        const fragment = document.createDocumentFragment();
        companies.forEach((company, index) => {
            const icon = L.divIcon({className:'atlas-pin-icon', html:`<div class="atlas-pin${company.premium ? ' premium' : ''}"></div>`, iconSize:[20,20], iconAnchor:[10,10]});
            const marker = L.marker([company.lat, company.lng], {icon}).addTo(markers);
            marker.bindPopup(`<div class="atlas-popup"><strong>${escapeHtml(company.name)}</strong><p>${escapeHtml([company.category,company.city].filter(Boolean).join(' · '))}</p><a href="${escapeHtml(company.url)}">Firma detayına git →</a></div>`);
            if (index >= 12) return;
            const link = document.createElement('a');
            link.className = `atlas-company${company.premium ? ' premium' : ''}`;
            link.href = company.url;
            const initial = (company.name || '?').slice(0,1).toLocaleUpperCase('tr-TR');
            link.innerHTML = `<span class="atlas-company-icon">${escapeHtml(initial)}</span><span class="atlas-company-body"><strong>${escapeHtml(company.name)}</strong><small>${escapeHtml([company.category,company.city].filter(Boolean).join(' · '))}</small></span><span class="atlas-company-arrow">›</span>`;
            fragment.appendChild(link);
        });
        results.replaceChildren(fragment);
    };

    const loadCompanies = async () => {
        if (!turkeyBounds) return;
        pending?.abort();
        pending = new AbortController();
        const current = ++requestNumber;
        const bounds = map.getBounds();
        const url = new URL(root.dataset.companiesUrl, location.origin);
        url.searchParams.set('west', Math.max(-180,bounds.getWest()).toFixed(5));
        url.searchParams.set('south', Math.max(-90,bounds.getSouth()).toFixed(5));
        url.searchParams.set('east', Math.min(180,bounds.getEast()).toFixed(5));
        url.searchParams.set('north', Math.min(90,bounds.getNorth()).toFixed(5));
        if (selectedCode) url.searchParams.set('city', provinces[selectedCode].slug);
        if (categorySelect.value) url.searchParams.set('category', categorySelect.value);
        status.textContent = 'Firmalar yükleniyor…';
        try {
            const response = await fetch(url, {signal:pending.signal, headers:{'Accept':'application/json'}});
            if (!response.ok) throw new Error('Firma verileri alınamadı');
            const data = await response.json();
            if (current !== requestNumber) return;
            renderCompanies(data.companies, data.total);
            status.textContent = `${new Intl.NumberFormat('tr-TR').format(data.companies.length)} pin gösteriliyor${data.total > data.companies.length ? ' · daha fazlası için yakınlaştır' : ''}`;
        } catch (error) {
            if (error.name === 'AbortError') return;
            status.textContent = 'Firma pinleri yüklenemedi. Tekrar denemek için haritayı hareket ettir.';
            results.innerHTML = '<p class="atlas-empty">Firmalar yüklenirken bir sorun oluştu. İl bağlantılarını kullanabilirsin.</p>';
        }
    };

    fetch(root.dataset.geoUrl)
        .then(response => {if (!response.ok) throw new Error('İl sınırları alınamadı'); return response.json();})
        .then(geojson => {
            provinceLayer = L.geoJSON(geojson, {
                style:baseStyle,
                onEachFeature:(feature, layer) => {
                    const code = feature.properties.shapeISO;
                    layers.set(code, layer);
                    const city = provinces[code];
                    if (!city) return;
                    layer.bindTooltip(`${city.name} · ${new Intl.NumberFormat('tr-TR').format(city.count)} firma`, {sticky:true});
                    layer.on('mouseover', () => {if (code !== selectedCode) layer.setStyle({fillColor:'#6dd5b4',fillOpacity:.96,weight:2,color:'#147b72'});});
                    layer.on('mouseout', () => layer.setStyle(baseStyle(feature)));
                    layer.on('click', () => updateCity(code));
                },
            }).addTo(map);
            turkeyBounds = provinceLayer.getBounds();
            fitTurkey();
            map.on('moveend', loadCompanies);
            loadCompanies();
        })
        .catch(() => {
            status.textContent = 'İl sınırları yüklenemedi. Aşağıdaki il bağlantılarını kullanabilirsin.';
            results.innerHTML = '<p class="atlas-empty">Harita şu anda açılamıyor. İller aşağıda listeleniyor.</p>';
        });

    citySelect.addEventListener('change', () => {updateCity(citySelect.value); loadCompanies();});
    categorySelect.addEventListener('change', () => {updateCity(selectedCode, false); loadCompanies();});
    document.getElementById('atlas-reset').addEventListener('click', () => {categorySelect.value = ''; updateCity(''); loadCompanies();});
})();
