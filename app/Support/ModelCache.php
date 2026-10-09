<?php

namespace App\Support;

use Closure;
use Illuminate\Support\Facades\Cache;

/**
 * Eloquent nesnelerini önbelleğe alır. Laravel 13 önbellekten nesne okumayı engellediği için
 * değer kendi serialize/unserialize'ımızla düz metin olarak saklanır.
 * Sürüm anahtarı (bump) ile bir kapsamdaki tüm kayıtlar tek hamlede geçersiz kılınır.
 */
class ModelCache
{
    public static function enabled(): bool
    {
        return (bool) config('performance.model_cache', true);
    }

    public static function remember(string $scope, string $key, int $seconds, Closure $callback): mixed
    {
        if (! self::enabled()) {
            return $callback();
        }

        $cacheKey = 'mc.'.$scope.'.'.self::version($scope).'.'.$key;
        $stored = Cache::get($cacheKey);

        if (is_string($stored)) {
            $value = @unserialize($stored);

            if ($value !== false || $stored === 'b:0;') {
                return $value;
            }
        }

        $value = $callback();

        // Boş sonuç saklanmaz: yeni eklenen rehber/kayıt hemen görünsün.
        if ($value !== null) {
            Cache::put($cacheKey, serialize($value), $seconds);
        }

        return $value;
    }

    public static function bump(string $scope): void
    {
        if (! self::enabled()) {
            return;
        }

        Cache::put('mc.ver.'.$scope, (string) microtime(true), 60 * 60 * 24 * 30);
    }

    private static function version(string $scope): string
    {
        return (string) Cache::get('mc.ver.'.$scope, '0');
    }
}
