{{--
    ボタン型リンク
    variant: primary（予約など主要導線）/ outline / line（LINE）/ light（濃い背景の上）
    size:    sm / md / lg
--}}
@props([
    'href',
    'variant' => 'primary',
    'size' => 'md',
    'arrow' => false,
])

@php
    $variants = [
        'primary' => 'bg-brand-700 text-white hover:bg-brand-800 shadow-sm shadow-brand-900/10',
        'outline' => 'border border-brand-700 text-brand-700 hover:bg-brand-700 hover:text-white',
        'line' => 'bg-line text-white hover:bg-line-dark shadow-sm',
        'light' => 'bg-white text-brand-800 hover:bg-brand-100',
    ];
    $sizes = [
        'sm' => 'px-4 py-2 text-xs',
        'md' => 'px-6 py-3 text-sm',
        'lg' => 'px-8 py-4 text-base',
    ];
@endphp

<a href="{{ $href }}" {{ $attributes->class([
    'inline-flex max-w-full items-center justify-center gap-2 rounded-full text-center tracking-widest transition sm:whitespace-nowrap duration-200 focus-visible:outline-2 focus-visible:outline-offset-2',
    $variants[$variant],
    $sizes[$size],
]) }}>
    @if ($variant === 'line')
        <svg class="size-[1.2em] shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 3C6.48 3 2 6.58 2 11c0 3.95 3.56 7.26 8.36 7.88.33.07.77.22.88.5.1.25.07.65.03.9l-.14.85c-.04.25-.2.98.86.53 1.06-.45 5.73-3.38 7.82-5.78C21.23 14.3 22 12.75 22 11c0-4.42-4.48-8-10-8Z"/></svg>
    @endif
    <span>{{ $slot }}</span>
    @if ($arrow)
        <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12l-7.5 7.5M21 12H3" /></svg>
    @endif
</a>
