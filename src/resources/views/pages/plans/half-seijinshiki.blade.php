@php
    $reserve = fn (string $plan) => route('reservation', ['plan' => 'other', 'message' => 'ご希望プラン：ハーフ成人式 '.$plan]);
@endphp

<x-layouts.app :seo="$seo" hero-image="plan-half-seijinshiki/03" :cta-query="['plan' => 'other', 'message' => 'ご希望プラン：ハーフ成人式']">

    <x-page-hero title="ハーフ成人式の君へ。" en="Half Seijinshiki" image="plan-half-seijinshiki/03" lead="10歳の記念日、おめでとう。" />

    {{-- 導入 --}}
    <x-section width="narrow" class="text-center">
        <div class="prose-mellow space-y-6">
            <p>10歳の記念日、おめでとう。</p>
            <p>少しづつ大人へ変わる時期の10歳。</p>
            <p>男の子は、<br>身長が伸び体格がよくなり<br>キリッと凛々しくかっこよく。</p>
            <p>女の子は、しなやかに美しく綺麗な姿へ。</p>
            <p>新しいお祝いの文化「ハーフ成人式」</p>
            <p>着物姿をしっかりと形に残す大切な行事。</p>
        </div>
        <div class="mt-10 flex flex-col justify-center gap-3 sm:flex-row">
            <x-button href="#plans">プランと料金を見る</x-button>
            <x-button :href="route('gallery.kimono')" variant="outline" arrow>着物ギャラリーを見る</x-button>
        </div>
    </x-section>

    {{-- プランと料金 --}}
    <x-section id="plans" tone="white" class="scroll-mt-20">
        <x-heading en="Plan">プランと料金</x-heading>

        <div class="mx-auto mb-10 max-w-3xl text-center text-sm leading-loose">
            <p>含まれるもの：<br>着物・ヘアメイク・着付け・撮影料金</p>
            <p class="mt-2">※生花stylingをご希望の場合、＋2,200円</p>
        </div>

        <div class="mx-auto grid max-w-4xl gap-8 md:grid-cols-2">
            <x-price-card name="スタジオ着物洋服70カットプラン" badge="70カット"
                          :prices="[['平日', 43000], ['土日祝', 48000]]" :reserve="$reserve('スタジオ着物洋服70カットプラン')">
                <x-slot:options>
                    <p>※生花stylingをご希望の場合、＋2,200円</p>
                </x-slot:options>
            </x-price-card>

            <x-price-card name="スタジオ着物50カットプラン"
                          :prices="[['平日', 35000], ['土日祝', 40000]]" :reserve="$reserve('スタジオ着物50カットプラン')">
                <x-slot:options>
                    <p>※生花stylingをご希望の場合、＋2,200円</p>
                </x-slot:options>
            </x-price-card>
        </div>
    </x-section>

    {{-- 本人のペースで --}}
    <x-section>
        <div class="grid items-center gap-10 md:grid-cols-2 md:gap-16">
            <x-picture src="plan-half-seijinshiki/01" alt="柔らかな表情の10歳の女の子" sizes="(min-width: 768px) 50vw, 100vw" class="w-full" />
            <div>
                <x-heading en="Our style" align="left">本人のペースで…</x-heading>
                <div class="prose-mellow space-y-5">
                    <p>体も成長をして心も同じように成長を。<br>だからこそ、<br>カメラマンとお子様との関係性や<br>会話がとても重要なのです。</p>
                    <p>お子様のペースで本人の好きな表情を大切に、<br>子供らしい表情も出せる撮影をお楽しみください。</p>
                </div>
            </div>
        </div>

        <x-gallery class="mt-16" :columns="3" label="ハーフ成人式の撮影フォト" :images="[
            ['src' => 'plan-half-seijinshiki/02', 'alt' => 'ハーフ成人式の着物姿の写真'],
            ['src' => 'plan-half-seijinshiki/03', 'alt' => '袴姿で窓辺に立つ10歳の女の子'],
            ['src' => 'plan-half-seijinshiki/04', 'alt' => 'ハーフ成人式の着物姿の写真'],
        ]" square />
    </x-section>

    {{-- Information --}}
    <x-section tone="white" width="narrow">
        <x-heading en="Information">お着物について</x-heading>
        <div class="prose-mellow">
            <ul class="list-disc space-y-4 pl-5">
                <li>お着物のお持ち込み（袴のみのお貸し出し）も可能です。</li>
                <li>着付け小物/ブーツ等はこちらでご準備させていただきます。（Mellowは足袋ブーツのご用意です）</li>
                <li>男の子のお着物もご準備ございます。</li>
            </ul>
        </div>
    </x-section>

</x-layouts.app>
