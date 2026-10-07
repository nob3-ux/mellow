{{-- 手順の1ステップ（<x-steps> の中で使う） --}}
@props(['title'])

<li class="relative [counter-increment:step]">
    <span class="absolute top-0 -left-8 flex size-12 -translate-x-1/2 flex-col items-center justify-center rounded-full bg-brand-700 text-white after:font-display after:text-lg after:leading-none after:content-[counter(step)] sm:-left-12" aria-hidden="true">
        <span class="font-display text-[10px] leading-none tracking-widest uppercase">Step</span>
    </span>
    <h3 class="text-lg tracking-[0.1em] text-brand-800">{{ $title }}</h3>
    <div {{ $attributes->class('prose-mellow mt-3') }}>{{ $slot }}</div>
</li>
