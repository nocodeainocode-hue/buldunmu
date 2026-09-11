<?php

namespace App\Services;

use App\Models\Directory;
use App\Models\SiteSetting;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class DirectoryManagementService
{
    private const DIRECTORY_FIELDS = [
        'name',
        'slug',
        'domain',
        'logo',
        'favicon',
        'template',
        'slug_pattern',
        'plan',
        'status',
        'expires_at',
        'meta_title',
        'meta_description',
        'page_contents',
        'geography_mode',
        'primary_city_slug',
        'featured_city_slugs',
        'group_other_cities',
        'blog_layout',
        'editorial_voice',
    ];

    private const SETTING_FIELDS = [
        'phone',
        'whatsapp',
        'email',
        'address',
        'homepage_title',
        'homepage_subtitle',
        'show_membership_plans',
    ];

    private const THEME_FIELDS = [
        'primary',
        'secondary',
        'accent',
        'bg',
        'bg_card',
        'text',
        'text_muted',
        'border',
        'hero_gradient_from',
        'hero_gradient_to',
        'font_body',
        'border_radius',
        'card_shadow',
    ];

    public function formData(Directory $directory): array
    {
        $settings = $this->settingsFor($directory);
        $data = array_merge(
            Arr::only($directory->attributesToArray(), self::DIRECTORY_FIELDS),
            Arr::only($settings->attributesToArray(), self::SETTING_FIELDS),
        );

        foreach ($this->theme($directory) as $key => $value) {
            if (in_array($key, self::THEME_FIELDS, true)) {
                $data['theme_'.$key] = $value;
            }
        }

        return $data;
    }

    public function update(Directory $directory, array $data): void
    {
        DB::transaction(function () use ($directory, $data): void {
            $theme = $this->theme($directory);

            foreach (self::THEME_FIELDS as $field) {
                $value = $data['theme_'.$field] ?? null;

                if (filled($value)) {
                    $theme[$field] = $value;
                } else {
                    unset($theme[$field]);
                }
            }

            $directoryData = Arr::only($data, self::DIRECTORY_FIELDS);
            $directoryData['domain'] = Directory::normalizeDomain($directoryData['domain'] ?? null);
            $directoryData['theme'] = $theme;
            $directory->update($directoryData);

            $settings = $this->settingsFor($directory);
            $settings->update(array_merge(
                Arr::only($data, self::SETTING_FIELDS),
                ['site_name' => $directory->name],
            ));
        });
    }

    private function settingsFor(Directory $directory): SiteSetting
    {
        return SiteSetting::withoutGlobalScope('directory')->firstOrCreate(
            ['directory_id' => $directory->id],
            ['site_name' => $directory->name],
        );
    }

    private function theme(Directory $directory): array
    {
        $theme = $directory->theme;

        if (is_string($theme)) {
            $theme = json_decode($theme, true);
        }

        return is_array($theme) ? $theme : [];
    }
}
