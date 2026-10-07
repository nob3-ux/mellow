@php
    /*
     * 旧サイトでは115枚が区分なしで並んでいたため、写真の内容から区分けしている。
     * 各区分内は旧サイトの掲載順。
     */
    $sections = [
        [
            'id' => 'kimono',
            'en' => 'Shichi-Go-San',
            'title' => '七五三・着物',
            'link' => ['route' => 'plans.shichigosan', 'label' => '七五三プランを見る'],
            'images' => [
                1 => '番傘を持つ着物の女の子',
                2 => '着物と袴で寄り添う兄妹',
                4 => '生花を手にした着物の女の子',
                6 => '窓辺で鏡をのぞく着物の女の子',
                7 => '神社の前を歩く着物の女の子と男の子',
                14 => 'うさぎのぬいぐるみを抱いた着物の女の子',
                16 => 'カーテンの前で扇子を持つ袴の男の子',
                17 => '生花を持って笑う被布の女の子',
                18 => '扇子を持つ着物の男の子',
                21 => '鏡の前で笑う袴の男の子',
                22 => 'お母様に着物を整えてもらう女の子',
                23 => '神社の境内に並ぶ着物の兄弟',
                24 => '木漏れ日の参道に立つ着物の女の子',
                26 => '生花を飾った白い壁の前に立つ緑の着物の女の子',
                28 => 'ピンクの背景で番傘を持つ着物の女の子',
                30 => '帽子をかぶった袴の男の子',
                32 => '手まりを持つ着物の女の子',
                35 => '着物姿のご家族',
                36 => 'ベールをかぶった紫の着物の女の子',
                41 => '袴と着物で並ぶ兄弟',
                51 => '着物姿で並ぶ姉妹',
                52 => '頬を寄せ合う着物の姉妹',
                53 => '籐の椅子にもたれる着物の女の子',
                54 => '生花の横に立つ着物の女の子',
                59 => '笑顔の着物の姉妹',
                60 => '籐の椅子に座る着物の女の子',
                67 => '桜の枝と着物の女の子',
                70 => '紋付袴の男の子',
                71 => '神社の境内に立つ袴の男の子',
                73 => '神社の前で並ぶ着物の姉妹',
                74 => '着付け中の男の子',
                75 => 'クッションに寝そべる髪飾りの女の子',
                76 => '籐の椅子と生花と着物の女の子',
                77 => '階段を上る着物の女の子',
                80 => '着物の女の子を囲むご家族',
                84 => '花に囲まれた着物の女の子',
                88 => 'アンティークのドレッサー前に立つ着物の女の子',
                109 => '籐の椅子に座る被布の女の子',
            ],
        ],
        [
            'id' => 'baby',
            'en' => 'Baby & Birthday',
            'title' => 'ベビー・お誕生日',
            'link' => ['route' => 'plans.flower-styling', 'label' => '生花Stylingプランを見る'],
            'images' => [
                3 => '椅子に座るサロペットの男の子',
                5 => 'グリーンの背景で遊ぶ男の子',
                9 => 'ケーキとピンクの背景でお誕生日の女の子',
                10 => 'かごのベッドに座る赤ちゃん',
                11 => 'ボンネットをかぶりぬいぐるみに囲まれた赤ちゃん',
                12 => '子供服を飾った壁の前に座る赤ちゃん',
                15 => 'くま耳の帽子をかぶって寝転ぶ赤ちゃん',
                19 => 'ベッドの上で笑う赤ちゃん',
                20 => '籐の椅子と生花とお誕生日の女の子',
                25 => '籐の椅子につかまる赤ちゃん',
                27 => 'おもちゃと生花に囲まれて笑う赤ちゃん',
                29 => '花冠をかぶりケーキを食べた女の子',
                31 => '鏡の前に立つ赤ちゃん',
                34 => 'ボンネットをかぶった赤ちゃん',
                38 => '生花とフルーツに囲まれた赤ちゃん',
                39 => '籐の椅子と生花で飾ったお誕生日のセット',
                40 => '生花と一緒に笑う赤ちゃん',
                44 => 'ハイハイする赤ちゃん',
                50 => 'Happy Birthdayの飾りとケーキの前の女の子',
                58 => '帽子をかぶり椅子に座る女の子',
                63 => 'カーテンと枝ものの前に立つ女の子',
                65 => '花冠をつけて寝転ぶ赤ちゃん',
                85 => 'ベレー帽をかぶった男の子',
                86 => '泣き顔の赤ちゃん',
                94 => 'ベレー帽をかぶり積み木を持つ男の子',
                95 => 'ピンクの天蓋の下でクッションを抱く女の子',
                96 => 'ケーキを持つお誕生日の女の子',
                101 => '花冠をかぶり籐の椅子に座る女の子',
                107 => '星の飾りとケーキで1歳のお誕生日',
                108 => '籐の椅子に座りケーキを前にした男の子',
                114 => '王冠をかぶって手を広げる女の子',
            ],
        ],
        [
            'id' => 'kids',
            'en' => 'Kids',
            'title' => 'キッズ・ハーフ成人式・入学',
            'link' => ['route' => 'plans.half-seijinshiki', 'label' => 'ハーフ成人式プランを見る'],
            'images' => [
                8 => '蘭の花冠をつけた女の子',
                33 => 'カーテンの陰で微笑む女の子',
                45 => '頬杖をつく女の子',
                47 => '椅子の上で笑うハットの女の子',
                56 => 'ピンクのワンピースの女の子',
                57 => '生花の向こうで笑う女の子',
                62 => 'ランドセルを背負った女の子',
                64 => '麦わら帽子の女の子',
                68 => '籐の椅子に座る女の子',
                69 => '花冠をつけて籐の椅子に座る女の子',
                72 => '頬に花を飾った姉妹',
                79 => '王冠をかぶりうさぎを抱く女の子',
                81 => '鏡越しの女の子',
                87 => 'ランドセルを背負った男の子',
                90 => 'ランドセルを頭にのせて笑う男の子',
                92 => '生花を飾ったランドセル',
                93 => '頬に花を飾って寄り添う姉妹',
                97 => '王冠をつけて抱き合う姉妹',
                99 => '生花とフルーツのテーブルの前の女の子',
                100 => '籐の椅子に座る女の子',
                102 => '花冠をつけて膝を抱える女の子',
                103 => '籐のマットに立つ女の子の足元',
                104 => 'ランドセルを背負った姉妹',
                105 => '頬杖をつく女の子',
                106 => 'デイジーを持って笑う女の子',
                110 => '桜の下でランドセルを背負う女の子',
                111 => '桜の下でランドセルを背負う女の子',
                112 => 'ランドセルを背負って笑う女の子',
                113 => '花を持って寝転び笑う女の子',
                115 => '蘭の花冠をつけた女の子',
            ],
        ],
        [
            'id' => 'family',
            'en' => 'Family',
            'title' => 'ファミリー・きょうだい',
            'link' => ['route' => 'plans.anniversary', 'label' => 'Anniversaryプランを見る'],
            'images' => [
                13 => '椅子を囲むご家族',
                37 => '白い衣装で赤ちゃんを抱くご家族',
                42 => '鏡の前で寄り添う親子',
                43 => '赤ちゃんを抱くご両親',
                46 => '床に座って寄り添うご家族',
                48 => '抱き合って笑う親子',
                49 => 'デニムでそろえた親子',
                55 => '白い衣装でそろえたご家族',
                61 => '床に座るご家族',
                66 => 'ソファで遊ぶきょうだい',
                78 => 'スーツ姿で向かい合うご家族',
                89 => '赤ちゃんと一緒に床に座るご家族',
                91 => '緑の中で抱き合う親子',
            ],
        ],
        [
            'id' => 'maternity',
            'en' => 'Maternity',
            'title' => 'マタニティー・ポートレート',
            'link' => ['route' => 'plans.maternity', 'label' => 'マタニティープランを見る'],
            'images' => [
                82 => '白いドレスで耳元に手を添える女性',
                83 => 'ドットのチュール越しの女性のポートレート',
                98 => 'お腹に手を添えるマタニティーフォト',
            ],
        ],
    ];

    // 旧サイトでキャプションが付いていた写真
    $captions = [16 => 'お持ち込み着物', 26 => 'お持ち込み着物'];

    $toGallery = fn (array $images) => collect($images)
        ->map(fn ($alt, $n) => array_filter([
            'src' => sprintf('gallery/%02d', $n),
            'alt' => $alt,
            'caption' => $captions[$n] ?? null,
        ]))
        ->values()
        ->all();
@endphp

<x-layouts.app :seo="$seo" hero-image="gallery/115">

    <x-page-hero title="ギャラリー" en="Gallery" image="gallery/115" />

    {{-- 区分への目次 --}}
    <nav aria-label="ギャラリーの区分" class="border-b border-brand-200 bg-white">
        <ul class="mx-auto flex max-w-6xl flex-wrap justify-center gap-x-6 gap-y-2 px-5 py-5 text-sm tracking-wider">
            @foreach ($sections as $section)
                <li><a href="#{{ $section['id'] }}" class="text-brand-700 underline-offset-4 hover:underline">{{ $section['title'] }}</a></li>
            @endforeach
        </ul>
    </nav>

    @foreach ($sections as $section)
        <x-section :tone="$loop->even ? 'white' : 'base'" width="wide" :id="$section['id']" class="scroll-mt-20">
            <x-heading :en="$section['en']">{{ $section['title'] }}</x-heading>
            @if ($loop->first)
                <p class="-mt-6 mb-8 text-center text-sm text-brand-700">画像クリックで拡大</p>
            @endif
            <x-gallery :images="$toGallery($section['images'])" :columns="4" :label="$section['title'].'のギャラリー'" />
            <div class="mt-10 text-center">
                <x-button :href="route($section['link']['route'])" variant="outline" arrow>{{ $section['link']['label'] }}</x-button>
            </div>
        </x-section>
    @endforeach

    {{-- 各プラン・他のギャラリーへの導線 --}}
    <x-section tone="cream" width="wide">
        <x-heading en="Plan">撮影プラン</x-heading>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach (array_keys(config('site.plans')) as $plan)
                <x-plan-card :plan="$plan" />
            @endforeach
        </div>
        <div class="mt-12 flex flex-col justify-center gap-3 sm:flex-row">
            <x-button :href="route('plans.index')" arrow>プラン・料金をくらべる</x-button>
            <x-button :href="route('gallery.wedding')" variant="outline" arrow>ウェディングギャラリー</x-button>
            <x-button :href="route('gallery.kimono')" variant="outline" arrow>七五三 着物ギャラリー</x-button>
        </div>
    </x-section>

</x-layouts.app>
