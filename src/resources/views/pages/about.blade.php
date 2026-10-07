<x-layouts.app :seo="$seo" hero-image="about/01">

    <x-page-hero title="Mellowについて" en="About story" image="about/01" />

    {{-- コンセプト --}}
    <x-section width="narrow" class="text-center">
        <x-heading en="Concept">生花で彩る写真</x-heading>
        <div class="prose-mellow space-y-8">
            <p>Photo Studio Mellowは、<br>何年後、何十年後に見ても違和感なく、<br>素敵と思えるスタイルで<br class="sm:hidden">『生花で彩る写真 』をメインテーマにしたスタジオです。</p>
            <p>被写体に視線を寄せて、<br>その子の個性や可愛い・綺麗な瞬間を切り取るのが<br class="sm:hidden">Mellowの魅力です。</p>
        </div>
    </x-section>

    {{-- インテリア --}}
    <x-section tone="white">
        <div class="grid items-center gap-10 md:grid-cols-2 md:gap-16">
            <div class="grid grid-cols-2 gap-3">
                <x-picture src="studio/14" alt="ミッドセンチュリーの家具とランプを置いたスタジオの一角" sizes="(min-width: 768px) 25vw, 50vw" class="aspect-[2/3] w-full object-cover" />
                <x-picture src="studio/15" alt="海外インポートの小物や香水瓶を並べたドレッサー" sizes="(min-width: 768px) 25vw, 50vw" class="mt-10 aspect-[2/3] w-full object-cover" />
            </div>
            <div>
                <x-heading en="Interior" align="left">被写体をより可愛く写すために</x-heading>
                <div class="prose-mellow">
                    <p>スタジオのインテリアは、<br>シンプルな空間にミッドセンチュリーの家具や花瓶・海外インポート小物子供服・<br>新しい古き良きもの・手作りのもの。</p>
                    <p>大切な記念日を、より綺麗に映し出すものだけを集めました。</p>
                    <p>インテリアや背景は、あくまでも被写体をより可愛く写す道具の一つ。</p>
                    <p>主役の表情や仕草、個性を第一にシャッターを切っています。</p>
                </div>
            </div>
        </div>
    </x-section>

    {{-- 想い --}}
    <x-section tone="cream" width="narrow">
        <x-heading en="Our wish">撮り続けていきたい写真</x-heading>
        <ul class="mx-auto max-w-md space-y-4 text-center text-base leading-relaxed tracking-[0.08em] text-brand-800 sm:text-lg">
            <li>・家族が幸せな気分になる写真。</li>
            <li>・幸せだと認識する写真。</li>
            <li>・心が愛しさでぎゅっとなる写真。</li>
            <li>・思わず笑ってしまう写真。</li>
            <li>・家族の会話が増える写真。</li>
        </ul>
        <p class="prose-mellow mt-10 text-center">そんな写真を撮り続けていきたい。<br class="sm:hidden">と日々思いながら撮影をしています。</p>
    </x-section>

    {{-- スタジオ --}}
    <x-section width="narrow" class="text-center">
        <x-heading en="Studio">外苑前の小さなアトリエ</x-heading>
        <div class="prose-mellow space-y-8">
            <p>Mellowは、渋谷区外苑前のマンションの小さな1室。</p>
            <p>アトリエ兼スタジオのようなアットホームな場所です。</p>
            <p>たくさんの生花で、心地良く香るスタジオ。<br>おうちのような空間だからこそ緊張しない良さ。</p>
            <p>そんな楽しくも心地よい、<br>Mellowな空間でお会いできることを<br class="sm:hidden">楽しみにしております。</p>
        </div>
        <div class="mt-12 flex flex-col justify-center gap-3 sm:flex-row">
            <x-button :href="route('studio')" variant="outline" arrow>スタジオイメージを見る</x-button>
            <x-button :href="route('gallery')" variant="outline" arrow>ギャラリーを見る</x-button>
            <x-button :href="route('access')" variant="outline" arrow>アクセス</x-button>
        </div>
    </x-section>

</x-layouts.app>
