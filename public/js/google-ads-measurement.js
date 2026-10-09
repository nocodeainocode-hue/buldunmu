(() => {
    const banner = document.getElementById('ads-consent-banner');
    const script = document.getElementById('google-ads-measurement');
    if (!banner || !script || typeof gtag !== 'function') return;

    let choice = null;
    try { choice = localStorage.getItem('fr_ads_consent_v1'); } catch (_) {}
    const settings = document.getElementById('ads-consent-settings');
    banner.hidden = choice === 'granted' || choice === 'denied';
    settings.hidden = !banner.hidden;

    let conversion = null;
    try { conversion = JSON.parse(script.dataset.registration); } catch (_) {}
    let sent = false;
    function recordRegistration() {
        if (sent || choice !== 'granted' || !conversion) return;
        const key = 'fr_ads_registration_' + conversion.transaction_id;
        try { if (sessionStorage.getItem(key)) return; } catch (_) {}
        // Yalnızca başarılı firma kaydının yönlendirmesinde; panel ziyaretleri sayılmaz.
        gtag('event', 'conversion', conversion);
        sent = true;
        try { sessionStorage.setItem(key, '1'); } catch (_) {}
    }
    recordRegistration();

    banner.querySelectorAll('[data-ads-consent]').forEach(button => {
        button.addEventListener('click', () => {
            choice = button.dataset.adsConsent;
            try { localStorage.setItem('fr_ads_consent_v1', choice); } catch (_) {}
            gtag('consent', 'update', {
                ad_storage: choice,
                ad_user_data: choice,
                ad_personalization: 'denied',
                analytics_storage: 'denied'
            });
            banner.hidden = true;
            settings.hidden = false;
            recordRegistration();
        });
    });
    settings.addEventListener('click', () => {
        banner.hidden = false;
        settings.hidden = true;
        banner.querySelector('button').focus();
    });
})();
