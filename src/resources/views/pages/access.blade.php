<x-layouts.app :seo="$seo">

    <x-page-hero title="アクセス" en="Access" lead="東京メトロ銀座線 外苑前駅から徒歩6分。" />

    {{-- 住所・地図 --}}
    <x-section width="default" class="!pt-4">
        <div class="grid items-start gap-10 md:grid-cols-2">
            <div>
                <x-heading en="Address" align="left">所在地</x-heading>
                <address class="text-base leading-loose font-medium tracking-wider text-brand-800 not-italic">
                    〒{{ config('site.address.postal_code') }}<br>
                    {{ config('site.address.region') }}{{ config('site.address.locality') }}神宮前2-5-6<br>
                    アマデウスハウス205
                </address>

                <x-notice title="Googleマップでお越しの方はご注意ください" tone="alert" class="mt-6">
                    <p><strong>Google MAPで住所を入れると、マンション裏に誘導されてしまいます。</strong><br>
                        下記の地図をみて、お越しいただくのをお勧めいたします。</p>
                    <p><a href="#route">駅からの行き方（写真付き）を見る</a></p>
                </x-notice>
            </div>
            <iframe src="{{ config('site.address.map_embed_url') }}" title="{{ config('site.name') }}の地図"
                    class="aspect-[4/3] w-full rounded-sm border-0" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
        </div>
    </x-section>

    {{-- 電車・お車 --}}
    <x-section tone="white">
        <x-heading en="Train & Car">交通案内</x-heading>
        <div class="grid gap-8 md:grid-cols-2">
            <div class="rounded-sm bg-brand-50 p-6 text-sm leading-loose sm:p-8">
                <h3 class="text-lg tracking-[0.1em] text-brand-800">電車でお越しの方</h3>
                <ul class="mt-4 space-y-1">
                    <li>・地下鉄メトロ銀座線 外苑前駅 徒歩6分</li>
                    <li>・銀座線/半蔵門線/千代田線 表参道駅 徒歩13分</li>
                    <li>・大江戸線 国立競技場駅 徒歩15分</li>
                    <li>・中央線 千駄ヶ谷駅 徒歩15分</li>
                </ul>
            </div>
            <div class="rounded-sm bg-brand-50 p-6 text-sm leading-loose sm:p-8">
                <h3 class="text-lg tracking-[0.1em] text-brand-800">お車でお越しの方</h3>
                <p class="mt-4">立地がら、駐車利用金が多少お高めです。スタジオでの駐車料金の負担はございません。<br>
                    電車でのご来店をお勧めいたしますが、お車でのご来店の方は、こちらをご参考くださいませ。</p>
                <p class="mt-4">■<a href="https://www.s-park.jp/map/27889" target="_blank" rel="noopener" class="text-brand-700 underline underline-offset-4">外苑前２丁目パーキングパート３</a><br>
                    ↑近くて比較的お安いです。スタジオマンション裏にあるパーキング。台数に限りあり。</p>
                <p class="mt-4">※　マンション前に路上コインパーキングがございます。<br>
                    １時間ごとに動かすことが必要ですが、リーズナブルなのでお勧めです。（1時間300円）<br>
                    パパと一緒にご来店の方は、動かすのがお手数ですがご利用ください。</p>
            </div>
        </div>
    </x-section>

    {{-- 駅からの行き方 --}}
    <x-section width="narrow" id="route" class="scroll-mt-20">
        <x-heading en="Route">外苑前駅からの行き方</x-heading>
        <x-gallery :columns="1" label="外苑前駅からの行き方" :images="[
            ['src' => 'access/02', 'alt' => '外苑前駅3番出口からスタジオMellowまでの地図。スタジアム通りを進み、酒屋INOSEと岡田歯医者の間を左へ曲がり、青山熊野神社の先', 'caption' => '＜ 外苑前駅〜の地図 ＞'],
            ['src' => 'access/03', 'alt' => '外苑前駅3番出口からスタジオまでの道順写真。3番出口前の横断歩道を渡り右に直進、歯医者とINOSEの間を左へ曲がり、セブン越えて直進、隣のマンションのエントランスからエレベーターで2階の205号室へ', 'caption' => '＜ 外苑前からの行き方 ＞'],
        ]" />
    </x-section>

    {{-- 連絡先 --}}
    <x-section tone="white" width="narrow" class="text-center">
        <x-heading en="Mail & LINE">連絡先</x-heading>
        <p class="text-sm">【 Mail &amp; LINE 】</p>
        <p class="mt-2"><a href="mailto:{{ config('site.contact.email') }}" class="text-brand-700 underline underline-offset-4">{{ config('site.contact.email') }}</a></p>
        <div class="mt-8">
            <x-button :href="config('site.contact.line_url')" variant="line" size="lg" target="_blank" rel="noopener">LINEアカウントはこちら</x-button>
        </div>
    </x-section>

</x-layouts.app>
