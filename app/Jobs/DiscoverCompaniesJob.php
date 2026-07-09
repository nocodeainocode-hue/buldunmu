<?php

namespace App\Jobs;

use App\Models\DiscoveredCompany;
use App\Services\FirecrawlService;
use Filament\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DiscoverCompaniesJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds the job can run before timing out.
     * Firecrawl API can take a while for large crawls.
     */
    public int $timeout = 300;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 1;

    /**
     * @param array<string, mixed> $data Discovery parameters
     * @param int|null $userId The admin user who initiated the discovery
     * @param int $directoryId The directory scope
     */
    public function __construct(
        public array $data,
        public ?int $userId,
        public int $directoryId,
    ) {}

    /**
     * Get the unique ID for the job to prevent duplicate dispatches.
     */
    public function uniqueId(): string
    {
        $keyword = $this->data['keyword'] ?? '';
        $city = $this->data['city'] ?? '';
        $source = $this->data['source'] ?? 'search';

        return "discover:{$this->directoryId}:{$source}:{$keyword}:{$city}";
    }

    /**
     * Execute the job.
     */
    public function handle(FirecrawlService $service): void
    {
        $keyword = $this->data['keyword'] ?? '';
        $city = $this->data['city'] ?? '';
        $source = $this->data['source'] ?? 'google_maps';
        $customUrl = $this->data['customUrl'] ?? null;

        Log::info('Firecrawl keşif job başladı.', [
            'keyword' => $keyword,
            'city' => $city,
            'source' => $source,
            'user_id' => $this->userId,
        ]);

        $results = $service->discoverCompanies(
            keyword: $keyword,
            city: $city,
            source: $source,
            customUrl: $customUrl,
        );

        $saved = 0;
        $skipped = 0;

        foreach ($results as $company) {
            if (empty($company['name'])) {
                continue;
            }

            $exists = DiscoveredCompany::where('name', $company['name'])
                ->where('search_keyword', $keyword)
                ->where('search_city', $city)
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            DiscoveredCompany::create([
                'name' => $company['name'],
                'phone' => $company['phone'] ?? null,
                'address' => $company['address'] ?? null,
                'website' => $company['website'] ?? null,
                'logo_url' => $company['logo_url'] ?? null,
                'email' => $company['email'] ?? null,
                'description' => $company['description'] ?? null,
                'source' => $source,
                'search_keyword' => $keyword,
                'search_city' => $city,
                'raw_data' => $company,
                'status' => 'pending',
                'directory_id' => $this->directoryId,
            ]);
            $saved++;
        }

        Log::info('Firecrawl keşif job tamamlandı.', [
            'keyword' => $keyword,
            'city' => $city,
            'saved' => $saved,
            'skipped' => $skipped,
            'total' => count($results),
        ]);

        // Send Filament notification to the admin who initiated the discovery
        if ($this->userId) {
            $user = \App\Models\User::find($this->userId);
            if ($user) {
                $body = "{$keyword} - {$city} araması tamamlandı. {$saved} yeni firma bulundu, {$skipped} tekrar eden atlandı.";

                if ($saved === 0 && $skipped > 0) {
                    $body = "{$keyword} - {$city} araması tamamlandı. Tüm sonuçlar zaten veritabanında mevcut ({$skipped} tekrar).";
                } elseif (empty($results)) {
                    $body = "{$keyword} - {$city} araması tamamlandı ancak hiçbir sonuç bulunamadı.";
                }

                Notification::make()
                    ->title('Firma keşfi tamamlandı')
                    ->body($body)
                    ->success()
                    ->sendToDatabase($user);
            }
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        $keyword = $this->data['keyword'] ?? '';
        $city = $this->data['city'] ?? '';

        Log::error('Firecrawl keşif job başarısız oldu.', [
            'keyword' => $keyword,
            'city' => $city,
            'error' => $exception->getMessage(),
            'user_id' => $this->userId,
        ]);

        // Notify the admin about the failure
        if ($this->userId) {
            $user = \App\Models\User::find($this->userId);
            if ($user) {
                Notification::make()
                    ->title('Firma keşfi başarısız oldu')
                    ->body("{$keyword} - {$city} araması sırasında bir hata oluştu. Hata: " . $exception->getMessage())
                    ->danger()
                    ->sendToDatabase($user);
            }
        }
    }
}
