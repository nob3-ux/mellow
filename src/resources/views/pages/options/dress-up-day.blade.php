@php
    $reserveQuery = ['message' => 'dress upオプション追加'];
@endphp

<x-layouts.app :seo="$seo" hero-image="option-dress-up-day/01" :cta-query="$reserveQuery">

    <x-page-hero title="dress up day." en="Dress up option" image="option-dress-up-day/01" lead="ママもdress upを" />

    {{-- 導入 --}}
    <x-section width="narrow" class="text-center">
        <x-heading en="Dress up">ママもdress upを</x-heading>
        <div class="prose-mellow space-y-6">
            <p>年に一度のこの日は<br>ママもキラキラしてほしい日</p>
            <p>いつかの結婚式のように<br>ママもドレスを着てヘアセットもして</p>
            <p>どんどん綺麗になる姿に<br>自分も心が躍るけど<br>それを見る子どもたちもパパも<br>ワクワクする瞬間</p>
            <p>家族ができて<br>パパとママの間には<br>我が子の存在があるのが当たり前</p>
            <p>今日は<br>パパと手を繋いでみたり<br>きゅっと抱きついたり</p>
            <p>いつもとは少し違う<br>家族でみんなで<br>dress upした<br>家族写真<br>是非お楽しみください。</p>
        </div>
    </x-section>

    {{-- オプション内容と料金 --}}
    <x-section tone="white" id="price" class="scroll-mt-20">
        <x-heading en="Option">dress up option</x-heading>

        <div class="grid items-start gap-10 md:grid-cols-2">
            <div class="prose-mellow space-y-6 text-center md:text-left">
                <p>通常のフォトプランに<br>＋オプションで<br>ママのヘアセットと<br>ドレス1着をお貸しいたします。</p>
                <p>お子様のソロ写真を含め<br>家族写真<br>お子様との2ショット<br>ご夫婦写真をたっぷり残す<br>スペシャルな撮影オプション。</p>
                <div class="pt-2">
                    <x-button :href="route('gallery.wedding')" variant="outline" arrow>dress image</x-button>
                </div>
            </div>

            <x-price-card name="dress up option" catch="通常のフォトプランに＋オプションで" badge="ママもdress up"
                          :prices="[['オプション価格 ＋', 18000]]">
                <h4 class="font-medium tracking-[0.08em] text-brand-800">ヘアスタイリング料金込み</h4>
                <ul class="mt-2">
                    <li>ママとお子様のヘアセット込みのご料金です。</li>
                    <li>ママのポイントメイクもさせていただきます。</li>
                </ul>
                <p class="mt-1 text-xs">※フルメイクをご希望の場合は + ¥5,000</p>

                <h4 class="mt-6 font-medium tracking-[0.08em] text-brand-800">ドレス1着お貸出</h4>
                <ul class="mt-2">
                    <li>9号までのサイズは10着ほどご準備があります。<br>9号以上の方は、お持ち込みをお願いいたします。<br><span class="text-xs">※ メンズ衣装及びブライダルインナー、靴はご持参ください。</span></li>
                    <li>お子様の衣装もお貸出OK</li>
                </ul>

                <h4 class="mt-6 font-medium tracking-[0.08em] text-brand-800">ドレスセレクト</h4>
                <ul class="mt-2">
                    <li>当日、ドレスをセレクトいただきます。<br>事前の衣装合わせ等はございません。</li>
                </ul>
            </x-price-card>
        </div>
    </x-section>

    {{-- ご予約について --}}
    <x-section width="narrow" class="text-center">
        <x-heading en="Reservation">ご予約について</x-heading>
        <div class="prose-mellow">
            <p>ご予約の際、<br>備考欄へ<strong>「dress upオプション追加」</strong><br>とご記載くださいませ。</p>
        </div>
        <p class="mt-4 text-xs leading-relaxed text-brand-700">下のボタンから予約フォームへ進むと、備考欄に「dress upオプション追加」が入力された状態で開きます。</p>
        <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
            <x-button :href="route('reservation', $reserveQuery)" size="lg" arrow>ご予約はこちら</x-button>
            <x-button :href="route('plans.index')" variant="outline" size="lg" arrow>フォトプランを見る</x-button>
        </div>
    </x-section>

</x-layouts.app>
