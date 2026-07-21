/*
 * BuldunMu PWA Service Worker
 * ────────────────────────────
 * Strategy:
 *   CSS / JS / Fonts / Images  →  Cache-first (stale-while-revalidate)
 *   HTML (navigation)          →  Network-first, fallback to offline page
 *   Other                       →  Network-first
 *
 * Cache version is bumped on every SW update to invalidate old caches.
 */
const CACHE_VERSION = 'v1';
const STATIC_CACHE  = 'buldunmu-static-' + CACHE_VERSION;
const HTML_CACHE    = 'buldunmu-html-' + CACHE_VERSION;
const OFFLINE_PAGE  = '/offline';

/* ── Install: pre-cache offline + splash + manifest ── */
const PRE_CACHE = [
    OFFLINE_PAGE,
    '/site.webmanifest',
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(STATIC_CACHE).then((cache) => {
            return cache.addAll(PRE_CACHE);
        })
    );
    self.skipWaiting();
});

/* ── Activate: purge old caches ── */
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) => {
            return Promise.all(
                keys
                    .filter((key) => key.startsWith('buldunmu-') && key !== STATIC_CACHE && key !== HTML_CACHE)
                    .map((key) => caches.delete(key))
            );
        })
    );
    self.clients.claim();
});

/* ── Fetch ── */
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Don't intercept non-GET or chrome-extension / browser-internal requests
    if (request.method !== 'GET') return;

    // Don't cache admin / livewire / API / filament
    if (
        url.pathname.startsWith('/admin') ||
        url.pathname.startsWith('/livewire') ||
        url.pathname.startsWith('/api') ||
        url.pathname.startsWith('/filament')
    ) {
        return;
    }

    /* ── 1. Static assets: cache-first (stale-while-revalidate) ── */
    if (isStaticAsset(request, url)) {
        event.respondWith(cacheFirst(request, STATIC_CACHE));
        return;
    }

    /* ── 2. HTML navigations: network-first → offline fallback ── */
    if (request.mode === 'navigate') {
        event.respondWith(networkFirstHtml(request));
        return;
    }

    /* ── 3. Everything else: network-first ── */
    event.respondWith(networkFirst(request, HTML_CACHE));
});

/* ────────────────────────────────────────────
   Helpers
   ──────────────────────────────────────────── */

function isStaticAsset(request, url) {
    const staticExts = /\.(css|js|woff2?|ttf|eot|otf|png|jpe?g|gif|svg|ico|webp|avif|json|xml|txt)$/i;
    if (staticExts.test(url.pathname)) return true;

    if (request.destination && ['style', 'script', 'font', 'image'].includes(request.destination)) {
        return true;
    }

    return false;
}

/** Cache-first with network update (stale-while-revalidate pattern inside fetch). */
function cacheFirst(request, cacheName) {
    return caches.match(request).then((cached) => {
        if (cached) {
            // Revalidate in the background
            fetch(request).then((response) => {
                if (response.ok) {
                    caches.open(cacheName).then((cache) => cache.put(request, response));
                }
            }).catch(() => {});
            return cached;
        }
        return networkFirst(request, cacheName);
    });
}

/** Network-first; on failure, serve from cache. */
function networkFirst(request, cacheName) {
    return fetch(request)
        .then((response) => {
            if (!response || response.status !== 200) return response;
            const clone = response.clone();
            caches.open(cacheName).then((cache) => cache.put(request, clone));
            return response;
        })
        .catch(() => caches.match(request));
}

/** Network-first for HTML; on failure, show offline page. */
function networkFirstHtml(request) {
    return fetch(request)
        .then((response) => {
            if (!response || response.status !== 200) return response;
            const clone = response.clone();
            caches.open(HTML_CACHE).then((cache) => cache.put(request, clone));
            return response;
        })
        .catch(() => {
            return caches.match(request).then((cached) => {
                return cached || caches.match(OFFLINE_PAGE);
            });
        });
}
