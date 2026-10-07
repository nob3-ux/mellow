{{--
    下層ページのファーストビュー（ページの h1 を持つ）
    image を渡すと写真の上に見出しを重ねる。lead は見出し下の短い説明。
--}}
@props([
    'title',
    'en' => null,
    'image' => null,
    'lead' => null,
])

@if ($image)
    <div class="relative isolate overflow-hidden">
        <x-picture :src="$image" alt="" sizes="100vw" eager class="absolute inset-0 -z-10 size-full object-cover" />
        <div class="absolute inset-0 -z-10 bg-gradient-to-t from-brand-900/60 via-brand-900/25 to-brand-900/10" aria-hidden="true"></div>
        <div class="mx-auto flex min-h-[min(52svh,460px)] max-w-6xl flex-col items-center justify-end px-5 pt-24 pb-14 text-center text-white sm:min-h-[min(60svh,560px)] sm:pb-20">
            @if ($en)
                <p class="font-display text-base tracking-[0.3em] uppercase italic opacity-90">{{ $en }}</p>
            @endif
            <h1 class="mt-3 text-2xl font-medium tracking-[0.14em] [text-shadow:0_1px_12px_rgb(0_0_0/0.25)] sm:text-4xl">{{ $title }}</h1>
            @if ($lead)
                <p class="mt-5 max-w-xl text-sm leading-loose opacity-95 sm:text-base">{{ $lead }}</p>
            @endif
            {{ $slot }}
        </div>
    </div>
@else
    <div class="mx-auto max-w-6xl px-5 pt-10 pb-6 text-center sm:pt-16">
        @if ($en)
            <p class="font-display text-base tracking-[0.3em] text-brand-500 uppercase italic">{{ $en }}</p>
        @endif
        <h1 class="mt-3 text-2xl font-medium tracking-[0.14em] text-brand-800 sm:text-3xl">{{ $title }}</h1>
        @if ($lead)
            <p class="mx-auto mt-5 max-w-xl text-sm leading-loose sm:text-base">{{ $lead }}</p>
        @endif
        {{ $slot }}
    </div>
@endif
