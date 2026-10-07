@php
    $reserveQuery = ['message' => 'スマッシュケーキ撮影（ケーキお持ち込み）を希望'];
@endphp

<x-layouts.app :seo="$seo" hero-image="option-smash-cake/01" :cta-query="$reserveQuery">

    <x-page-hero title="smash cake photo" en="Smash cake" image="option-smash-cake/01" lead="市販のケーキでもママの手作りでも、お好きなケーキを持ってきてください🧁" />

    {{-- 導入 --}}
    <x-section width="narrow" class="text-center">
        <x-heading en="Smash cake">smash cake photo</x-heading>
        <div class="prose-mellow">
            <p>スマッシュケーキは、<br>市販のケーキでもママの手作りでも<br>どちらも可愛いですよ♡<br>お好きなケーキを持ってきてください🧁</p>
        </div>

        <x-notice title="⚠️ ご希望のプランについて" tone="alert" class="mt-10 text-left">
            <p><strong>20カットPlanをご希望の方は、スマッシュケーキ撮影＋家族写真になります。<br>その他のバリエーションもご希望の方は、50カットPlanをご選択ください♩</strong></p>
        </x-notice>

        <div class="mt-10">
            <x-gallery :images="[
                ['src' => 'option-smash-cake/02', 'alt' => 'スマッシュケーキ撮影をする1歳の子ども'],
                ['src' => 'option-smash-cake/03', 'alt' => 'ケーキを前にしたスマッシュケーキ撮影の様子'],
            ]" :columns="2" label="スマッシュケーキ撮影の写真" />
        </div>
    </x-section>

    {{-- 料金と注意事項 --}}
    <x-section tone="white" id="price" class="scroll-mt-20">
        <x-heading en="Price">ケーキの持ち込み料金</x-heading>

        <div class="grid items-start gap-10 md:grid-cols-[2fr_3fr]">
            <x-price-card name="ケーキの持ち込み料金" badge="smash cake"
                          :prices="[['持ち込み料金', 1500]]" :reserve="route('reservation', $reserveQuery)" reserve-label="予約フォームで申し込む">
                <p class="text-xs">（支度料及び片付け料込み）</p>
                <ul class="mt-4 font-medium text-brand-800">
                    <li>木製のケーキスタンドお貸出できます</li>
                    <li>ケーキストッパーはありません</li>
                </ul>
            </x-price-card>

            <div class="rounded-sm border border-brand-200 bg-brand-50 p-6 sm:p-8">
                <h3 class="text-lg tracking-[0.08em] text-brand-800">ご予約前にご確認ください</h3>
                <ul class="mt-6 space-y-5 text-sm leading-loose">
                    <li>
                        <p class="font-medium text-brand-800">・ケーキはお持ち込みです！</p>
                        <p class="pl-4">スタジオでのご準備はございません。</p>
                    </li>
                    <li>
                        <p class="font-medium text-brand-800">・ケーキの配送はお断りをしております。</p>
                        <p class="pl-4">撮影中や外部撮影のため受け取りができません。<br>配送はNGです。</p>
                    </li>
                    <li>
                        <p class="font-medium text-brand-800">・スタジオにシャワーはありません！</p>
                        <p class="pl-4">簡易的に流せる洗面所のみになります。</p>
                    </li>
                    <li>
                        <p class="font-medium text-brand-800">・タオルやビニール袋をお持ちください。</p>
                        <p class="pl-4">お子様の体を拭いてあげるタオルです。</p>
                    </li>
                    <li>
                        <p class="font-medium text-brand-800">・スタジオ衣装はお貸しできません！</p>
                        <p class="pl-4">おむつ姿もしくはお持ち込み衣装のみ<br>撮影可能です。</p>
                    </li>
                    <li>
                        <p class="font-medium text-brand-800">・背景紙での撮影も可能です。</p>
                        <p class="pl-4">ピンクorブルーの背景紙をご利用いただけます。</p>
                    </li>
                    <li>
                        <p class="font-medium text-brand-800">・ご予約時にスマッシュケーキお持ち込みの旨、お伝えください。</p>
                    </li>
                </ul>
            </div>
        </div>

        <div class="mt-12 flex flex-col justify-center gap-3 sm:flex-row">
            <x-button :href="route('reservation', $reserveQuery)" size="lg" arrow>スマッシュケーキ撮影を予約する</x-button>
            <x-button :href="route('plans.index')" variant="outline" size="lg" arrow>撮影プランを見る</x-button>
        </div>
    </x-section>

    {{-- ギャラリー --}}
    <x-section>
        <x-heading en="Gallery">スマッシュケーキ撮影のフォト</x-heading>
        <x-gallery :images="[
            ['src' => 'option-smash-cake/04', 'alt' => 'スマッシュケーキ撮影の写真'],
            ['src' => 'gallery/38', 'alt' => '生花に囲まれてケーキを食べる1歳の子ども'],
            ['src' => 'option-smash-cake/05', 'alt' => 'スマッシュケーキ撮影の写真'],
        ]" label="スマッシュケーキ撮影のフォト" />
    </x-section>

</x-layouts.app>
