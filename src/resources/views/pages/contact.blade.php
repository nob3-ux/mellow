@php
    $channels = [
        [
            'en' => 'Reservation',
            'title' => 'ご予約フォーム',
            'for' => '撮影のご予約をされる方',
            'text' => 'プラン・撮影希望日などをフォームにご入力いただけます。',
            'href' => route('reservation'),
            'label' => 'ご予約フォームはこちら',
            'variant' => 'primary',
        ],
        [
            'en' => 'LINE',
            'title' => 'LINE＠予約',
            'for' => 'LINEでご予約・ご相談をされたい方',
            'text' => 'LINEアカウントを友だち追加して、メッセージでご予約いただけます。',
            'href' => route('line'),
            'label' => 'LINE＠予約はこちら',
            'variant' => 'line',
        ],
        [
            'en' => 'Contact',
            'title' => 'お問い合わせ',
            'for' => 'ご予約以外のご質問・ご相談の方',
            'text' => 'プラン内容や撮影についてのご質問はこちらから。',
            'href' => route('inquiry'),
            'label' => 'お問い合わせはこちら',
            'variant' => 'outline',
        ],
    ];
@endphp

<x-layouts.app :seo="$seo" :cta="false">

    <x-page-hero title="ご予約・お問い合わせ" en="Contact" />

    <x-section width="default" class="!pt-4">
        <p class="mb-12 text-center text-sm leading-loose sm:text-base">
            LINEアカウント・ご予約フォームより<br>
            ご予約 / お問い合わせをお受付しております。
        </p>

        <div class="grid gap-6 md:grid-cols-3">
            @foreach ($channels as $channel)
                <article class="flex flex-col rounded-sm bg-white p-6 text-center sm:p-8">
                    <p class="font-display text-sm tracking-[0.3em] text-brand-500 uppercase italic">{{ $channel['en'] }}</p>
                    <h2 class="mt-2 text-xl tracking-[0.12em] text-brand-800">{{ $channel['title'] }}</h2>
                    <p class="mt-4 text-sm font-medium text-brand-700">{{ $channel['for'] }}</p>
                    <p class="mt-3 flex-1 text-sm leading-loose">{{ $channel['text'] }}</p>
                    <x-button :href="$channel['href']" :variant="$channel['variant']" class="mt-6" arrow>{{ $channel['label'] }}</x-button>
                </article>
            @endforeach
        </div>
    </x-section>

    <x-section tone="white" width="narrow" class="text-center">
        <p class="text-sm leading-loose">ご予約の前に、空き状況やご予約の流れもご確認ください。</p>
        <div class="mt-6 flex flex-col justify-center gap-3 sm:flex-row">
            <x-button :href="route('schedule')" variant="outline" arrow>空き状況</x-button>
            <x-button :href="route('flow')" variant="outline" arrow>ご予約の流れ</x-button>
            <x-button :href="route('cancel-policy')" variant="outline" arrow>キャンセルポリシー</x-button>
        </div>
    </x-section>

</x-layouts.app>
