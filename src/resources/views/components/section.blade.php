{{--
    ページ内のセクション
    tone:  base（生成り）/ white / cream（濃いベージュ）/ dark（茶）
    width: narrow（文章中心） / default / wide
--}}
@props([
    'tone' => 'base',
    'width' => 'default',
])

@php
    $tones = [
        'base' => 'bg-brand-50',
        'white' => 'bg-white',
        'cream' => 'bg-brand-100',
        'dark' => 'bg-brand-800 text-brand-50',
    ];
    $widths = [
        'narrow' => 'max-w-3xl',
        'default' => 'max-w-6xl',
        'wide' => 'max-w-7xl',
    ];
@endphp

<section {{ $attributes->class([$tones[$tone], 'py-16 sm:py-24']) }}>
    <div class="{{ $widths[$width] }} mx-auto px-5 sm:px-6 lg:px-8">
        {{ $slot }}
    </div>
</section>
