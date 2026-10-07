@php
    $reserveQuery = ['plan' => 'other', 'message' => 'ご希望プラン：Mellow wedding'];
@endphp

<x-layouts.app :seo="$seo" hero-image="plan-wedding/01" :cta-query="$reserveQuery">

    <x-page-hero title="Mellow wedding" en="Wedding" image="plan-wedding/01" lead="お気に入りの額縁に飾る シンプルで飾りすぎないウェディング" />

    {{-- コンセプト --}}
    <x-section width="narrow" class="text-center">
        <x-heading en="Concept">花を愛する女性たちへ贈る</x-heading>
        <div class="prose-mellow space-y-6">
            <p>生活の中に花が溶け込むように<br>今日という特別な日にも花を添えて</p>
            <p>香るように美しく<br>誰かを大切に想う気持ち<br>この先もたくさんの幸せの訪れを願う</p>
            <p>お気に入りの額縁に飾る<br>シンプルで飾りすぎないウェディング</p>
        </div>
    </x-section>

    <x-section tone="white">
        <div class="grid items-center gap-10 md:grid-cols-2 md:gap-16">
            <x-picture src="plan-wedding/02" alt="生花のブーケを持つ花嫁のウェディングフォト" sizes="(min-width: 768px) 50vw, 100vw" class="mx-auto w-full max-w-md" />
            <div class="prose-mellow space-y-6 text-center md:text-left">
                <p class="text-lg tracking-[0.1em] text-brand-800">柔らかく、しなやかに、凛と…</p>
                <p>女性フォトグラファーだからこその<br>感性・質感を大切にしています。</p>
                <p>直接的ではなく間接的な写真。<br>パーツや仕草で<br>その時の情景が伝わるような</p>
                <p>Weddingフォトを<br>お届けしたいと思っています。</p>
            </div>
        </div>
    </x-section>

    <x-section width="narrow" class="text-center">
        <div class="prose-mellow space-y-6">
            <p>Mellow Weddingは、<br>昨今のウェディングフォトよりも<br>気軽にお試しいただけるような<br>価格設定にしております。</p>
            <p class="tracking-[0.08em] text-brand-800">・<strong>手が出し難いではなく、手が届きやすいを大切に</strong>・</p>
            <p>ウェディングフォトを迷っている方、<br>お花が好きな方、<br>少しだけ写真が苦手で恥ずかしい方、<br>リラックスしてお越しください。</p>
        </div>
        <p class="mt-10 text-xl tracking-[0.1em] text-brand-800"><strong>プラン価格 66,000円(税込)〜</strong></p>
        <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
            <x-button href="#plan">プラン内容を見る</x-button>
            <x-button :href="route('gallery.wedding')" variant="outline" arrow>写真をもっと見る</x-button>
        </div>
    </x-section>

    {{-- ギャラリー --}}
    <x-section tone="white">
        <x-heading en="Gallery">Weddingの撮影フォト</x-heading>
        <x-gallery :columns="3" square label="Weddingの撮影フォト"
                   :images="collect(range(3, 8))->map(fn ($n) => ['src' => sprintf('plan-wedding/%02d', $n), 'alt' => 'Mellow weddingで撮影したウェディングフォト'])->all()" />
        <div class="mt-10 text-center">
            <x-button :href="route('gallery.wedding')" variant="outline" arrow>写真をもっと見る</x-button>
        </div>
    </x-section>

    {{-- プラン --}}
    <x-section id="plan" class="scroll-mt-20">
        <x-heading en="Plan">プラン内容</x-heading>
        <div class="mx-auto max-w-2xl">
            <x-price-card name="Mellow wedding photo Plan" catch="ドレスお貸出無料・ヘアスタイリング・生花ヘアピース料金込み"
                          :prices="[['プラン価格', 66000]]" :reserve="route('reservation', $reserveQuery)">
                <dl class="space-y-6">
                    <div>
                        <dt class="font-medium tracking-[0.08em] text-brand-800">所要時間　トータル2時間</dt>
                        <dd class="mt-1">・ドレスセレクト〜ヘアセット 1時間<br>・撮影　1時間</dd>
                    </div>
                    <div>
                        <dt class="font-medium tracking-[0.08em] text-brand-800">撮影場所</dt>
                        <dd class="mt-1">・Mellowスタジオで行います。</dd>
                    </div>
                    <div>
                        <dt class="font-medium tracking-[0.08em] text-brand-800">全50カットお渡し</dt>
                        <dd class="mt-1">・色味調整（レタッチ）後、1ヶ月以内にギガファイル便及びLINEにてお渡しいたします。<br>画像サイズJPG /Lサイズ<br>（約3010万（6720×4480）画素）</dd>
                    </div>
                    <div>
                        <dt class="font-medium tracking-[0.08em] text-brand-800">ヘアスタイリング・生花ヘアピース 料金込み</dt>
                        <dd class="mt-1">・ヘアセット及び髪につける生花が込みの価格設定となります。<br>・メイクはご自身で行ってきていただきます。<br>※フルメイクをご希望の場合は、＋11,000円にてご利用いただけます。</dd>
                    </div>
                    <div>
                        <dt class="font-medium tracking-[0.08em] text-brand-800">ドレスお貸出無料</dt>
                        <dd class="mt-1">・9号までのサイズはドレス無料にてお貸出いたします。<br>9号以上の方は、お持ち込みをお願いいたします。<br>※ メンズ衣装及びブライダルインナー、靴はご持参ください。</dd>
                    </div>
                </dl>
                <x-slot:options>
                    <p>土日祝は＋5,000円頂戴いたします。</p>
                </x-slot:options>
            </x-price-card>
        </div>
    </x-section>

    {{-- オプション --}}
    <x-section tone="white">
        <x-heading en="Option">オプション</x-heading>
        <div class="grid gap-10 md:grid-cols-2">
            <article class="rounded-sm bg-brand-50">
                <x-picture src="plan-wedding/09" alt="季節のお花で彩ったFlower cake" sizes="(min-width: 768px) 50vw, 100vw" class="aspect-[4/3] w-full object-cover" />
                <div class="p-6 sm:p-8">
                    <h3 class="flex flex-wrap items-baseline justify-between gap-4 text-lg tracking-[0.08em] text-brand-800">
                        Flower cake photo <span class="text-base">＋ ¥8,800<span class="text-xs">（税込）</span></span>
                    </h3>
                    <p class="mt-4 text-sm leading-loose">季節のお花で彩った可愛いcakeをご用意いたします。<br>※ お花のご指定はできかねます</p>
                </div>
            </article>
            <article class="rounded-sm bg-brand-50">
                <x-picture src="plan-wedding/10" alt="フローリストによるオリジナルブーケ" sizes="(min-width: 768px) 50vw, 100vw" class="aspect-[4/3] w-full object-cover" />
                <div class="p-6 sm:p-8">
                    <h3 class="flex flex-wrap items-baseline justify-between gap-4 text-lg tracking-[0.08em] text-brand-800">
                        original bouquet <span class="text-base">＋ ¥15,000〜<span class="text-xs">（税込）</span></span>
                    </h3>
                    <p class="mt-4 text-sm leading-loose">フローリストによるブーケをオーダーいただけます。<br>サイズにより価格は変わります。<br>※ 色味やイメージをお聞きして作成を依頼します。<br>市場や季節により、お花のご指定はできかねる場合もございます。</p>
                </div>
            </article>
        </div>
        <div class="mt-12 text-center">
            <x-button :href="route('flow')" variant="outline" arrow>ご予約について</x-button>
        </div>
    </x-section>

    {{-- 結婚式当日 --}}
    <x-section>
        <x-heading en="Ceremony">結婚式当日のお手伝いもできます。</x-heading>
        <div class="grid items-center gap-10 md:grid-cols-2 md:gap-16">
            <x-picture src="plan-wedding/11" alt="結婚式当日に撮影したご親族の集合写真" sizes="(min-width: 768px) 50vw, 100vw" class="w-full" />
            <div>
                <h3 class="flex flex-wrap items-baseline gap-x-4 text-lg tracking-[0.08em] text-brand-800">
                    Wedding Ceremony Photo <strong class="text-base font-medium">¥66,000〜 (税込)</strong>
                </h3>
                <div class="prose-mellow mt-5 space-y-5">
                    <p>結婚式やウェディングパーティーetc…<br>当日撮影のお手伝いをさせていただきます。<br>自然光を得意とするフォトグラファーの<br>女性目線であったかくて柔らかな写真を<br>お届けいたします。</p>
                    <p>※価格は、LINEでの打ち合わせにて、<br>場所/所要時間/ご希望内容を踏まえて<br>決定をさせていただきます。</p>
                </div>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <x-button :href="config('site.contact.line_url')" variant="line" target="_blank" rel="noopener" arrow>LINEで相談する</x-button>
                    <x-button :href="route('reservation', ['plan' => 'other', 'message' => 'ご希望プラン：Wedding Ceremony Photo'])" variant="outline" arrow>予約フォームから相談する</x-button>
                </div>
            </div>
        </div>

        <x-gallery class="mt-16" :columns="3" square label="結婚式当日の撮影フォト"
                   :images="collect(range(12, 27))->map(fn ($n) => ['src' => sprintf('plan-wedding/%02d', $n), 'alt' => '結婚式当日に撮影したウェディングフォト'])->all()" />
    </x-section>

</x-layouts.app>
