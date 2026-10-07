{{--
    サイト共通レイアウト
    seo:       App\Support\Seo（PageController が渡す）
    cta:       ページ下部の予約誘導ブロックを表示するか
    ctaQuery:  予約誘導ブロックから予約フォームへ渡す初期値（例: ['plan' => 'shichigosan']）
    heroImage: ファーストビュー画像（先読みしてLCPを改善）
--}}
@props([
    'seo',
    'cta' => true,
    'ctaQuery' => [],
    'heroImage' => null,
])

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $seo->title }}</title>
    <meta name="description" content="{{ $seo->description }}">
    <link rel="canonical" href="{{ $seo->canonical }}">
    @unless (app()->isProduction())
        <meta name="robots" content="noindex, nofollow">
    @endunless

    <meta property="og:type" content="{{ $seo->isHome ? 'website' : 'article' }}">
    <meta property="og:site_name" content="{{ config('site.name') }}">
    <meta property="og:title" content="{{ $seo->title }}">
    <meta property="og:description" content="{{ $seo->description }}">
    <meta property="og:url" content="{{ $seo->canonical }}">
    <meta property="og:image" content="{{ $seo->image }}">
    <meta property="og:locale" content="ja_JP">
    <meta name="twitter:card" content="summary_large_image">

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="apple-touch-icon" href="{{ \App\Support\ImageManifest::url('brand/logo', 640) }}">

    @if ($heroImage)
        <link rel="preload" as="image" type="image/webp"
              imagesrcset="{{ \App\Support\ImageManifest::srcset($heroImage) }}" imagesizes="100vw">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;1,400&family=Noto+Serif+JP:wght@400;500;600&display=swap">

    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])

    <script type="application/ld+json">@json(\App\Support\Seo::localBusiness(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)</script>
    @if ($breadcrumbList = $seo->breadcrumbList())
        <script type="application/ld+json">@json($breadcrumbList, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)</script>
    @endif
    @stack('head')
</head>
<body class="min-h-screen pb-16 lg:pb-0">
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:left-2 focus:z-50 focus:bg-white focus:px-4 focus:py-2">本文へスキップ</a>

    <x-site-header />

    <main id="main">
        @unless ($seo->isHome)
            <x-breadcrumbs :items="$seo->breadcrumbs" />
        @endunless

        {{ $slot }}
    </main>

    @if ($cta)
        <x-reservation-cta :query="$ctaQuery" />
    @endif

    <x-site-footer />

    <x-mobile-action-bar />
</body>
</html>
