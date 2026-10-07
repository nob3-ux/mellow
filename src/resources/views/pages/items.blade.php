@php
    $detailImages = fn (array $numbers, string $alt) => collect($numbers)
        ->map(fn ($n) => ['src' => sprintf('items/%02d', $n), 'alt' => $alt])
        ->all();
@endphp

<x-layouts.app :seo="$seo" hero-image="items/01">

    <x-page-hero title="商品アイテム" en="Items" image="items/01" lead="Mellowでは、松・竹・梅でフォトアルバムをご用意しました。" />

    {{-- 導入・目次 --}}
    <x-section width="narrow" class="text-center">
        <div class="prose-mellow">
            <p>Mellowでは、<br>松・竹・梅でフォトアルバムをご用意しました。</p>
        </div>
        <nav aria-label="商品一覧" class="mt-10">
            <ul class="grid grid-cols-2 gap-3 text-sm sm:grid-cols-4">
                @foreach ([
                    ['#album-matsu', 'PHOTO ALBUM', '松'],
                    ['#album-take', 'SQUARE PHOTO ALBUM', '竹'],
                    ['#book-ume', 'カジュアル PHOTO BOOK', '梅'],
                    ['#canvas', 'キャンバスフォトフレーム', null],
                ] as [$href, $label, $grade])
                    <li>
                        <a href="{{ $href }}" class="flex h-full flex-col items-center justify-center rounded-sm border border-brand-200 bg-white px-3 py-4 tracking-wider text-brand-800 transition hover:border-brand-500">
                            @if ($grade)
                                <span class="text-lg">〜{{ $grade }}〜</span>
                            @endif
                            <span class="text-xs">{{ $label }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
    </x-section>

    {{-- 松 --}}
    <x-section tone="white" id="album-matsu" class="scroll-mt-20">
        <x-heading en="Photo album">PHOTO ALBUM 〜松〜</x-heading>
        <div class="grid items-start gap-10 md:grid-cols-2">
            <x-picture src="items/01" alt="写真集のようなフォトアルバム（松）を開いた様子" sizes="(min-width: 768px) 50vw, 100vw" class="w-full rounded-sm" />
            <x-price-card name="PHOTO ALBUM 〜松〜" badge="松"
                          :prices="[['30ページ', 30000], ['50ページ', 35000]]">
                <p>七五三や1歳記念日。<br>よりしっかりと形に残したい。<br>おしゃれな写真集のようなアルバム。</p>
                <p class="mt-4">本棚に収まるレターサイズ。<br>リビングのサイドボードに飾るなど活用もできます。</p>
                <ul class="mt-4">
                    <li>収納箱付き</li>
                    <li>224×289cm</li>
                    <li>納期：2〜3ヶ月／時期により変動</li>
                </ul>
            </x-price-card>
        </div>
        <x-gallery :images="$detailImages(range(2, 7), 'フォトアルバム（松）の写真')" class="mt-10" label="PHOTO ALBUM 〜松〜 の写真" />
    </x-section>

    {{-- 竹 --}}
    <x-section id="album-take" class="scroll-mt-20">
        <x-heading en="Square photo album">SQUARE PHOTO ALBUM 〜竹〜</x-heading>
        <div class="grid items-start gap-10 md:grid-cols-2">
            <x-picture src="items/08" alt="スクエアフォトアルバム（竹）" sizes="(min-width: 768px) 50vw, 100vw" class="w-full rounded-sm md:order-2" />
            <x-price-card name="SQUARE PHOTO ALBUM 〜竹〜" badge="竹"
                          :prices="[['40ページ', 26000]]" class="md:order-1">
                <p>スクエアで可愛らしく。</p>
                <p class="mt-4">ちょっとカジュアルに特別な1日をプレゼントに。</p>
                <ul class="mt-4">
                    <li>収納箱付き</li>
                    <li>216×216cm</li>
                    <li>オプションでミニブックも増刷可</li>
                    <li>納期：2〜3ヶ月／時期により変動</li>
                </ul>
            </x-price-card>
        </div>
        <x-gallery :images="$detailImages(range(9, 14), 'スクエアフォトアルバム（竹）の写真')" class="mt-10" label="SQUARE PHOTO ALBUM 〜竹〜 の写真" />
    </x-section>

    {{-- 梅 --}}
    <x-section tone="white" id="book-ume" class="scroll-mt-20">
        <x-heading en="Photo book">カジュアル PHOTO BOOK 〜梅〜</x-heading>
        <div class="grid items-start gap-10 md:grid-cols-2">
            <x-picture src="items/15" alt="カジュアルなフォトブック（梅）" sizes="(min-width: 768px) 50vw, 100vw" class="w-full rounded-sm" />
            <x-price-card name="カジュアル PHOTO BOOK 〜梅〜" badge="梅"
                          :prices="[['48ページ', 10000]]">
                <p>シンプルに。手軽に。<br>Mellowの世界観にぴったりのphoto book<br>毎年毎年増やして見返す喜びをぜひ。</p>
                <ul class="mt-4">
                    <li>151×212cm</li>
                    <li>納期：2〜3ヶ月／時期により変動</li>
                    <li>レターパックでの郵送</li>
                </ul>
            </x-price-card>
        </div>
        <x-gallery :images="$detailImages(range(16, 20), 'カジュアルなフォトブック（梅）の写真')" class="mt-10" label="カジュアル PHOTO BOOK 〜梅〜 の写真" />
    </x-section>

    {{-- キャンバスフォトフレーム --}}
    <x-section id="canvas" class="scroll-mt-20">
        <x-heading en="Canvas frame">キャンバスフォトフレーム</x-heading>
        <div class="grid items-start gap-10 md:grid-cols-2">
            <x-picture src="items/21" alt="壁に飾ったキャンバスフォトフレーム（A4とスクエア）" sizes="(min-width: 768px) 50vw, 100vw" class="mx-auto w-full max-w-md rounded-sm" />
            <div class="space-y-8">
                <x-price-card name="キャンバスフォトフレーム"
                              :prices="[['スクエア', 8000], ['A4サイズ', 10000]]">
                    <p>キャンバス生地で作られたフレーム</p>
                </x-price-card>

                {{-- 2024年版の商品ページより（料金を含まない説明のみ） --}}
                <div class="prose-mellow space-y-4 text-sm">
                    <p><strong>インテリアの一部として。</strong></p>
                    <p>キャンバス生地だからこそ<br>アートの様な風合いに。<br>モノクロや<br>パーツカットをさりげなく飾る。</p>
                    <p>トイレや玄関・パパのオフィス、<br>勿論リビングにも。</p>
                    <p>”さり気なく” がキーワード。</p>
                </div>
            </div>
        </div>

        <ul class="mt-12 grid gap-8 sm:grid-cols-3">
            <li>
                <x-picture src="items-old/08" alt="壁に飾ったキャンバスフレームを横から見た様子" sizes="(min-width: 640px) 33vw, 100vw" class="aspect-[3/2] w-full rounded-sm object-cover" />
                <p class="mt-3 text-sm leading-loose">キャンバス素材なので、<br>画鋲やテープでも<br>簡単に飾ることが出来ます。</p>
            </li>
            <li>
                <x-picture src="items-old/09" alt="キャンバスフレームのマットな質感のアップ" sizes="(min-width: 640px) 33vw, 100vw" class="aspect-[3/2] w-full rounded-sm object-cover" />
                <p class="mt-3 text-sm leading-loose">光沢感は無くマットな質感。<br>絵のように。</p>
            </li>
            <li>
                <x-picture src="items-old/10" alt="キャンバスフレームの裏側" sizes="(min-width: 640px) 33vw, 100vw" class="aspect-[3/2] w-full rounded-sm object-cover" />
                <p class="mt-3 text-sm leading-loose">裏はこのようになっています。</p>
            </li>
        </ul>
    </x-section>

    {{-- お問い合わせ --}}
    <x-section tone="white" width="narrow" class="text-center">
        <p class="text-sm leading-loose">ご不明点がございましたら<br>お気軽にお問い合わせください。</p>
        <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
            <x-button :href="route('inquiry')" variant="outline" arrow>お問い合わせ</x-button>
            <x-button :href="route('plans.index')" variant="outline" arrow>撮影プラン・料金を見る</x-button>
        </div>
    </x-section>

</x-layouts.app>
