@php
    $alts = [
        1 => '生花のチューリップとHappy Birthdayの飾り文字',
        2 => 'アンティークの鏡台と生花をあしらった撮影セット',
        3 => 'ベージュのカーテンとチェアに飾った生花',
        4 => 'オレンジと黄色のポピーの生花',
        5 => '生花とケーキを飾ったテーブル',
        6 => 'ピンクの布と生花で彩った撮影背景',
        7 => '寄木の床に広げた布と生花',
        8 => 'ピンクの天蓋とベビーベッドのセット',
        9 => '壁掛けハンガーに吊るした子供服とバスケット',
        10 => '花模様のパネル背景',
        11 => 'くまのぬいぐるみとキルトのクッション',
        12 => '星のオーナメントを飾ったグリーンの壁',
        13 => '星のガーランドとうさぎのぬいぐるみを置いた小さな椅子',
        14 => 'ミッドセンチュリーのサイドテーブルとフロアランプ',
        15 => 'インポート小物や香水瓶を並べたドレッサー',
        16 => 'ふかふかのクッションマット',
        17 => 'ガラスの花瓶と小物を置いたテーブル',
        18 => 'くすみピンクの花のオブジェ',
        19 => 'レースのカーテンと洋書',
        20 => '窓辺のレースと陶器の鳥の置物',
        21 => '木のテーブルに置いた赤いりんご',
        22 => '窓辺のテーブルに並べたガラス瓶とドライフラワー',
        23 => '籐の椅子のあるスタジオの一角',
        24 => '白い壁と木の床、ドット柄のボール',
    ];
    $images = collect(range(1, 24))->map(fn ($n) => ['src' => sprintf('studio/%02d', $n), 'alt' => $alts[$n]])->all();
@endphp

<x-layouts.app :seo="$seo" hero-image="studio/06">

    <x-page-hero title="スタジオイメージ" en="Studio image" image="studio/06" />

    {{-- 大きく見せる3枚 --}}
    <x-section tone="white" width="wide">
        <x-heading en="Studio">スタジオのようす</x-heading>
        <div class="grid gap-3 md:grid-cols-3">
            @foreach (array_slice($images, 0, 3) as $image)
                <x-picture :src="$image['src']" :alt="$image['alt']" sizes="(min-width: 768px) 33vw, 100vw" class="aspect-[2/3] w-full object-cover" />
            @endforeach
        </div>
    </x-section>

    {{-- 一覧 --}}
    <x-section width="wide">
        <x-heading en="Interior">インテリア・小物</x-heading>
        <p class="-mt-6 mb-8 text-center text-sm text-brand-700">画像クリックで拡大</p>
        <x-gallery :images="array_slice($images, 3)" :columns="2" label="スタジオのインテリア・小物" />

        <p class="mt-12 text-center text-sm leading-loose">
            ※スタジオ衣装をご覧になりたい方は、<br class="sm:hidden">
            <a href="{{ config('site.contact.instagram_url') }}" target="_blank" rel="noopener" class="text-brand-700 underline underline-offset-4">Instagram</a>ストーリー⇨衣装♡をご覧ください。
        </p>
        <div class="mt-10 flex flex-col justify-center gap-3 sm:flex-row">
            <x-button :href="route('gallery')" variant="outline" arrow>撮影ギャラリーを見る</x-button>
            <x-button :href="route('about')" variant="outline" arrow>Mellowについて</x-button>
        </div>
    </x-section>

</x-layouts.app>
