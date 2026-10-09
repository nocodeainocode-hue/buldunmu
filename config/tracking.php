<?php

return [
    // Etiketler yalnızca ilgili rehberde çalışır; diğer sitelerin dönüşümleri karışmaz.
    'google_ads' => [
        '81ilfirmalar.com.tr' => [
            'tag_id' => env('GOOGLE_ADS_81IL_TAG_ID', 'AW-18054234044'),
            'registration_label' => env('GOOGLE_ADS_81IL_REGISTRATION_LABEL', 'q7gkCKShh5cdELz_9qBD'),
        ],
    ],
];
