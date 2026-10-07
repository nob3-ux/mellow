@php
    $reserve = fn (string $plan) => route('reservation', ['plan' => 'shichigosan', 'shichigosan_plan' => $plan]);
@endphp

<x-layouts.app :seo="$seo" hero-image="plan-shichigosan/04" :cta-query="['plan' => 'shichigosan']">

    <x-page-hero title="七五三プラン" en="Shichi-Go-San" image="plan-shichigosan/04" lead="「こんなに大きくなりました。」" />

    {{-- 導入 --}}
    <x-section width="narrow" class="text-center">
        <div class="prose-mellow space-y-6">
            <p>3歳男女・5歳男の子・7歳女の子<br>家族にとって特別な行事の七五三。</p>
            <p>遠い親戚や祖父母様に見せるためにも、<br>写真撮影は感謝を伝える最高のツール。</p>
            <p>Mellowでは、”趣のあるアンティーク着物”をご準備しています。<br>いつの時代でも良いとされている、時代を通して素敵なものをぜひ。</p>
        </div>
        <div class="mt-10 flex flex-col justify-center gap-3 sm:flex-row">
            <x-button :href="route('gallery.kimono')" variant="outline" arrow>着物ギャラリーを見る</x-button>
            <x-button href="#plans">プランと料金を見る</x-button>
        </div>
    </x-section>

    {{-- お知らせ --}}
    <x-section tone="white" width="narrow">
        <x-heading en="News">お知らせ</x-heading>
        <div class="space-y-6">
            <x-notice title="「スタジオ＋日枝神社撮影プラン」を再開しました">
                <p>2025年6月より、廃止していた「スタジオ＋日枝神社撮影プラン」を、みなさまからの熱いご要望もあり再開させていただくことになりました。</p>
                <p>自身の運営上の問題もあり廃止にしておりましたが、幼いお子様にとってかなりハードな撮影プランということ、また雨天のリスケジュールが翌年になってしまうなど、廃止にしたあらゆる懸念事項があるプランです。</p>
                <p>こちらのプランをご希望の方は、あらゆる注意事項があることをご考慮いただいた上で、ご予約ご検討をよろしくお願いいたします。</p>
            </x-notice>
            <x-notice title="当面の間、3歳・5歳のみの受付となります" tone="alert">
                <p>7歳さんは大変申し訳ございません。再開の目処が立ち次第、ご案内をさせていただきます。</p>
            </x-notice>
        </div>
    </x-section>

    {{-- プランと料金 --}}
    <x-section id="plans" class="scroll-mt-20">
        <x-heading en="Plan">プランと料金</x-heading>

        <h3 class="mb-8 text-center text-lg tracking-[0.12em] text-brand-800">スタジオで撮影をしたい！</h3>
        <div class="grid gap-8 md:grid-cols-2">
            <x-price-card name="スタジオ着物洋服70カット Plan" catch="着物も洋服も撮影したい方へ" badge="着物も洋服も"
                          :prices="[['平日', 43000], ['土日祝', 48000]]" :reserve="$reserve('studio_70')">
                <ul>
                    <li>120分制（完全プライベート空間）</li>
                    <li>着物1着・洋服1着ずつ</li>
                    <li>着物・洋服レンタル無料</li>
                    <li>お持ち込みも大歓迎です◎（お持ち込みの場合でもご料金は同一です）</li>
                    <li>ご兄弟の洋服もお貸しいたします（無料）</li>
                    <li>70カット保証</li>
                    <li>撮影バリエーションはご相談しながら決めましょう！</li>
                </ul>
                <p class="mt-4">家族写真やご兄弟写真ももちろんお撮りします（無料）。家族写真やママとの2ショットなど、推奨スタジオです◎</p>
                <x-slot:options>
                    <p>女の子3歳・7歳 帯着用の場合 ＋5,500円</p>
                </x-slot:options>
            </x-price-card>

            <x-price-card name="スタジオ着物50カット Plan" catch="着物のみをたっぷり・リーズナブルに残したい方へ"
                          :prices="[['平日', 38000], ['土日祝', 42000]]" :reserve="$reserve('studio_50')">
                <ul>
                    <li>60〜90分制（完全プライベート空間）</li>
                    <li>着物1着</li>
                    <li>着物レンタル無料</li>
                    <li>お持ち込みも大歓迎です◎（お持ち込みの場合でもご料金は同一です）</li>
                    <li>50カット保証</li>
                    <li>撮影バリエーションはご相談しながら決めましょう！</li>
                </ul>
                <p class="mt-4">家族写真やご兄弟写真ももちろんお撮りします（無料）。家族写真やママとの2ショットなど、推奨スタジオです◎</p>
                <x-slot:options>
                    <p>女の子3歳・7歳 帯着用の場合 ＋5,500円</p>
                    <p>生花スタイリング追加 ＋3,300円（背景に入れたい場合でも同様です）</p>
                </x-slot:options>
            </x-price-card>
        </div>

        <h3 class="mt-20 mb-8 text-center text-lg tracking-[0.12em] text-brand-800">神社でのお参り写真も残したい！</h3>
        <div class="grid gap-8 md:grid-cols-2">
            <x-price-card name="日枝神社ロケ Plan" catch="スタジオでお支度＆着物レンタルも。神社お参りの写真だけを撮りたい方へ"
                          :prices="[['平日', 42000], ['土日祝', 47000]]" :reserve="$reserve('hie_location')">
                <ul>
                    <li>トータル2時間〜2時間半ほど</li>
                    <li>着物撮影のみ（洋服着用なし）</li>
                    <li>着物レンタル無料</li>
                    <li>お持ち込みも大歓迎です◎（お持ち込みの場合でもご料金は同一です）</li>
                    <li>50カット</li>
                    <li>撮影バリエーションはご相談しながら決めましょう！</li>
                </ul>
                <p class="mt-4">家族写真やご兄弟写真ももちろんお撮りします（無料）</p>
                <x-slot:options>
                    <p>女の子3歳・7歳 帯着用 ＋5,500円</p>
                    <p>スタジオ〜神社までの往復タクシー料金はお客様ご負担です</p>
                </x-slot:options>
            </x-price-card>

            <x-price-card name="スタジオ＋日枝神社ロケ Plan" catch="スタジオと神社の両方とも、しっかりと残したい方へ" badge="100カット"
                          :prices="[['平日', 66000], ['土日祝', 77000]]" :reserve="$reserve('studio_hie_100')">
                <ul>
                    <li>トータル3時間〜3時間半ほど</li>
                    <li>着物撮影のみ（洋服着用なし）</li>
                    <li>着物レンタル無料</li>
                    <li>お持ち込みも大歓迎です◎（お持ち込みの場合でもご料金は同一です）</li>
                    <li>100カット</li>
                    <li>撮影バリエーションはご相談しながら決めましょう！</li>
                </ul>
                <p class="mt-4">家族写真やご兄弟写真ももちろんお撮りします（無料）</p>
                <x-slot:options>
                    <p>女の子3歳・7歳 帯着用 ＋5,500円</p>
                    <p>スタジオ〜神社までの往復タクシー料金はお客様ご負担です</p>
                </x-slot:options>
            </x-price-card>
        </div>

        {{-- 全プラン共通 --}}
        <div class="mx-auto mt-12 max-w-3xl rounded-sm bg-white p-6 text-sm leading-loose sm:p-8">
            <h3 class="tracking-[0.1em] text-brand-800">ご兄弟の着物着用追加（全プラン共通）</h3>
            <dl class="mt-3 grid grid-cols-[auto_1fr] gap-x-6">
                <dt>男の子</dt><dd>5,500円（税込）</dd>
                <dt>3歳女の子</dt><dd>6,600円（税込）</dd>
                <dt>7歳女の子</dt><dd>8,800円（税込）</dd>
            </dl>
        </div>
    </x-section>

    {{-- 神社ロケの注意事項 --}}
    <x-section tone="white" width="narrow">
        <x-heading en="Location">神社ロケをご希望の方へ</x-heading>

        <div class="divide-y divide-brand-200 border-y border-brand-200">
            <details class="group py-5" open>
                <summary class="flex cursor-pointer list-none items-center justify-between tracking-[0.08em] text-brand-800">
                    タクシー移動について
                    <span class="text-brand-500 transition group-open:rotate-45" aria-hidden="true">＋</span>
                </summary>
                <div class="prose-mellow mt-4">
                    <ul class="list-disc space-y-2 pl-5">
                        <li>神社までのタクシー移動は、ご一緒に御同行をさせていただきます（自家用車をご利用の方も同様）。<strong>スタッフ2人</strong></li>
                        <li>タクシー移動料金は、お客様ご負担にてお願いをしております。</li>
                        <li>「配車アプリGO」にて配車いたします。片道1,500円程〜（配車料金＋利用料金・交通状況により変動します）。事前にGOアプリをダウンロードいただくと便利です。</li>
                    </ul>
                    <p class="mt-5"><strong>4名以上のご家族の場合は、2台の配車をお願いする事になります。</strong></p>
                    <p>タクシー乗車人数制限により、お子様2名＋大人3名（ご両親＋自身）は人数オーバーとなります。</p>
                    <p>自家用車がある方でお子様が2名いらっしゃる場合は、駐車料金とタクシー料金を比較すると、自家用車でお越しいただくほうがお安くなる場合もございます（日枝神社は駐車場が無料です）。</p>
                    <p><strong>タクシー料金を見越した上でのご判断をお願いいたします。</strong></p>
                </div>
            </details>

            <details class="group py-5">
                <summary class="flex cursor-pointer list-none items-center justify-between tracking-[0.08em] text-brand-800">
                    撮影当日について
                    <span class="text-brand-500 transition group-open:rotate-45" aria-hidden="true">＋</span>
                </summary>
                <div class="prose-mellow mt-4">
                    <ul class="list-disc space-y-2 pl-5">
                        <li>神社は来場者が大変多いため、人の写り込み等はご了承くださいませ。</li>
                        <li>お子様のコンディションによりお支度等に時間がかかった場合や、ご来店時間に遅れた場合、所要時間の延長は出来かねます。但し、次の撮影に影響しないと判断した場合は臨機応変にご対応をさせていただきます。</li>
                        <li>七五三はお支度に時間がかかるため、お着物と他の撮影を組み合わせることはできかねます。</li>
                    </ul>
                </div>
            </details>

            <details class="group py-5">
                <summary class="flex cursor-pointer list-none items-center justify-between tracking-[0.08em] text-brand-800">
                    雨天の場合について
                    <span class="text-brand-500 transition group-open:rotate-45" aria-hidden="true">＋</span>
                </summary>
                <div class="prose-mellow mt-4">
                    <dl class="space-y-2">
                        <div><dt class="inline">ママの着物着用がある場合（着付け師手配の為）：</dt><dd class="inline">3・4日前の天気予報での判断</dd></div>
                        <div><dt class="inline">着用がない場合：</dt><dd class="inline">前日PM12時の天気予報での判断</dd></div>
                    </dl>
                    <p class="mt-4">☀️晴れ＆☁️曇り……決行<br>雨☔️60%以上……リスケジュール（無料）<br>※決行の場合、こちらからの連絡はありません。</p>
                    <p class="mt-4"><strong>リスケジュールの場合、スタジオ側のご案内可能日とお客様のスケジュールを確認しながら日程を調整いたします。シーズン時期はご予約が立て込むため、ご予定次第では12月への振替／1月の撮影になる可能性もございます。その点もご理解頂き、ご予約をお願いいたします。</strong></p>
                    <p>撮影自体を全キャンセルの場合は、<a href="{{ route('cancel-policy') }}">キャンセル料</a>が発生いたします。</p>
                </div>
            </details>
        </div>

        <figure class="mt-12">
            <x-gallery :images="[['src' => 'plan-shichigosan/01', 'alt' => '神社ロケーションのタイムスケジュール']]" :columns="1" />
            <figcaption class="mt-3 text-center text-sm text-brand-700">神社ロケーションのタイムスケジュール</figcaption>
        </figure>
    </x-section>

    {{-- オプション --}}
    <x-section>
        <x-heading en="Option">オプション</x-heading>
        <div class="grid gap-10 md:grid-cols-2">
            <article class="rounded-sm bg-white">
                <x-picture src="plan-shichigosan/02" alt="生花でアレンジした七五三のヘアスタイル" sizes="(min-width: 768px) 50vw, 100vw" class="aspect-[4/3] w-full object-cover" />
                <div class="p-6 sm:p-8">
                    <h3 class="flex items-baseline justify-between gap-4 text-lg tracking-[0.08em] text-brand-800">
                        🌷 生花スタイリング <span class="text-base">＋3,300円<span class="text-xs">（税込）</span></span>
                    </h3>
                    <p class="mt-4 text-sm leading-loose">ヘッドアクセサリーの代わりに生花を使ってアレンジメントをいたします（2〜3、4種類の生花を利用します）。たくさんのお花を飾りたい方は、ご持参または＋5,500円で増量も可能です。ご予約時にお伝えください。<br>※背景に生花を入れて撮影をご希望の場合もこちらをご選択ください。</p>
                </div>
            </article>
            <article class="rounded-sm bg-white">
                <x-picture src="plan-shichigosan/03" alt="新日本髪アレンジのヘアスタイル" sizes="(min-width: 768px) 50vw, 100vw" class="aspect-[4/3] w-full object-cover" />
                <div class="p-6 sm:p-8">
                    <h3 class="flex items-baseline justify-between gap-4 text-lg tracking-[0.08em] text-brand-800">
                        ⭐︎ 新日本髪アレンジ <span class="text-base">＋3,300円<span class="text-xs">（税込）</span></span>
                    </h3>
                    <p class="mt-4 text-sm leading-loose">日本髪に似せた現代風のヘアアレンジ。通常のヘアセットよりもお時間をいただきます（肩下10センチ以上必要）。</p>
                </div>
            </article>
        </div>

        <div class="mx-auto mt-12 max-w-3xl rounded-sm border border-brand-200 bg-white p-6 text-sm leading-loose sm:p-8">
            <ul class="list-disc space-y-1 pl-5">
                <li>パパのお着物のご準備はございません。</li>
                <li>お着物のお持ち込み（袴のみのお貸し出し）も可能です。</li>
                <li>着付け小物等で無いものがある場合は、こちらでお貸出いたします。</li>
                <li>着物のみのレンタルは行っておりません。</li>
            </ul>
            <div class="mt-6 text-center">
                <x-button :href="route('options.mama-kimono')" variant="outline" arrow>お母様の訪問着着用をご希望の方</x-button>
            </div>
        </div>
    </x-section>

    {{-- ギャラリー --}}
    <x-section tone="white">
        <x-heading en="Gallery">七五三の撮影フォト</x-heading>
        <x-gallery :images="collect(range(4, 14))->map(fn ($n) => sprintf('plan-shichigosan/%02d', $n))->all()" label="七五三の撮影フォト" />
        <div class="mt-10 text-center">
            <x-button :href="route('gallery.kimono')" variant="outline" arrow>着物ギャラリーを見る</x-button>
        </div>
    </x-section>

</x-layouts.app>
