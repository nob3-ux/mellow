<x-layouts.app :seo="$seo">

    <x-page-hero title="空き状況カレンダー" en="Schedule" lead="ご予約は、ご希望日の3ヶ月前より承ります。ご希望の日程をお選びくださいませ。" />

    <div class="mx-auto max-w-4xl px-5 pb-20 sm:px-6">
        <x-island name="ScheduleCalendar" :props="[
            'eventsUrl' => route('schedule.events'),
            'reservationUrl' => route('reservation'),
            'lineUrl' => config('site.contact.line_url'),
            'monthsAhead' => config('site.schedule_months_ahead'),
        ]">
            <div class="rounded-sm bg-white p-10 text-center text-sm text-brand-700">空き状況を読み込んでいます…</div>
        </x-island>

        <div class="mt-10 grid gap-6 md:grid-cols-2">
            <x-notice title="スケジュールについて">
                <p>スケジュールは午前・午後のご案内となります。詳しい撮影枠は日によって変動いたします。希望日を選択した上で、ご予約時にお時間をご案内させていただきます。</p>
                <p><strong>空欄は予約不可日です。</strong>空きがあっても先約が入りご案内不可の場合もございます。ご了承くださいませ。</p>
            </x-notice>
            <x-notice title="ご予約の最終受付">
                <ul class="list-disc space-y-1 pl-5">
                    <li>生花Stylingありの場合、3営業日前まで。</li>
                    <li>その他のプランの場合、当日または前日まで。</li>
                    <li>生花無しの場合、背景の生花の利用もございません。</li>
                </ul>
            </x-notice>
        </div>
    </div>

</x-layouts.app>
