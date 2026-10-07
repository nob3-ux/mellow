@php
    $alts = [
        1 => '窓辺でウェディングドレスの裾を整える花嫁',
        2 => 'ブーケを持って見つめ合う新郎新婦',
        3 => 'ブーケを手にドレスを広げて座る花嫁',
        4 => '手を上げてポーズをとる花嫁',
        5 => '窓辺に立つウェディングドレスの花嫁',
        6 => '床に手を伸ばす花嫁',
        7 => 'ブーケを持って寄り添い座る新郎新婦',
        8 => 'カーテンの前に並んで立つ新郎新婦',
        9 => 'ベールをかぶり新郎にもたれる花嫁',
        10 => 'カーテンの前で寄り添う新郎新婦',
        11 => '鏡の前に立つ花嫁',
        12 => '鏡越しの花嫁',
        13 => 'チュールのドレスで座る花嫁',
        14 => '生花の髪飾りとブーケの花嫁の後ろ姿',
        15 => 'ロングベールの花嫁の後ろ姿',
        16 => '鏡と生花のそばに座る花嫁',
        17 => 'チュールに包まれて微笑む花嫁',
        18 => '白い壁の前で椅子に座る花嫁',
        19 => '生花越しに寄り添う新郎新婦',
        20 => 'フロアランプのそばに座る花嫁',
    ];
    $images = collect($alts)->map(fn ($alt, $n) => ['src' => sprintf('gallery-wedding/%02d', $n), 'alt' => $alt])->values()->all();
@endphp

<x-layouts.app :seo="$seo" hero-image="gallery-wedding/07" :cta-query="['plan' => 'other', 'message' => 'ご希望プラン：Mellow wedding']">

    <x-page-hero title="ウェディングギャラリー" en="Wedding gallery" image="gallery-wedding/07" />

    <x-section tone="white" width="wide">
        <x-heading en="Gallery">ウェディングフォト</x-heading>
        <p class="-mt-6 mb-8 text-center text-sm text-brand-700">画像クリックで拡大</p>
        <x-gallery :images="$images" :columns="3" label="ウェディングフォト" />
    </x-section>

    <x-section width="narrow" class="text-center">
        <x-heading en="Plan">ウェディングのプラン</x-heading>
        <div class="flex flex-col justify-center gap-3 sm:flex-row">
            <x-button :href="route('plans.wedding')" arrow>Mellow weddingプランを見る</x-button>
        </div>
        <div class="mt-6">
            <a href="{{ route('gallery') }}" class="text-sm text-brand-700 underline underline-offset-4">ギャラリーにもどる</a>
        </div>
    </x-section>

</x-layouts.app>
