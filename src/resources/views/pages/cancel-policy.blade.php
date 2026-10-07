<x-layouts.app :seo="$seo">

    <x-page-hero title="キャンセルポリシー" en="Cancel Policy" />

    {{-- キャンセル・日時変更 --}}
    <x-section width="narrow" class="!pt-4">
        <x-heading en="Cancel">キャンセル・日時変更</x-heading>

        <div class="grid gap-6 md:grid-cols-2">
            <div class="rounded-sm bg-white p-6 text-sm leading-loose sm:p-8">
                <h3 class="border-b border-brand-200 pb-3 text-center text-lg tracking-[0.1em] text-brand-800">ご予約自体を全てキャンセル</h3>
                <table class="mt-4 w-full">
                    <caption class="sr-only">キャンセル料</caption>
                    <thead>
                        <tr class="text-left text-xs text-brand-700">
                            <th scope="col" class="py-2 font-normal">キャンセル日</th>
                            <th scope="col" class="py-2 font-normal">キャンセル料</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brand-200 border-t border-brand-200">
                        <tr>
                            <th scope="row" class="py-3 text-left font-normal">撮影日前日</th>
                            <td class="py-3 font-medium text-brand-800">ご入金額の50%</td>
                        </tr>
                        <tr>
                            <th scope="row" class="py-3 text-left font-normal">撮影日当日</th>
                            <td class="py-3 font-medium text-brand-800">ご入金額の100%</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="rounded-sm bg-white p-6 text-sm leading-loose sm:p-8">
                <h3 class="border-b border-brand-200 pb-3 text-center text-lg tracking-[0.1em] text-brand-800">撮影日の延期・変更</h3>
                <p class="mt-4 text-center font-medium text-alert">キャンセル料は頂いておりません。</p>
                <p class="text-center">但し、当日の朝8時までにご連絡をお願いいたします。</p>
                <dl class="mt-5 space-y-4">
                    <div>
                        <dt class="font-medium text-brand-800">生花スタイリングをご希望の方</dt>
                        <dd>前日PM12時以降の変更については、生花用意代金として<strong class="text-alert">1人につき1,500円</strong>を頂戴いたします。</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-brand-800">ママ着付けをご依頼の方</dt>
                        <dd>当日キャンセル及び延期の場合、<strong class="text-alert">100%のキャンセル料</strong>を頂戴いたします。</dd>
                    </div>
                </dl>
            </div>
        </div>

        <p class="mt-6 text-center text-sm">※ 尚、ご入金時・ご返金時の手数料はお客様負担にてお願いしております。</p>
    </x-section>

    {{-- 撮影・写真について --}}
    <x-section tone="white" width="narrow">
        <x-heading en="Photo">撮影・写真について</x-heading>
        <ul class="space-y-4 text-sm leading-loose">
            <li class="flex gap-3"><span class="text-brand-500" aria-hidden="true">●</span><span>全データ丁寧にレタッチをいたします。<br>シーンやお子様のイメージに合わせてデザインをするため、お時間を要する場合もございます。</span></li>
            <li class="flex gap-3"><span class="text-brand-500" aria-hidden="true">●</span><span>写真サイズ：JPEG　Lサイズ<br>（4928×3264）約1600万画素</span></li>
            <li class="flex gap-3"><span class="text-brand-500" aria-hidden="true">●</span><span>納品方法：LINE送信＆ファイル便（ギガファイル便）</span></li>
            <li class="flex gap-3"><span class="text-brand-500" aria-hidden="true">●</span><span>納品日数：撮影日～2週間以内</span></li>
            <li class="flex gap-3"><span class="text-brand-500" aria-hidden="true">●</span><span>すべてのプランで家族写真/姉妹兄弟撮影が可能です。</span></li>
            <li class="flex gap-3"><span class="text-brand-500" aria-hidden="true">●</span><span>お支払いは、すべて事前入金にてお願いしております。</span></li>
        </ul>
    </x-section>

    {{-- 出張料金について --}}
    <x-section width="narrow">
        <x-heading en="Travel Fee">出張料金について</x-heading>
        <table class="w-full rounded-sm bg-white text-sm leading-loose">
            <caption class="sr-only">出張料金</caption>
            <thead>
                <tr class="border-b border-brand-200 text-left text-xs text-brand-700">
                    <th scope="col" class="px-4 py-3 font-normal sm:px-6">渋谷駅からの所要時間</th>
                    <th scope="col" class="px-4 py-3 font-normal sm:px-6">追加料金</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-brand-200">
                <tr>
                    <th scope="row" class="px-4 py-3 text-left font-normal sm:px-6">渋谷駅～往復2時間内</th>
                    <td class="px-4 py-3 font-medium text-brand-800 sm:px-6">追加料金無しの地域</td>
                </tr>
                <tr>
                    <th scope="row" class="px-4 py-3 text-left font-normal sm:px-6">渋谷駅～往復2時間以上</th>
                    <td class="px-4 py-3 font-medium text-brand-800 sm:px-6">＋2,200円</td>
                </tr>
                <tr>
                    <th scope="row" class="px-4 py-3 text-left font-normal sm:px-6">渋谷駅～往復2.5時間以上3時間未満</th>
                    <td class="px-4 py-3 font-medium text-brand-800 sm:px-6">＋3,300円</td>
                </tr>
            </tbody>
        </table>
        <ul class="mt-6 space-y-2 text-sm leading-loose">
            <li>※3時間を超える出張撮影の場合は、ご相談くださいませ。</li>
            <li>※最寄り駅から出張先まで徒歩15分を超える場合、タクシーまたはお迎えをお願いする場合もございます。</li>
        </ul>
    </x-section>

</x-layouts.app>
