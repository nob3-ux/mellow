{{--
    セクション見出し（英字の飾り + 日本語見出し）
    level: 見出しレベル（ページ内の主見出しは h1 を page-hero が持つので、通常は 2）
--}}
@props([
    'en' => null,
    'level' => 2,
    'align' => 'center',
])

<div {{ $attributes->class(['mb-10 sm:mb-14', 'text-center' => $align === 'center']) }}>
    @if ($en)
        <p class="font-display text-sm tracking-[0.3em] text-brand-500 uppercase italic sm:text-base">{{ $en }}</p>
    @endif
    <h{{ $level }} class="mt-2 text-xl font-medium tracking-[0.12em] text-brand-800 sm:text-2xl">{{ $slot }}</h{{ $level }}>
    <span @class(['mt-5 block h-px w-12 bg-brand-400', 'mx-auto' => $align === 'center']) aria-hidden="true"></span>
</div>
