{{--
    プランの料金カード
    name:      プラン名
    catch:     対象・キャッチ（例: 着物も洋服も撮影したい方へ）
    prices:    [['平日', 43000], ['土日祝', 48000]]（金額は税込）
    badge:     「人気」などの目印
    reserve:   予約フォームのURL（指定時のみ予約ボタンを表示）
    reserveLabel: 予約ボタンの文言
    slot:      プラン内容・注意事項（<ul> や <p>）
    options:   追加料金などの補足（名前付きスロット）
--}}
@props([
    'name',
    'catch' => null,
    'prices' => [],
    'badge' => null,
    'reserve' => null,
    'reserveLabel' => 'このプランで予約する',
])

<article {{ $attributes->class('relative flex flex-col rounded-sm border border-brand-200 bg-white p-6 sm:p-8') }}>
    @if ($badge)
        <p class="absolute -top-3 left-6 rounded-full bg-brand-700 px-3 py-1 text-xs tracking-widest text-white">{{ $badge }}</p>
    @endif

    @if ($catch)
        <p class="text-xs tracking-wider text-brand-600">{{ $catch }}</p>
    @endif
    <h3 class="mt-2 text-lg leading-snug tracking-[0.08em] text-brand-800">{{ $name }}</h3>

    @if ($prices)
        <dl class="mt-5 space-y-1 border-y border-brand-100 py-4">
            @foreach ($prices as [$label, $amount])
                <div class="flex items-baseline justify-between gap-4">
                    <dt class="text-sm">{{ $label }}</dt>
                    <dd class="text-xl tracking-wider text-brand-800">{{ number_format($amount) }}<span class="ml-1 text-xs">円（税込）</span></dd>
                </div>
            @endforeach
        </dl>
    @endif

    <div class="mt-5 flex-1 text-sm leading-relaxed [&_li]:relative [&_li]:pl-4 [&_li]:before:absolute [&_li]:before:left-0 [&_li]:before:text-brand-400 [&_li]:before:content-['・'] [&_ul]:space-y-1">
        {{ $slot }}
    </div>

    @isset($options)
        <div class="mt-5 rounded-sm bg-brand-50 p-4 text-xs leading-relaxed">
            {{ $options }}
        </div>
    @endisset

    @if ($reserve)
        <x-button :href="$reserve" class="mt-6 w-full" arrow>{{ $reserveLabel }}</x-button>
    @endif
</article>
