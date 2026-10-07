@php
    $reserveQuery = ['plan' => 'other', 'message' => 'ご希望プラン：マタニティー'];
@endphp

<x-layouts.app :seo="$seo" hero-image="plan-maternity/04" :cta-query="$reserveQuery">

    <x-page-hero title="Hello Baby ." en="Maternity" image="plan-maternity/04" lead="もうすぐ産まれてきてくれる赤ちゃんも新しい家族の一員。" />

    {{-- 導入 --}}
    <x-section width="narrow" class="text-center">
        <div class="prose-mellow space-y-6">
            <p>もうすぐ産まれてきてくれる赤ちゃんも<br>新しい家族の一員。</p>
            <p>産まれてくる赤ちゃんを想う、<br>夫婦のカタチを<br>シンプルで<br>自然体の飾りたくなるような写真でお届けします。</p>
        </div>
    </x-section>

    {{-- プランと料金 --}}
    <x-section id="plans" tone="white" width="narrow" class="scroll-mt-20">
        <x-heading en="Plan">プランと料金</x-heading>
        <div class="rounded-sm border border-brand-200 bg-brand-50 p-6 text-center leading-loose sm:p-10">
            <p class="text-lg tracking-[0.08em] text-brand-800">※ 金額及びプランは、<br>通常プランが適応となります。</p>
            <div class="mt-8 grid gap-4 text-sm sm:grid-cols-2">
                <div class="rounded-sm bg-white p-5">
                    <p>生花スタイリングをつけたい方は</p>
                    <x-button :href="route('plans.flower-styling')" variant="outline" size="sm" class="mt-3" arrow>生花Styling Plan</x-button>
                </div>
                <div class="rounded-sm bg-white p-5">
                    <p>生花スタイリング無しの方は</p>
                    <x-button :href="route('plans.anniversary')" variant="outline" size="sm" class="mt-3" arrow>Anniversary Plan</x-button>
                </div>
            </div>
            <x-button :href="route('reservation', $reserveQuery)" class="mt-8" arrow>マタニティー撮影を予約する</x-button>
        </div>
    </x-section>

    {{-- エモーショナルな写真 --}}
    <x-section>
        <div class="grid items-center gap-10 md:grid-cols-2 md:gap-16">
            <x-picture src="plan-maternity/01" alt="暗がりでお腹に手を添える妊婦さんのマタニティフォト" sizes="(min-width: 768px) 50vw, 100vw" class="w-full" />
            <div>
                <x-heading en="Emotional" align="left">エモーショナルな写真</x-heading>
                <div class="prose-mellow space-y-5">
                    <p>女性だからわかること。</p>
                    <p>ハッキリ映らなくていい。<br>顔もしっかり映ってなくてもいい。</p>
                    <p>ただ、<br>イメージ写真のような<br>趣のあるmaternityを綺麗に撮ってほしい。</p>
                </div>
            </div>
        </div>

        <div class="mt-20 grid items-center gap-10 md:grid-cols-2 md:gap-16">
            <div class="md:order-2">
                <x-picture src="plan-maternity/02" alt="お子様と一緒に撮影したマタニティフォト" sizes="(min-width: 768px) 50vw, 100vw" class="w-full" />
            </div>
            <div class="md:order-1">
                <x-heading en="With kids" align="left">お子様も一緒に</x-heading>
                <div class="prose-mellow">
                    <p>ママとのラブラブな写真も。<br>お姉ちゃんお兄ちゃんになる楽しみを<br>感じながら。</p>
                </div>
            </div>
        </div>
    </x-section>

    {{-- ギャラリー --}}
    <x-section tone="white">
        <x-heading en="Gallery">マタニティーの撮影フォト</x-heading>
        <x-gallery :columns="3" label="マタニティーの撮影フォト"
                   :images="collect(range(3, 14))->map(fn ($n) => ['src' => sprintf('plan-maternity/%02d', $n), 'alt' => 'Mellowで撮影したマタニティフォト'])->all()" square />
    </x-section>

    {{-- Information --}}
    <x-section width="narrow">
        <x-heading en="Information">撮影について</x-heading>
        <div class="prose-mellow">
            <ul class="list-disc space-y-4 pl-5">
                <li>大人の衣装もご準備しております。<br>スタジオのコンセプト上、ドレスのご準備がございません。<br>カジュアルな衣装をご提案しております。</li>
                <li>衣装のお持ち込みも可能です。</li>
                <li>ヘアセット・スタイリングまでさせていただきます。<br>※ メイクはございません。</li>
                <li>男性の衣装のご準備はございません。</li>
                <li>お子様の衣装もお貸出可能です。</li>
                <li>生花stylingをご希望の方・・・maternity撮影は、背景や小物として、生花をお入れして撮影をさせていただきます。</li>
            </ul>
        </div>
    </x-section>

</x-layouts.app>
