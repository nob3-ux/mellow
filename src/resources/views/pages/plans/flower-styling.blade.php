@php
    $reserve = fn (string $plan) => route('reservation', ['plan' => $plan, 'style' => 'flower']);
@endphp

<x-layouts.app :seo="$seo" hero-image="plan-flower-styling/06" :cta-query="['plan' => 'standard', 'style' => 'flower']">

    <x-page-hero title="生花Styling Photo" en="Flower Styling" image="plan-flower-styling/06" lead="生花でお子様をより可愛く彩る写真" />

    {{-- 導入 --}}
    <x-section width="narrow" class="text-center">
        <x-picture src="plan-flower-styling/01" alt="生花を髪に飾った女の子の写真" sizes="(min-width: 768px) 768px, 100vw" class="w-full" />
        <div class="prose-mellow mt-10">
            <p>含まれるもの：<br>生花1人分料金・衣装・ヘアメイク・撮影料金</p>
            <p>＜スタジオ衣装は、<a href="{{ config('site.contact.instagram_url') }}" target="_blank" rel="noopener">Instagram</a>ストーリー⇨衣装♡をご覧ください＞</p>
        </div>
        <div class="mt-10 flex flex-col justify-center gap-3 sm:flex-row">
            <x-button href="#plans">プランと料金を見る</x-button>
            <x-button :href="route('plans.anniversary')" variant="outline" arrow>生花なしのAnniversary Plan</x-button>
        </div>
    </x-section>

    {{-- 生花スタイリングとは --}}
    <x-section tone="white">
        <x-heading en="About">生花スタイリングとは？</x-heading>
        <x-gallery :columns="2" label="生花スタイリングのご案内" :images="[
            ['src' => 'plan-flower-styling/02', 'alt' => '生花スタイリングとは？生花でお子様をより可愛く彩る写真。どのくらいの生花を使う？20カットPlanは2種類ほど、50カットPlanは2〜3.4種類ほどの生花（生花代／仕入れ代／スタイリング料／背景使用料を含めた金額設定）。生花の種類指定はできません。色味（ピンク系・ブルー系など）の指定は可能です。ご予約時にお伝えください。'],
            ['src' => 'plan-flower-styling/03', 'alt' => '生花は、高価なもの。たくさんのお花をご希望される方もいらっしゃるのですが、その場合は追加料金とさせていただきます。ご了承いただけるとありがたいです。※頭を覆うようなモリモリの生花をご希望の場合は、＋3,300円でご用意可能です。または、お好きな生花をお持ちいただいても結構です。'],
        ]" />
    </x-section>

    {{-- プランと料金 --}}
    <x-section id="plans" class="scroll-mt-20">
        <x-heading en="Plan">プランと料金</x-heading>

        <div class="grid gap-8 md:grid-cols-2">
            <x-price-card name="スタンダード50カット 生花styling Plan" catch="バリエーションと衣装をたっぷり味わいたい方へ" badge="50カット保証"
                          :prices="[['平日', 35000], ['土日祝', 40000]]" :reserve="$reserve('standard')">
                <p class="mb-3 text-xs tracking-wider text-brand-600">＊ information ＊</p>
                <ul>
                    <li>90〜120分制（完全プライベート空間）</li>
                    <li>衣装は何着でもOK＾＾</li>
                    <li>スタジオにも衣装のご準備あります（無料）</li>
                    <li>お持ち込み洋服も大歓迎です◎<br>ご兄弟の衣装もお貸しいたします（無料）130㎝まで</li>
                    <li><strong>50カット保証（50カット以上お渡ししています♡）</strong></li>
                </ul>
                <p class="mt-4"><strong>※背景に生花を入れたい場合も生花styling planが適応です。</strong></p>
                <p class="mt-4">家族写真やご兄弟写真ももちろんお撮りします（無料）<br>家族写真やママとの2ショットなど、推奨スタジオです◎</p>
                <div class="mt-5 rounded-sm border border-brand-200 p-4 text-center">
                    <p>ママもドレスを着れるスペシャルなオプション<br><span class="text-xs">（スタンダード50のみ利用可）</span></p>
                    <x-button :href="route('options.dress-up-day')" variant="outline" size="sm" class="mt-3" arrow>dress up オプション</x-button>
                </div>
                <x-slot:options>
                    <p><a href="{{ route('options.smash-cake') }}" class="underline">＋スマッシュケーキ持込撮影 ¥1,500</a></p>
                    <p>＋姉妹の生花追加 ¥2,200</p>
                </x-slot:options>
            </x-price-card>

            <x-price-card name="スタンダード20カット 生花styling Plan" catch="気軽に手軽にお試しで写真を残したい方へ"
                          :prices="[['平日', 22000], ['土日祝', 25000]]" :reserve="$reserve('small')">
                <p class="mb-3 text-xs tracking-wider text-brand-600">＊ プラン内容 ＊</p>
                <ul>
                    <li>60分制（完全プライベート空間）</li>
                    <li>衣装は1着のみ（おむつショットはおまけ♡）</li>
                    <li>全20カット</li>
                </ul>
                <p class="mt-4">＊家族写真やご兄弟写真もお撮りします（無料）</p>
                <p class="mt-4"><strong>＊メインのお子様1人追加、＋5,500円&amp;10カット増枚いたします。</strong><br>バリエーションを多くご希望の場合は、50カットプランをご選択ください。</p>
                <x-slot:options>
                    <p><a href="{{ route('options.smash-cake') }}" class="underline">スマッシュケーキ持込撮影 ＋1,500円</a></p>
                </x-slot:options>
            </x-price-card>
        </div>

        {{-- スマッシュケーキ --}}
        <a href="{{ route('options.smash-cake') }}" class="group mx-auto mt-14 flex max-w-3xl flex-col items-center gap-6 rounded-sm bg-white p-6 transition hover:shadow-lg sm:flex-row sm:p-8">
            <x-picture src="plan-flower-styling/04" alt="スマッシュケーキを楽しむ1歳の女の子" sizes="(min-width: 640px) 200px, 100vw" class="aspect-square w-full object-cover sm:w-48" />
            <p class="text-sm leading-loose text-brand-800">スマッシュケーキをご希望の方はこちらを必ずご確認ください。<span class="mt-2 block text-brand-600 group-hover:underline">スマッシュケーキ撮影について →</span></p>
        </a>
    </x-section>

    {{-- Information --}}
    <x-section tone="white" width="narrow">
        <x-heading en="Information">生花についてのご案内</x-heading>
        <div class="prose-mellow">
            <ul class="list-disc space-y-3 pl-5">
                <li>当日スタジオにご用意した生花よりセレクトしております。</li>
                <li>生花の増量（増数）は、数に限りがございますのでご遠慮を頂いております。</li>
                <li>小さなお子様の場合、髪の毛にお花を結うことができかねる場合がございます。<br>コンディション優先が第一です。<br>男の子や髪に結うことができない場合は、背景や手に持ったりなど対応をさせていただきます。</li>
            </ul>
            <p class="mt-6">※生花のお持ち込みも可能です◎<br>花モリモリのスタイリングをご希望の方は、是非お持ちください＾＾</p>
        </div>
    </x-section>

    {{-- ギャラリー --}}
    <x-section>
        <x-heading en="Gallery">生花Stylingの撮影フォト</x-heading>
        <x-gallery square label="生花Stylingの撮影フォト" :images="collect(['plan-flower-styling/05', 'plan-flower-styling/06', 'plan-flower-styling/07', 'plan-flower-styling/08', 'plan-flower-styling/09', 'plan-flower-styling/10', 'plan-flower-styling/11', 'gallery/08', 'plan-flower-styling/12', 'plan-flower-styling/13', 'plan-flower-styling/14', 'plan-flower-styling/15'])->map(fn ($src) => ['src' => $src, 'alt' => '生花スタイリングで撮影したお子様の写真'])->all()" :columns="4" />
        <div class="mt-10 text-center">
            <x-button :href="route('gallery')" variant="outline" arrow>ギャラリーをもっと見る</x-button>
        </div>
    </x-section>

</x-layouts.app>
