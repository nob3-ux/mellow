<?php

namespace App\Support;

use InvalidArgumentException;

/**
 * public/images 以下のWebP画像（幅違い）の情報を resources/data/images.json から引く。
 *
 * 画像キー "plan-shichigosan/03" は public/images/plan-shichigosan/03-{幅}.webp に対応する。
 */
class ImageManifest
{
    /** @var array<string, array{width: int, height: int, widths: array<int, int>}>|null */
    private static ?array $manifest = null;

    /**
     * @return array{width: int, height: int, widths: array<int, int>}
     */
    public static function get(string $key): array
    {
        self::$manifest ??= json_decode(file_get_contents(resource_path('data/images.json')), true);

        return self::$manifest[$key] ?? throw new InvalidArgumentException("画像 [{$key}] が見つかりません。");
    }

    /**
     * 指定幅以上で最小のサイズのURL（無ければ最大サイズ）。
     */
    public static function url(string $key, int $width = 1280): string
    {
        $widths = self::get($key)['widths'];
        $chosen = collect($widths)->first(fn (int $w) => $w >= $width) ?? end($widths);

        return asset("images/{$key}-{$chosen}.webp");
    }

    public static function srcset(string $key): string
    {
        return collect(self::get($key)['widths'])
            ->map(fn (int $w) => asset("images/{$key}-{$w}.webp")." {$w}w")
            ->implode(', ');
    }
}
