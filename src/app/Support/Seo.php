<?php

namespace App\Support;

/**
 * ページのタイトル・説明文・パンくず・構造化データをまとめる。
 */
class Seo
{
    /**
     * @param  array<int, array{name: string, url: string}>  $breadcrumbs
     */
    public function __construct(
        public string $title,
        public string $description,
        public string $canonical,
        public ?string $image,
        public array $breadcrumbs,
        public bool $isHome = false,
    ) {}

    public static function forPage(string $name): self
    {
        // ルート名に「.」を含むため、config() のドット記法ではなく配列から引く
        $pages = config('pages.pages');
        $page = $pages[$name];
        $siteName = config('site.name');

        $breadcrumbs = [];
        for ($current = $name; $current !== null; $current = $pages[$current]['parent'] ?? null) {
            $breadcrumbs[] = [
                'name' => $pages[$current]['title'] ?? 'ホーム',
                'url' => route($current),
            ];
        }

        return new self(
            title: $page['title'] ? "{$page['title']}｜{$siteName}" : "{$siteName}｜".config('site.tagline').'（渋谷区・外苑前）',
            description: $page['description'],
            canonical: route($name),
            image: ImageManifest::url($page['image'] ?? 'home/01', 1280),
            breadcrumbs: array_reverse($breadcrumbs),
            isHome: $name === 'home',
        );
    }

    /**
     * 店舗情報の構造化データ（schema.org LocalBusiness）。
     *
     * @return array<string, mixed>
     */
    public static function localBusiness(): array
    {
        $address = config('site.address');

        return [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            '@id' => route('home').'#business',
            'name' => config('site.name'),
            'description' => config('site.description'),
            'url' => route('home'),
            'image' => ImageManifest::url('home/01', 1280),
            'logo' => ImageManifest::url('brand/logo', 640),
            'email' => config('site.contact.email'),
            'address' => [
                '@type' => 'PostalAddress',
                'postalCode' => $address['postal_code'],
                'addressRegion' => $address['region'],
                'addressLocality' => $address['locality'],
                'streetAddress' => $address['street'],
                'addressCountry' => 'JP',
            ],
            'sameAs' => [
                config('site.contact.instagram_url'),
                config('site.contact.pinterest_url'),
            ],
        ];
    }

    /**
     * パンくずの構造化データ（schema.org BreadcrumbList）。
     *
     * @return array<string, mixed>|null
     */
    public function breadcrumbList(): ?array
    {
        if ($this->isHome) {
            return null;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => collect($this->breadcrumbs)->values()->map(fn (array $crumb, int $i) => [
                '@type' => 'ListItem',
                'position' => $i + 1,
                'name' => $crumb['name'],
                'item' => $crumb['url'],
            ])->all(),
        ];
    }
}
