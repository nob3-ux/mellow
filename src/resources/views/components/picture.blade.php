{{--
    レスポンシブ画像（public/images のWebP）
    src:   画像キー（例: plan-shichigosan/03）
    sizes: 表示幅のヒント（例: "(min-width: 1024px) 50vw, 100vw"）
    eager: ファーストビューの画像は true（遅延読み込みしない）
--}}
@props([
    'src',
    'alt' => '',
    'sizes' => '100vw',
    'eager' => false,
])

@php
    $image = \App\Support\ImageManifest::get($src);
@endphp

<img src="{{ \App\Support\ImageManifest::url($src, 1280) }}"
     srcset="{{ \App\Support\ImageManifest::srcset($src) }}"
     sizes="{{ $sizes }}"
     width="{{ $image['width'] }}"
     height="{{ $image['height'] }}"
     alt="{{ $alt }}"
     @if ($eager) fetchpriority="high" @else loading="lazy" @endif
     decoding="async"
     {{ $attributes }}>
