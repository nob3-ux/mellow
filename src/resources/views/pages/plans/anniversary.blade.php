@php
    $reserve = fn (string $plan) => route('reservation', ['plan' => $plan, 'style' => 'anniversary']);
@endphp

<x-layouts.app :seo="$seo" hero-image="plan-anniversary/04" :cta-query="['plan' => 'standard', 'style' => 'anniversary']">

    <x-page-hero title="Anniversary Plan" en="Anniversary" image="plan-anniversary/04" lead="生花が無くても可愛いんです。" />

    {{-- 導入 --}}
    <x-section width="narrow" class="text-center">
        <div class="prose-mellow">
            <p>可愛いを卒業したBOYや<br>生花が無い写真をご希望の方など。<br>生花が無くても可愛いんです。</p>
        </div>

        <div class="mt-10 rounded-sm bg-white p-6 text-left text-sm leading-loose sm:p-8">
            <h2 class="mb-3 text-center font-display tracking-[0.2em] text-brand-700 italic">Information</h2>
            <p>「生花styling plan」と「Anniversary plan」の違いは、生花の有無のみです。</p>
            <p>背景に生花を入れたい方、生花を結いたい方は、「<a href="{{ route('plans.flower-styling') }}" class="underline">生花styling plan</a>」をご選択ください。</p>
            <p>どちらのプランも、あらゆる記念日（birthday・1/2birthday・お宮参り・newborn・家族写真・兄弟姉妹撮影）をお撮りいたします。</p>
        </div>

        <div class="prose-mellow mt-10">
            <p>含まれるもの：<br>衣装・ヘアメイク・撮影料金</p>
            <p>＜スタジオ衣装は、<a href="{{ config('site.contact.instagram_url') }}" target="_blank" rel="noopener">Instagram</a>ストーリー⇨衣装♡をご覧ください＞</p>
        </div>

        <div class="mt-10 flex flex-col justify-center gap-3 sm:flex-row">
            <x-button href="#plans">プランと料金を見る</x-button>
            <x-button :href="route('plans.flower-styling')" variant="outline" arrow>生花ありの生花Styling Plan</x-button>
        </div>
    </x-section>

    {{-- プランと料金 --}}
    <x-section id="plans" tone="white" class="scroll-mt-20">
        <x-heading en="Plan">プランと料金</x-heading>

        <div class="grid gap-8 md:grid-cols-2">
            <x-price-card name="スタンダード50カット Anniversary Plan" catch="バリエーションと衣装をたっぷり味わいたい方へ" badge="50カット保証"
                          :prices="[['平日', 33000], ['土日祝', 38000]]" :reserve="$reserve('standard')">
                <p class="mb-3 text-xs tracking-wider text-brand-600">＊ プラン内容 ＊</p>
                <ul>
                    <li>90〜120分制（完全プライベート空間）</li>
                    <li>衣装は何着でもOK＾＾</li>
                    <li>スタジオにも衣装のご準備あります。</li>
                    <li>お持ち込み洋服も大歓迎です◎<br>ご兄弟の衣装も無料にてお貸しいたします。</li>
                    <li>50カット保証（50カット以上お渡ししています♡）</li>
                </ul>
                <p class="mt-4">※背景に生花を入れたい場合は<a href="{{ route('plans.flower-styling') }}" class="underline">生花styling plan</a>が適応です。</p>
                <p class="mt-4">家族写真やご兄弟写真お撮りいたします（無料）<br>家族写真やママとの2ショットなど、推奨スタジオです♩</p>
                <div class="mt-5 rounded-sm border border-brand-200 p-4 text-center">
                    <p>ママもドレスを着れるスペシャルなオプション<br><span class="text-xs">（スタンダード50のみ利用可）</span></p>
                    <x-button :href="route('options.dress-up-day')" variant="outline" size="sm" class="mt-3" arrow>dress up オプション</x-button>
                </div>
                <x-slot:options>
                    <p><a href="{{ route('options.smash-cake') }}" class="underline">スマッシュケーキ持込撮影　＋1,500円</a></p>
                </x-slot:options>
            </x-price-card>

            <x-price-card name="スタンダード20カット Anniversary Plan" catch="気軽に手軽にお試しで写真を残したい方へ"
                          :prices="[['平日', 20000], ['土日祝', 23000]]" :reserve="$reserve('small')">
                <p class="mb-3 text-xs tracking-wider text-brand-600">＊ プラン内容 ＊</p>
                <ul>
                    <li>60分制（完全プライベート空間）</li>
                    <li>衣装1着（おむつショットはおまけ♡）</li>
                    <li>スタジオにも衣装のご準備あります。</li>
                    <li>お持ち込み洋服も大歓迎です◎<br>ご兄弟の衣装もお貸しいたします（無料）</li>
                    <li>全20カット</li>
                </ul>
                <p class="mt-4">家族写真やご兄弟写真もお撮りします（無料）<br>家族写真やママとの2ショットなど、推奨スタジオです。</p>
                <p class="mt-4"><strong>＊メインのお子様1人追加、＋5,500円10カット増枚いたします。</strong><br>ご予約時にお伝えください。<br>バリエーション多く撮影をご希望の場合は、50カットプランをご選択ください。</p>
                <x-slot:options>
                    <p><a href="{{ route('options.smash-cake') }}" class="underline">スマッシュケーキ撮影　＋1,500円</a></p>
                </x-slot:options>
            </x-price-card>
        </div>

        {{-- スマッシュケーキ --}}
        <a href="{{ route('options.smash-cake') }}" class="group mx-auto mt-14 flex max-w-3xl flex-col items-center gap-6 rounded-sm bg-brand-50 p-6 transition hover:shadow-lg sm:flex-row sm:p-8">
            <x-picture src="plan-flower-styling/04" alt="スマッシュケーキを楽しむ1歳の女の子" sizes="(min-width: 640px) 200px, 100vw" class="aspect-square w-full object-cover sm:w-48" />
            <p class="text-sm leading-loose text-brand-800">スマッシュケーキをご希望の方はこちらをご確認ください。<span class="mt-2 block text-brand-600 group-hover:underline">スマッシュケーキ撮影について →</span></p>
        </a>
    </x-section>

    {{-- ギャラリー --}}
    <x-section>
        <x-heading en="Gallery">Anniversaryの撮影フォト</x-heading>
        <x-gallery square :columns="4" label="Anniversaryの撮影フォト"
                   :images="collect(range(1, 12))->map(fn ($n) => ['src' => sprintf('plan-anniversary/%02d', $n), 'alt' => '生花なしのAnniversary Planで撮影したお子様の写真'])->all()" />
        <div class="mt-10 text-center">
            <x-button :href="route('gallery')" variant="outline" arrow>ギャラリーをもっと見る</x-button>
        </div>
    </x-section>

</x-layouts.app>
