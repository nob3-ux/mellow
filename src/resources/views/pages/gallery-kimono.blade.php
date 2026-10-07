@php
    $img = fn (int $n, string $alt, ?string $caption = null) => array_filter([
        'src' => sprintf('gallery-kimono/%02d', $n),
        'alt' => $alt,
        'caption' => $caption,
    ]);

    $categories = [
        [
            'id' => 'age3',
            'en' => '3 years old',
            'title' => '3歳着物',
            'kimonos' => [
                ['name' => '菱×橙', 'images' => [$img(1, '3歳着物 菱×橙'), $img(2, '3歳着物 菱×橙')]],
                ['name' => 'ピンク×花籠', 'images' => [$img(3, '3歳着物 ピンク×花籠'), $img(4, '3歳着物 ピンク×花籠')]],
                ['name' => '正絹しぼり', 'images' => [$img(5, '3歳着物 正絹しぼり'), $img(6, '3歳着物 正絹しぼりに被布を合わせた様子', '被布を合わせて華やかに')]],
                ['name' => '朱赤×花', 'images' => [$img(7, '3歳着物 朱赤×花'), $img(8, '3歳着物 朱赤×花')]],
                ['name' => '水色', 'images' => [$img(9, '3歳着物 水色'), $img(10, '3歳着物 水色')]],
                ['name' => 'ベージュ', 'images' => [$img(11, '3歳着物 ベージュ'), $img(12, '3歳着物 ベージュ', '90cm以下のお子様用')]],
            ],
        ],
        [
            'id' => 'age5',
            'en' => '5 years old',
            'title' => '5歳男児',
            'kimonos' => [
                ['name' => null, 'images' => [$img(13, '5歳男児の着物 茶×黒レトロな着物', '茶×黒レトロな着物'), $img(14, '5歳男児の着物 茶×黒レトロな着物', '茶×黒レトロな着物')]],
                ['name' => null, 'images' => [$img(15, '5歳男児の着物', '大きめ5〜7歳まで着れます。'), $img(16, '5歳男児の抹茶色の着物と縞袴', '抹茶色の着物と縞袴')]],
                ['name' => null, 'images' => [$img(17, '5歳男児の着物'), $img(18, '5歳男児の着物とベーシックの黒縞袴', 'あとはベーシックの黒縞袴')]],
                ['name' => null, 'images' => [$img(19, '5歳男児の青×グレーの粋な着物', '青×グレーの粋な着物'), $img(20, '5歳男児の着物')]],
            ],
        ],
        [
            'id' => 'age7',
            'en' => '7 years old',
            'title' => '7歳着物',
            'kimonos' => [
                ['name' => '紫×花籠', 'images' => [$img(21, '7歳着物 紫×花籠'), $img(22, '7歳着物 紫×花籠 アンティークの鮮やかな着物', 'アンティークの鮮やかな着物')]],
                ['name' => '水色×紫', 'images' => [$img(23, '7歳着物 水色×紫'), $img(24, '7歳着物 水色×紫')]],
                ['name' => 'オレンジ黄×お花', 'images' => [$img(25, '7歳着物 オレンジ黄×お花'), $img(26, '7歳着物 オレンジ黄×お花')]],
                ['name' => '白地×手鞠', 'images' => [$img(27, '7歳着物 白地×手鞠'), $img(28, '7歳着物 白地×手鞠')]],
                ['name' => '白×ピンク', 'note' => '（小柄な7歳/100〜110cm）', 'images' => [$img(29, '7歳着物 白×ピンク'), $img(30, '7歳着物 白×ピンク')]],
                ['name' => 'pinkリボン', 'images' => [$img(31, '7歳着物 pinkリボン'), $img(32, '7歳着物 pinkリボン')]],
            ],
            'after' => [$img(33, '7歳着物の帯・帯あげ・抱え帯', '帯・帯あげ・抱え帯もバランスを見て良いものを')],
        ],
        [
            'id' => 'age10',
            'en' => '10 years old',
            'title' => '10歳着物',
            'kimonos' => [
                ['name' => null, 'images' => [
                    $img(34, '10歳の豪華なアンティークの着物', '豪華なアンティークの着物'),
                    $img(35, '10歳の枝花模様刺繍の着物', '枝花模様刺繍の着物'),
                    $img(36, '10歳の黄緑の着物', '２着目は黄緑で可愛らしく'),
                    $img(37, '10歳の着物に合わせる袴', '袴は２種類（エンジ・緑）身長とバランスに合わせて決めさせていただきます。'),
                    $img(38, '10歳の着物'),
                    $img(39, '10歳男児の着物', '10歳男児の着物は１着'),
                ]],
            ],
        ],
    ];
@endphp

<x-layouts.app :seo="$seo" hero-image="gallery-kimono/21" :cta-query="['plan' => 'shichigosan']">

    <x-page-hero title="着物ギャラリー" en="Kimono gallery" image="gallery-kimono/21" lead="それぞれ年齢のレンタル着物のご紹介です。" />

    {{-- 導入・目次 --}}
    <x-section width="narrow" class="text-center">
        <div class="prose-mellow">
            <p>それぞれ年齢のレンタル着物のご紹介です。<br>（画像をタップで拡大）</p>
        </div>
        <nav aria-label="年齢別の着物" class="mt-10">
            <ul class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                @foreach ($categories as $category)
                    <li>
                        <a href="#{{ $category['id'] }}" class="block rounded-sm border border-brand-200 bg-white px-3 py-4 text-sm tracking-[0.12em] text-brand-800 transition hover:border-brand-500">{{ $category['title'] }}</a>
                    </li>
                @endforeach
            </ul>
        </nav>
    </x-section>

    @foreach ($categories as $category)
        <x-section :tone="$loop->odd ? 'white' : 'base'" id="{{ $category['id'] }}" class="scroll-mt-20">
            <x-heading :en="$category['en']">{{ $category['title'] }}</x-heading>

            <div class="grid gap-x-8 gap-y-14 {{ count($category['kimonos']) > 1 ? 'md:grid-cols-2' : '' }}">
                @foreach ($category['kimonos'] as $kimono)
                    <article>
                        @if ($kimono['name'])
                            <h3 class="mb-4 text-center tracking-[0.12em] text-brand-800">
                                {{ $kimono['name'] }}
                                @isset($kimono['note'])
                                    <span class="mt-1 block text-xs text-brand-600">{{ $kimono['note'] }}</span>
                                @endisset
                            </h3>
                        @endif
                        <x-gallery :images="$kimono['images']" :columns="count($kimono['images']) > 2 ? 3 : 2"
                                   :label="$category['title'].($kimono['name'] ? ' '.$kimono['name'] : '')" />
                    </article>
                @endforeach
            </div>

            @isset($category['after'])
                <div class="mx-auto mt-14 max-w-2xl">
                    <x-gallery :images="$category['after']" :columns="1" :label="$category['title'].'の帯・小物'" />
                </div>
            @endisset

            @if ($category['id'] === 'age10')
                <div class="mt-12 text-center">
                    <x-button :href="route('plans.half-seijinshiki')" variant="outline" arrow>ハーフ成人式プランを見る</x-button>
                </div>
            @endif
        </x-section>
    @endforeach

    {{-- Mellowの着物について --}}
    <x-section tone="cream" width="narrow" class="text-center">
        <x-heading en="About kimono">Mellowの着物</x-heading>
        <div class="prose-mellow space-y-6">
            <p>Mellowの着物は、<br>着やすさを１番に考えて選んでいます。<br>女の子の着物は全て正絹。<br>スタジオのイメージに合わせて選んだ<br>こだわりの着物達です。</p>
            <p>こちらの中より、<br>当日実際の着物を見ていただき<br>ご家族でお選びくださいませ＾＾</p>
        </div>
        <div class="mt-10 flex flex-col justify-center gap-3 sm:flex-row">
            <x-button :href="route('reservation', ['plan' => 'shichigosan'])" arrow>七五三撮影を予約する</x-button>
            <x-button :href="route('plans.shichigosan')" variant="outline" arrow>七五三プランを見る</x-button>
        </div>
        <p class="mt-6 text-sm">
            <a href="{{ route('options.mama-kimono') }}" class="text-brand-800 underline underline-offset-4 hover:no-underline">お母様の訪問着のご案内はこちら</a>
        </p>
    </x-section>

</x-layouts.app>
