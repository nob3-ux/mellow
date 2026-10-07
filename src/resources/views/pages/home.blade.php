<x-layouts.app :seo="$seo" hero-image="home/hero">

    {{-- ファーストビュー --}}
    <section class="relative isolate overflow-hidden">
        <x-picture src="home/hero" alt="生花と小物に囲まれて撮影した双子の赤ちゃん" sizes="100vw" eager
                   class="absolute inset-0 -z-10 size-full object-cover" />
        <div class="absolute inset-0 -z-10 bg-gradient-to-t from-brand-900/70 via-brand-900/20 to-transparent" aria-hidden="true"></div>

        <div class="mx-auto flex min-h-[min(78svh,680px)] max-w-6xl flex-col justify-end px-5 pt-28 pb-14 text-white sm:min-h-[min(88svh,820px)] sm:px-8 sm:pb-20">
            <p class="font-display text-lg tracking-[0.3em] italic opacity-90 sm:text-xl">Photo Studio Mellow</p>
            <h1 class="mt-3 text-3xl leading-snug font-medium tracking-[0.12em] [text-shadow:0_1px_16px_rgb(0_0_0/0.3)] sm:text-5xl">
                生花と撮れる<br>フォトスタジオ
            </h1>
            <p class="mt-5 text-sm leading-loose tracking-wider opacity-95 sm:text-base">
                渋谷区神宮前・外苑前駅から徒歩6分。<br>
                1枠1家族の完全プライベート空間で、ご家族の記念日を彩ります。
            </p>
            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <x-button :href="route('plans.index')" variant="light" size="lg" arrow>撮影プラン・料金を見る</x-button>
                <x-button :href="route('schedule')" size="lg" class="border border-white/60 !bg-transparent hover:!bg-white/15">空き状況を確認する</x-button>
            </div>
        </div>
    </section>

    {{-- 想い --}}
    <x-section width="narrow" class="text-center">
        <p class="font-display text-base tracking-[0.3em] text-brand-500 uppercase italic">Story</p>
        <h2 class="mt-4 text-xl leading-loose tracking-[0.14em] text-brand-800 sm:text-2xl">
            ” ありがとう、わたしたちの宝物 ”<br>
            <span class="text-base sm:text-lg">幸せのストーリーを紡ぐ</span>
        </h2>
        <div class="prose-mellow mt-10 space-y-8">
            <p>小さくてふわふわの手、ぷっくりとした頬<br>どんどん大きくなっていく身長<br>豊かになっていく表情</p>
            <p>あのときの家族のカタチ</p>
            <p>物語を作るように…<br>一瞬で通り過ぎる、<br>愛しく幸せな瞬間を紡ぐお手伝いをしよう。</p>
            <p>全てはこの想いから始まりました。</p>
        </div>
    </x-section>

    {{-- お知らせ --}}
    <x-section tone="white" width="narrow">
        <x-heading en="News">お知らせ</x-heading>
        <x-notice title="七五三の受付を再開しました">
            <p>年間お休みしていた七五三の受付を、これより再開することとなりました✨<br>
                そして去年お断りをしていた「スタジオ＋神社ロケPlan」も再開することになりました！</p>
            <p>ただ、御免なさい🙇‍♀️ 当分の間、以下の制限があります。</p>
            <ol class="list-decimal space-y-1 pl-6">
                <li>3歳・5歳のみの受付（7歳は御免なさい🙏）</li>
                <li>平日のみの営業（七五三に限らず営業自体が平日のみになります）</li>
                <li>七五三のママヘアセット不可（訪問着レンタル及び着付けは可能です）</li>
            </ol>
            <p>なんでもok👌では無くて本当に心苦しいのですが、人員や娘の保育園入園の関係で、当分の間はこちらの内容でのご案内とさせていただきます🥲<br>
                洋服撮影においては、そのまま継続していきますのでご安心ください🫶🌷</p>
            <p>ご理解のご家族様、ぜひお手伝いさせてください！</p>
        </x-notice>
    </x-section>

    {{-- コンセプト --}}
    <x-section>
        <x-heading en="Concept">Mellowの写真</x-heading>

        <div class="grid items-center gap-10 md:grid-cols-2 md:gap-16">
            <x-picture src="home/01" alt="生花でスタイリングしたお誕生日の記念写真" sizes="(min-width: 768px) 50vw, 100vw"
                       class="w-full rounded-sm shadow-xl shadow-brand-900/10" />
            <div>
                <p class="font-display tracking-[0.2em] text-brand-500 italic">Flower styling photo</p>
                <h3 class="mt-2 text-xl leading-relaxed tracking-[0.1em] text-brand-800">大切な記念日にイロドリを♡<br>生花でスタイリングしよう</h3>
                <div class="prose-mellow mt-6">
                    <p>”花をモチーフにした名前だから”<br>”とにかく花が大好き”<br>”彩り溢れる日々を送ってほしい”</p>
                    <p>生花スタイリングは、お子様の可愛いをより引き立たせる1つのエッセンスです。もちろん女の子だけではなく男の子も。</p>
                    <p>人生を彩りよく過ごしてほしい。そんな想いを込めた、生花スタイリングフォト。</p>
                </div>
                <x-button :href="route('plans.flower-styling')" variant="outline" class="mt-8" arrow>生花Stylingプランを見る</x-button>
            </div>
        </div>

        <div class="mt-20 grid items-center gap-10 md:grid-cols-2 md:gap-16">
            <x-picture src="home/02" alt="インテリアを背景に自然体で撮影したお子様の写真" sizes="(min-width: 768px) 50vw, 100vw"
                       class="w-full rounded-sm shadow-xl shadow-brand-900/10 md:order-2" />
            <div class="md:order-1">
                <p class="font-display tracking-[0.2em] text-brand-500 italic">Natural &amp; Formal</p>
                <h3 class="mt-2 text-xl leading-relaxed tracking-[0.1em] text-brand-800">世代を超えて喜ばれるように♡<br>自然体 &amp; フォーマル</h3>
                <div class="prose-mellow mt-6">
                    <p>それぞれの良さを活かす写真。</p>
                    <p>額装したくなる写真。おばあちゃまおじいちゃまが嬉しい写真。それが背景紙や白背景での撮影。</p>
                    <p>そしてインテリアを背景にした、等身大で、自然に、自由に動きながら。柔らかでおしゃれに残す写真。</p>
                    <p>いろんなシチュエーションがあっても楽しいよね♩ そんな想いです。</p>
                </div>
                <div class="mt-8 flex flex-wrap gap-3">
                    <x-button :href="route('studio')" variant="outline" arrow>スタジオイメージ</x-button>
                    <x-button :href="route('gallery')" variant="outline" arrow>ギャラリー</x-button>
                </div>
            </div>
        </div>
    </x-section>

    {{-- プラン --}}
    <x-section tone="cream" width="wide" id="plans">
        <x-heading en="Plan">撮影プラン</x-heading>
        <p class="mx-auto -mt-4 mb-12 max-w-2xl text-center text-sm leading-loose sm:text-base">
            ご家族の用途やお好みに合わせて、<br class="sm:hidden">「気軽に手軽に写真が残せる20カット」<br class="hidden sm:inline">
            「しっかり写真が残せる50カット」をご用意しています。<br>お好きなプランをお選びください。
        </p>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach (array_keys(config('site.plans')) as $plan)
                <x-plan-card :plan="$plan" />
            @endforeach
        </div>
        <div class="mt-12 flex flex-col items-center gap-4 text-sm">
            <x-button :href="route('plans.index')" arrow>プラン・料金をくらべる</x-button>
            <a href="{{ route('items') }}" class="text-brand-700 underline underline-offset-4">フォトアルバムなど商品のご購入をご希望の方はこちら</a>
        </div>
    </x-section>

    {{-- ご予約前の注意 --}}
    <x-section width="narrow">
        <x-heading en="Information">撮影前に必ずご確認ください</x-heading>
        <ul class="space-y-4 rounded-sm bg-white p-6 text-sm leading-loose sm:p-8">
            <li class="flex gap-3"><span class="text-brand-500" aria-hidden="true">●</span><span>1枠1家族限定の完全プライベート空間です。</span></li>
            <li class="flex gap-3"><span class="text-brand-500" aria-hidden="true">●</span><span>スタジオ運営上、日程変更をお願いする場合もございます。ご了承頂いた上でのご予約をお願いいたします。</span></li>
            <li class="flex gap-3"><span class="text-brand-500" aria-hidden="true">●</span><span>撮影当日、37.5°を超える発熱、風邪症状が見られた際は、恐れ入りますがご日程調整をお願いいたします。</span></li>
            <li class="flex gap-3"><span class="text-brand-500" aria-hidden="true">●</span><span>ご予約時間に遅れた場合、次のお客様のご予約により、お時間延長は出来かねます。交通状況・駐車などを見越したご来店をお願いいたします。</span></li>
        </ul>
        <p class="mt-6 text-center text-sm">
            日程変更及びキャンセルについては、<a href="{{ route('cancel-policy') }}" class="text-brand-700 underline underline-offset-4">キャンセルポリシー</a>をご確認ください。
        </p>
    </x-section>

    {{-- ご予約の流れ --}}
    <x-section tone="white" width="narrow">
        <x-heading en="Flow">ご予約の流れ</x-heading>
        <x-steps>
            <x-step title="ご予約">
                <p>ご予約プラン・撮影希望日を決めていただき、<a href="{{ route('line') }}">LINE</a>もしくは<a href="{{ route('reservation') }}">予約フォーム</a>よりご予約をお願いいたします。スタジオの空き状況を確認させていただき、ご来店日を決定します。ご質問などもお気軽にお問い合わせください。</p>
            </x-step>
            <x-step title="予約日決定〜ご入金">
                <p>ご予約決定後、撮影の詳細メールをお送りいたします。その後、1週間以内の事前入金をお願いいたします。ご入金の確認が取れ次第、予約確定となります。ご予約日1週間前頃に最終確認のメールをお送りいたします。</p>
            </x-step>
            <x-step title="撮影当日">
                <p>ご予約時間の5〜10分前の到着をお願いいたします。衣装セレクト・ヘアメイク・撮影までがトータルの所要時間となります。<strong>Mellowな時間を心ゆくまでお楽しみください。</strong></p>
            </x-step>
            <x-step title="データ送信">
                <p>撮影データのお渡しは最大で2週間以内。撮影の余韻を楽しんで頂きたいので、早めにお渡しができるようにさせていただきます。お渡し方法はLINE送信＋データ便（ギガファイル便）、画像サイズJPG／Lサイズ（約3010万画素・6720×4480）にて納品いたします。</p>
            </x-step>
        </x-steps>
        <div class="mt-12 text-center">
            <x-button :href="route('flow')" variant="outline" arrow>ご予約の流れを詳しく見る</x-button>
        </div>
    </x-section>

    {{-- Instagram --}}
    <x-section>
        <x-heading en="Instagram">最新の撮影フォト</x-heading>
        <ul class="grid grid-cols-3 gap-1 sm:gap-2">
            @foreach (json_decode(file_get_contents(resource_path('data/instagram.json')), true) as $post)
                <li>
                    <a href="{{ $post['url'] }}" target="_blank" rel="noopener" class="group block aspect-square overflow-hidden bg-brand-100">
                        <x-picture :src="$post['image']" :alt="$post['caption']" sizes="33vw"
                                   class="size-full object-cover transition duration-500 group-hover:scale-105 group-hover:opacity-90" />
                    </a>
                </li>
            @endforeach
        </ul>
        <div class="mt-10 text-center">
            <x-button :href="config('site.contact.instagram_url')" variant="outline" target="_blank" rel="noopener" arrow>
                Instagramでもっと見る（{{ '@'.config('site.contact.instagram_id') }}）
            </x-button>
        </div>
    </x-section>

    {{-- アクセス --}}
    <x-section tone="white">
        <div class="grid items-center gap-10 md:grid-cols-2">
            <div>
                <x-heading en="Access" align="left">アクセス</x-heading>
                <address class="text-sm leading-loose not-italic sm:text-base">
                    〒{{ config('site.address.postal_code') }}<br>
                    {{ config('site.address.region') }}{{ config('site.address.locality') }}{{ config('site.address.street') }}
                </address>
                <ul class="mt-4 space-y-1 text-sm">
                    @foreach (config('site.address.stations') as $station)
                        <li>{{ $station }}</li>
                    @endforeach
                </ul>
                <x-button :href="route('access')" variant="outline" class="mt-8" arrow>駅からの行き方・駐車場</x-button>
            </div>
            <iframe src="{{ config('site.address.map_embed_url') }}" title="{{ config('site.name') }}の地図"
                    class="aspect-[4/3] w-full rounded-sm border-0" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
        </div>
    </x-section>

</x-layouts.app>
