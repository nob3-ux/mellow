<x-layouts.app :seo="$seo">

    <x-page-hero title="LINE予約ご希望のお客様" en="LINE" />

    <x-section width="narrow" class="!pt-4 text-center">
        <div class="rounded-sm bg-white px-6 py-10 sm:px-10">
            <x-picture src="access/01" alt="LINEのロゴ" sizes="96px" class="mx-auto size-24" />
            <h2 class="mt-6 text-xl tracking-[0.12em] text-brand-800">LINEアカウントはこちら</h2>
            <div class="mt-8">
                <x-button :href="config('site.contact.line_url')" variant="line" size="lg" class="w-full sm:w-auto sm:px-14" target="_blank" rel="noopener">友だち追加する</x-button>
            </div>
            <p class="mt-6 text-sm leading-loose">
                ※お友達検索の<br class="sm:hidden">
                <strong><a href="{{ config('site.contact.line_url') }}" target="_blank" rel="noopener" class="text-brand-700 underline underline-offset-4">{{ config('site.contact.line_id') }}</a></strong>を入力でも可能。
            </p>
        </div>

        <x-notice title="LINE登録時のお願い" class="mt-10 text-left">
            <p><strong>お手数をおかけしますが<br>LINE登録時のメッセージ項目を<br>全てご記載くださいませ。</strong></p>
        </x-notice>
    </x-section>

    <x-section tone="white" width="narrow" class="text-center">
        <p class="text-sm leading-loose">LINE以外では、予約フォームからもご予約いただけます。</p>
        <div class="mt-6 flex flex-col justify-center gap-3 sm:flex-row">
            <x-button :href="route('reservation')" arrow>予約フォーム</x-button>
            <x-button :href="route('flow')" variant="outline" arrow>ご予約の流れ</x-button>
        </div>
    </x-section>

</x-layouts.app>
