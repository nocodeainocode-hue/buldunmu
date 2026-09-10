<?php

namespace App\Support;

class ApplicationVersion
{
    public static function number(): string
    {
        return (string) config('version.number', 'dev');
    }

    public static function name(): string
    {
        return (string) config('version.name', 'Geliştirme sürümü');
    }

    public static function releasedAt(): ?string
    {
        return config('version.released_at');
    }

    public static function commit(): ?string
    {
        if ($configured = config('version.commit')) {
            return (string) $configured;
        }

        return self::commitFromGitCheckout();
    }

    public static function shortCommit(): ?string
    {
        $commit = self::commit();

        return $commit ? substr($commit, 0, 7) : null;
    }

    public static function label(): string
    {
        $label = 'v' . self::number();

        if ($commit = self::shortCommit()) {
            $label .= ' · ' . $commit;
        }

        return $label;
    }

    private static function commitFromGitCheckout(): ?string
    {
        $gitPath = base_path('.git');

        if (! is_dir($gitPath)) {
            return null;
        }

        $head = self::readFile($gitPath . DIRECTORY_SEPARATOR . 'HEAD');

        if (! $head) {
            return null;
        }

        if (! str_starts_with($head, 'ref: ')) {
            return preg_match('/^[0-9a-f]{40}$/i', $head) ? $head : null;
        }

        $reference = substr($head, 5);
        $commit = self::readFile($gitPath . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $reference));

        if ($commit) {
            return $commit;
        }

        $packedRefs = self::readFile($gitPath . DIRECTORY_SEPARATOR . 'packed-refs');

        if (! $packedRefs) {
            return null;
        }

        foreach (preg_split('/\R/', $packedRefs) as $line) {
            if (str_ends_with($line, ' ' . $reference)) {
                return strtok($line, ' ') ?: null;
            }
        }

        return null;
    }

    private static function readFile(string $path): ?string
    {
        if (! is_file($path) || ! is_readable($path)) {
            return null;
        }

        $contents = trim((string) file_get_contents($path));

        return $contents !== '' ? $contents : null;
    }
}
