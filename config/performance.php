<?php

return [
    // Rehber çözümleme, site ayarları ve ana sayfa verisi için kısa süreli önbellek.
    'model_cache' => env('MODEL_CACHE', true),
    'home_ttl' => (int) env('HOME_CACHE_SECONDS', 90),
    'directory_ttl' => (int) env('DIRECTORY_CACHE_SECONDS', 60),
    // Sayfa görüntüleme kayıtlarının saklanma süresi (gün).
    'page_view_retention_days' => (int) env('PAGE_VIEW_RETENTION_DAYS', 180),
];
