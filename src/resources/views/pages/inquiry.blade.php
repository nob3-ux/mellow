<x-layouts.app :seo="$seo" :cta="false">

    <x-page-hero title="お問い合わせ" en="Contact" lead="撮影についてのご質問や、メディア関係のお問い合わせはこちらからお送りください。" />

    <div class="mx-auto max-w-2xl px-5 pb-20 sm:px-6">
        <p class="mb-8 rounded-sm bg-brand-100 p-5 text-center text-sm leading-loose">
            撮影のご予約は<a href="{{ route('reservation') }}" class="text-brand-700 underline underline-offset-4">予約フォーム</a>または<a href="{{ route('line') }}" class="text-brand-700 underline underline-offset-4">LINE</a>からお申し込みください。
        </p>

        <x-island name="InquiryForm" :props="[
            'fields' => config('forms.inquiry.fields'),
            'submitUrl' => route('forms.submit', 'inquiry'),
        ]">
            <div class="rounded-sm bg-white p-10 text-center text-sm text-brand-700">
                <p>お問い合わせフォームを読み込んでいます…</p>
                <noscript>
                    <p class="mt-4">フォームのご利用にはJavaScriptを有効にしてください。<br>
                        メール（<a href="mailto:{{ config('site.contact.email') }}" class="underline">{{ config('site.contact.email') }}</a>）でも承ります。</p>
                </noscript>
            </div>
        </x-island>
    </div>

</x-layouts.app>
