{{--
    ご予約への誘導ブロック（各ページの最下部に表示）
    query: 予約フォームで最初から選んでおく項目（例: ['plan' => 'shichigosan']）
--}}
@props([
    'query' => [],
    'title' => '撮影のご予約・ご相談',
])

@php
    $reservationUrl = route('reservation', $query);
@endphp

<section {{ $attributes->class('relative isolate overflow-hidden bg-brand-800 text-brand-50') }} aria-labelledby="reservation-cta-title">
    <x-picture src="home/01" alt="" sizes="100vw" class="absolute inset-0 -z-10 size-full object-cover opacity-20" />
    <div class="mx-auto max-w-4xl px-5 py-16 text-center sm:py-24">
        <p class="font-display text-base tracking-[0.3em] uppercase italic text-brand-200">Reservation</p>
        <h2 id="reservation-cta-title" class="mt-3 text-xl tracking-[0.14em] sm:text-2xl">{{ $title }}</h2>
        <p class="mx-auto mt-6 max-w-xl text-sm leading-loose text-brand-100 sm:text-base">
            1枠1家族の完全プライベート空間で撮影します。<br class="hidden sm:inline">
            空き状況をご確認のうえ、予約フォームまたはLINEからお気軽にご連絡ください。
        </p>

        <div class="mt-10 flex flex-col items-stretch justify-center gap-3 sm:flex-row sm:items-center">
            <x-button :href="$reservationUrl" variant="light" size="lg" arrow>予約フォームから申し込む</x-button>
            <x-button :href="config('site.contact.line_url')" variant="line" size="lg" target="_blank" rel="noopener">LINEで予約・相談する</x-button>
        </div>

        <ul class="mt-8 flex flex-wrap justify-center gap-x-6 gap-y-2 text-sm text-brand-200">
            <li><a href="{{ route('schedule') }}" class="underline decoration-brand-400 underline-offset-4 hover:text-white">空き状況を見る</a></li>
            <li><a href="{{ route('flow') }}" class="underline decoration-brand-400 underline-offset-4 hover:text-white">ご予約の流れ</a></li>
            <li><a href="{{ route('cancel-policy') }}" class="underline decoration-brand-400 underline-offset-4 hover:text-white">キャンセルポリシー</a></li>
        </ul>
    </div>
</section>
