{{-- 撮影プランの一覧カード（config/site.php の plans） --}}
@props(['plan'])

@php
    $info = config("site.plans.{$plan}");
@endphp

<article {{ $attributes->class('group relative flex flex-col overflow-hidden rounded-sm bg-white shadow-sm shadow-brand-900/5 transition hover:-translate-y-0.5 hover:shadow-lg') }}>
    <div class="aspect-[4/3] overflow-hidden">
        <x-picture :src="$info['image']" :alt="$info['name']" sizes="(min-width: 1024px) 33vw, (min-width: 640px) 50vw, 100vw"
                   class="size-full object-cover transition duration-700 group-hover:scale-105" />
    </div>
    <div class="flex flex-1 flex-col p-6">
        <p class="text-xs tracking-wider text-brand-600">{{ $info['target'] }}</p>
        <h3 class="mt-2 text-lg tracking-[0.1em] text-brand-800">
            <a href="{{ route("plans.{$plan}") }}" class="after:absolute after:inset-0">{{ $info['name'] }}</a>
        </h3>
        <p class="mt-3 flex-1 text-sm leading-relaxed">{{ $info['lead'] }}</p>
        <p class="mt-5 flex items-end justify-between border-t border-brand-100 pt-4">
            @if ($info['price'])
                <span class="text-xs text-brand-600">平日・税込</span>
                <span class="text-xl tracking-wider text-brand-800">{{ number_format($info['price']) }}<span class="text-sm">円〜</span></span>
            @else
                <span class="text-sm text-brand-700">料金はプランページでご案内</span>
            @endif
        </p>
    </div>
</article>
