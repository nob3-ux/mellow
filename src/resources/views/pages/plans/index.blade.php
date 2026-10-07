<x-layouts.app :seo="$seo">

    <x-page-hero title="撮影プラン・料金" en="Plan" lead="ご家族の用途やお好みに合わせて、「気軽に手軽に写真が残せる20カット」「しっかり写真が残せる50カット」をご用意しています。" />

    <x-section width="wide" class="!pt-8">
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach (array_keys(config('site.plans')) as $plan)
                <x-plan-card :plan="$plan" />
            @endforeach
        </div>
    </x-section>

    {{-- 料金早見表 --}}
    <x-section tone="white" width="narrow">
        <x-heading en="Price list">料金早見表</x-heading>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[32rem] text-left text-sm">
                <caption class="sr-only">撮影プランの料金（税込）</caption>
                <thead class="border-b border-brand-300 text-xs tracking-wider text-brand-600">
                    <tr>
                        <th scope="col" class="py-3 font-normal">プラン</th>
                        <th scope="col" class="py-3 text-right font-normal">平日</th>
                        <th scope="col" class="py-3 text-right font-normal">土日祝</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-brand-100">
                    @foreach ([
                        ['生花Styling スタンダード20カット', 22000, 25000, 'plans.flower-styling'],
                        ['生花Styling スタンダード50カット', 35000, 40000, 'plans.flower-styling'],
                        ['Anniversary スタンダード20カット', 20000, 23000, 'plans.anniversary'],
                        ['Anniversary スタンダード50カット', 33000, 38000, 'plans.anniversary'],
                        ['七五三 スタジオ着物50カット', 38000, 42000, 'plans.shichigosan'],
                        ['七五三 スタジオ着物洋服70カット', 43000, 48000, 'plans.shichigosan'],
                        ['七五三 日枝神社ロケ', 42000, 47000, 'plans.shichigosan'],
                        ['七五三 スタジオ＋日枝神社ロケ', 66000, 77000, 'plans.shichigosan'],
                        ['ハーフ成人式 スタジオ着物50カット', 35000, 40000, 'plans.half-seijinshiki'],
                        ['ハーフ成人式 スタジオ着物洋服70カット', 43000, 48000, 'plans.half-seijinshiki'],
                        ['Mellow wedding', 66000, 71000, 'plans.wedding'],
                    ] as [$name, $weekday, $holiday, $route])
                        <tr>
                            <th scope="row" class="py-3 font-normal"><a href="{{ route($route) }}" class="text-brand-800 hover:underline">{{ $name }}</a></th>
                            <td class="py-3 text-right tracking-wider">{{ number_format($weekday) }}円</td>
                            <td class="py-3 text-right tracking-wider">{{ number_format($holiday) }}円</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p class="mt-4 text-xs leading-relaxed text-brand-700">※すべて税込です。マタニティープランは生花Styling・Anniversaryの通常プランの料金が適用されます。オプション・追加料金は各プランページでご案内しています。</p>
    </x-section>

    {{-- オプション --}}
    <x-section width="default">
        <x-heading en="Option">オプション・商品</x-heading>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['options.smash-cake', 'option-smash-cake/01', 'スマッシュケーキ撮影', '1歳のお誕生日に。ケーキ持ち込み料1,500円'],
                ['options.dress-up-day', 'option-dress-up-day/01', 'dress up day', 'ママも一緒にドレスアップ'],
                ['options.mama-kimono', 'option-mama-kimono/01', 'お母様の訪問着', '七五三でのレンタル・着付け'],
                ['items', 'items/01', '商品アイテム', 'フォトアルバム・キャンバスフレーム'],
            ] as [$route, $image, $name, $lead])
                <a href="{{ route($route) }}" class="group block overflow-hidden rounded-sm bg-white shadow-sm shadow-brand-900/5 transition hover:shadow-lg">
                    <div class="aspect-[4/3] overflow-hidden">
                        <x-picture :src="$image" :alt="$name" sizes="(min-width: 1024px) 25vw, 50vw" class="size-full object-cover transition duration-700 group-hover:scale-105" />
                    </div>
                    <div class="p-5">
                        <h3 class="tracking-[0.08em] text-brand-800">{{ $name }}</h3>
                        <p class="mt-1 text-xs leading-relaxed">{{ $lead }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </x-section>

</x-layouts.app>
