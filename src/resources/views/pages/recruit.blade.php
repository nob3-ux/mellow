@php
    $email = config('site.contact.email');
@endphp

<x-layouts.app :seo="$seo" :cta="false">

    <x-page-hero title="求人募集" en="Recruit" lead="スタッフ募集のお知らせ" />

    {{-- ごあいさつ --}}
    <x-section width="narrow" class="text-center">
        <div class="prose-mellow space-y-8">
            <p>MELLOWでは、<br>今季よりスタッフ募集をいたします。<br>応募方法は詳細をご確認の上、<br class="sm:hidden">ご応募くださいませ。</p>
            <p>MELLOWの世界観に共感してくださる方、<br>一緒に盛り上げてくださる方の<br>ご応募お待ちしております。</p>
        </div>
        <div class="mt-10">
            <x-button href="#apply" arrow>応募方法を見る</x-button>
        </div>
    </x-section>

    {{-- 募集概要 --}}
    <x-section tone="white" width="narrow">
        <x-heading en="Job description">募集概要</x-heading>

        <dl class="divide-y divide-brand-200 border-y border-brand-200 text-[0.95rem] leading-loose sm:text-base">
            <div class="grid gap-1 py-5 sm:grid-cols-[9rem_1fr] sm:gap-6">
                <dt class="font-medium tracking-[0.08em] text-brand-800">業種</dt>
                <dd>
                    <ul>
                        <li>① アシスタント</li>
                        <li>② ヘアメイク</li>
                        <li>③ フォトグラファー</li>
                    </ul>
                </dd>
            </div>
            <div class="grid gap-1 py-5 sm:grid-cols-[9rem_1fr] sm:gap-6">
                <dt class="font-medium tracking-[0.08em] text-brand-800">雇用形態</dt>
                <dd>業務委託（アルバイト）</dd>
            </div>
            <div class="grid gap-1 py-5 sm:grid-cols-[9rem_1fr] sm:gap-6">
                <dt class="font-medium tracking-[0.08em] text-brand-800">業務内容</dt>
                <dd>
                    <ul>
                        <li>①撮影のアシスタント</li>
                        <li>①②お子様のヘアメイク</li>
                        <li>②七五三時のママヘアセット</li>
                        <li class="text-sm text-brand-700">※アシスタント業もしてくれる方、大歓迎！優遇！</li>
                        <li>③撮影全般・画像編集</li>
                    </ul>
                </dd>
            </div>
            <div class="grid gap-1 py-5 sm:grid-cols-[9rem_1fr] sm:gap-6">
                <dt class="font-medium tracking-[0.08em] text-brand-800">募集資格</dt>
                <dd>
                    <ul>
                        <li>・ブランドイメージに共感してくれる方</li>
                        <li>・子供フォトスタジオ経験者優遇</li>
                        <li>・約束と責任を守れる方</li>
                        <li>・おもてなしと楽しむことを忘れない方</li>
                    </ul>
                </dd>
            </div>
            <div class="grid gap-1 py-5 sm:grid-cols-[9rem_1fr] sm:gap-6">
                <dt class="font-medium tracking-[0.08em] text-brand-800">勤務地</dt>
                <dd>
                    渋谷区外苑前MELLOWスタジオ
                    <a href="{{ route('access') }}" class="ml-2 text-sm text-brand-700 underline underline-offset-4">アクセス</a>
                </dd>
            </div>
            <div class="grid gap-1 py-5 sm:grid-cols-[9rem_1fr] sm:gap-6">
                <dt class="font-medium tracking-[0.08em] text-brand-800">勤務時間・給料</dt>
                <dd>シフト制：勤務希望により変動（2時間〜OK）<br>お問い合わせください。</dd>
            </div>
            <div class="grid gap-1 py-5 sm:grid-cols-[9rem_1fr] sm:gap-6">
                <dt class="font-medium tracking-[0.08em] text-brand-800">募集方法</dt>
                <dd>履歴書／SNSアカウントがあればアカウント名をご記載ください。<br>お問い合わせなどもお受け付けしております。</dd>
            </div>
        </dl>
    </x-section>

    {{-- 応募方法 --}}
    <x-section id="apply" tone="cream" width="narrow" class="scroll-mt-20 text-center">
        <x-heading en="How to apply">応募方法</x-heading>
        <div class="rounded-sm bg-white px-6 py-10 sm:px-10">
            <p class="prose-mellow">上記を記載の上、<br>下記メールアドレスまでお送りください。</p>
            <p class="mt-6">
                <a href="mailto:{{ $email }}?subject={{ rawurlencode('求人応募') }}" class="text-xl font-semibold tracking-wider text-brand-800 underline decoration-brand-400 underline-offset-8 sm:text-2xl">{{ $email }}</a>
            </p>
            <p class="prose-mellow mt-6">ご不明な点も合わせてお問い合わせください。</p>
            <div class="mt-8">
                <x-button href="mailto:{{ $email }}?subject={{ rawurlencode('求人応募') }}" arrow>メールで応募する</x-button>
            </div>
        </div>
        <p class="prose-mellow mt-12">素敵なご縁があることを楽しみにしております♡</p>
    </x-section>

</x-layouts.app>
