@php
    $reserveQuery = ['plan' => 'shichigosan', 'mother_rental' => 'yes'];

    $kimonos = [
        [
            'name' => '正絹・優しいピンク',
            'size' => 'M~Lサイズ(166㎝まで)',
            'images' => [
                ['src' => 'option-mama-kimono/02', 'alt' => '正絹・優しいピンクの訪問着', 'caption' => '小花が可愛く、優しいイメージにしたいママへおすすめの着物。'],
                ['src' => 'option-mama-kimono/03', 'alt' => '正絹・優しいピンクの訪問着の柄', 'caption' => 'とっても優しい色味のお着物です。'],
                ['src' => 'option-mama-kimono/04', 'alt' => '正絹・優しいピンクの訪問着を着用した様子'],
            ],
        ],
        [
            'name' => '正絹・淡いブルー',
            'size' => 'Mサイズ',
            'images' => [
                ['src' => 'option-mama-kimono/05', 'alt' => '正絹・淡いブルーの訪問着', 'caption' => 'とても上品で爽やかなお着物です。'],
                ['src' => 'option-mama-kimono/06', 'alt' => '正絹・淡いブルーの訪問着の柄', 'caption' => '清楚です。可愛すぎず、年齢を問わず素敵です。'],
            ],
        ],
        [
            'name' => '正絹・ベージュ',
            'size' => 'Mサイズ',
            'images' => [
                ['src' => 'option-mama-kimono/07', 'alt' => '小鳥があしらわれた正絹・ベージュの訪問着', 'caption' => '小鳥があしらわれています。'],
                ['src' => 'option-mama-kimono/08', 'alt' => '正絹・ベージュの訪問着', 'caption' => '上品でシンプルです。'],
                ['src' => 'option-mama-kimono/09', 'alt' => '正絹・ベージュの訪問着の帯と小物'],
            ],
        ],
        [
            'name' => '正絹・紫しぼり',
            'size' => 'Mサイズ',
            'images' => [
                ['src' => 'option-mama-kimono/10', 'alt' => '正絹・紫しぼりの訪問着', 'caption' => '大人の品があるお着物です。'],
                ['src' => 'option-mama-kimono/11', 'alt' => '正絹・紫しぼりの訪問着の柄', 'caption' => '落ち着いたイメージをお好みのママへ。'],
                ['src' => 'option-mama-kimono/12', 'alt' => '正絹・紫しぼりの訪問着を着用した様子'],
            ],
        ],
    ];
@endphp

<x-layouts.app :seo="$seo" hero-image="option-mama-kimono/01" :cta-query="$reserveQuery">

    <x-page-hero title="お母様訪問着のご案内" en="Mama kimono" image="option-mama-kimono/01" lead="お母様の訪問着レンタル一式をご準備しております。当日お決めいただきます。" />

    {{-- 料金 --}}
    <x-section width="narrow" id="price" class="scroll-mt-20">
        <div class="prose-mellow mb-12 text-center">
            <p>お母様の訪問着レンタル一式をご準備しております。<br>当日お決めいただきます。</p>
        </div>

        <x-heading en="Price">料金</x-heading>
        <ul class="grid gap-4 sm:grid-cols-3">
            <li class="flex flex-col rounded-sm border border-brand-200 bg-white p-6 text-center">
                <h3 class="tracking-[0.08em] text-brand-800">ママ着付け</h3>
                <p class="mt-3 text-2xl tracking-wider text-brand-800">¥ 9,900<span class="ml-1 text-xs">税込</span></p>
            </li>
            <li class="flex flex-col rounded-sm border border-brand-200 bg-white p-6 text-center">
                <h3 class="tracking-[0.08em] text-brand-800">ママ着物レンタル（単品）</h3>
                <p class="mt-3 text-2xl tracking-wider text-brand-800">¥ 5,500<span class="ml-1 text-xs">税込</span></p>
            </li>
            <li class="flex flex-col rounded-sm border border-dashed border-brand-200 bg-brand-50 p-6 text-center">
                <h3 class="tracking-[0.08em] text-brand-600">ママヘアセット（単品）</h3>
                <p class="mt-3 text-2xl tracking-wider text-brand-500 line-through decoration-1">¥ 5,500<span class="ml-1 text-xs">税込</span></p>
                <p class="mt-2 text-xs leading-relaxed">(20分内でできる簡単なヘアセットになります)</p>
                <p class="mt-2 text-xs font-medium text-alert">⇧現在、施行不可です❌</p>
            </li>
        </ul>

        <x-notice title="⚠ メイクは付きません！" tone="alert" class="mt-10">
            <p><strong>メイクは付きません！</strong><br><strong>メイクをせずにお越しになられてしまう方がいます！</strong><br><strong>本当に！お気をつけください！</strong></p>
        </x-notice>

        <div class="mt-10 flex flex-col justify-center gap-3 sm:flex-row">
            <x-button :href="route('reservation', $reserveQuery)" size="lg" arrow>訪問着レンタルを含めて予約する</x-button>
            <x-button :href="route('plans.shichigosan')" variant="outline" size="lg" arrow>七五三プランを見る</x-button>
        </div>
    </x-section>

    {{-- 着物一覧 --}}
    <x-section tone="white">
        <x-heading en="Kimono">訪問着のご紹介</x-heading>
        <div class="space-y-16">
            @foreach ($kimonos as $kimono)
                <article>
                    <h3 class="text-center text-lg tracking-[0.12em] text-brand-800">{{ $kimono['name'] }}</h3>
                    <p class="mt-1 text-center text-sm text-brand-600">{{ $kimono['size'] }}</p>
                    <x-gallery :images="$kimono['images']" class="mt-6" :label="$kimono['name'].'の訪問着'" />
                </article>
            @endforeach
        </div>
        <p class="mt-14 text-center text-sm leading-loose">クリーニングによりご用意がない着物もございます。<br>当日、実際のお着物を見てお決めください。</p>
    </x-section>

    {{-- information --}}
    <x-section width="narrow">
        <x-heading en="Information">ご利用にあたって</x-heading>
        <ul class="divide-y divide-brand-200 border-y border-brand-200 text-sm leading-loose">
            <li class="py-4">・L寸<br>（167㎝以上の方はご利用いただけません）</li>
            <li class="py-4">・サイズや身長が合わない場合、お着物をお持ちいただければ着用可能です。<br>その場合、着付け及びヘアセットのみお選びください。</li>
            <li class="py-4">・着物や小物（帯やバック・草履及び着付け小物等）<br>全てこちらでご準備をいたします。</li>
            <li class="py-4">・当日は、着付け師を依頼してお着付けをいたします。<br>そのため、予約時間に遅れた場合、着用ができなくなる、また着付け料金はご返金いたしかねます。</li>
            <li class="py-4">・お着物のみのレンタルは行っておりません。</li>
        </ul>
    </x-section>

</x-layouts.app>
