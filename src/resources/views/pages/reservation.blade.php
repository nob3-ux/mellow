<x-layouts.app :seo="$seo" :cta="false">

    <x-page-hero title="ご予約フォーム" en="Reservation" lead="ご予約希望内容をご記入くださいませ。スタジオの空き状況を確認のうえ、こちらからご連絡いたします。" />

    <div class="mx-auto max-w-3xl px-5 pb-20 sm:px-6">
        <div class="mb-10 space-y-4">
            <x-notice title="ご予約の最終受付について">
                <ul class="list-disc space-y-1 pl-5">
                    <li>生花Stylingありの場合、3営業日前まで。</li>
                    <li>その他のプランの場合、当日または前日まで。</li>
                    <li>生花無しの場合、背景の生花の利用もございません。</li>
                </ul>
            </x-notice>
            <x-notice title="七五三の受付について" tone="alert">
                <p>当分の間、①3歳・5歳のみの受付（7歳は御免なさい）②平日のみの営業（七五三に限らず営業自体が平日のみになります）③七五三のママヘアセット不可（訪問着レンタル及び着付けは可能です）となります。</p>
            </x-notice>
            <p class="text-center text-sm">
                <a href="{{ route('schedule') }}" class="text-brand-700 underline underline-offset-4">空き状況</a>・
                <a href="{{ route('flow') }}" class="text-brand-700 underline underline-offset-4">ご予約の流れ</a>・
                <a href="{{ route('items') }}" class="text-brand-700 underline underline-offset-4">商品アイテム</a>もあわせてご覧ください。
            </p>
        </div>

        <x-island name="ReservationForm" :props="[
            'fields' => config('forms.reservation.fields'),
            'steps' => config('forms.reservation.steps'),
            'submitUrl' => route('forms.submit', 'reservation'),
            'lineUrl' => config('site.contact.line_url'),
            'cancelPolicyUrl' => route('cancel-policy'),
            'flowUrl' => route('flow'),
        ]">
            <div class="rounded-sm bg-white p-10 text-center text-sm text-brand-700">
                <p>予約フォームを読み込んでいます…</p>
                <noscript>
                    <p class="mt-4">予約フォームのご利用にはJavaScriptを有効にしてください。<br>
                        <a href="{{ config('site.contact.line_url') }}" class="underline">LINE</a>からもご予約いただけます。</p>
                </noscript>
            </div>
        </x-island>

        <p class="mt-10 text-center text-sm leading-loose">
            フォームの入力が難しい場合は、<a href="{{ route('line') }}" class="text-brand-700 underline underline-offset-4">LINE</a>からもご予約いただけます。
        </p>
    </div>

</x-layouts.app>
