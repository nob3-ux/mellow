<x-layouts.admin title="空き状況の管理">
    <h1 class="text-xl tracking-widest text-brand-800">空き状況の管理</h1>
    <p class="mt-2 text-sm leading-relaxed text-brand-700">
        日付を押して、午前・午後の「空きあり」を切り替えます。変更はすぐに<a href="{{ route('schedule') }}" target="_blank" class="underline underline-offset-4">空き状況カレンダー</a>へ反映されます。
        お客様に公開されるのは本日から{{ config('site.schedule_months_ahead') }}ヶ月先の月末までです。
    </p>

    <x-island name="AdminCalendar" class="mt-8" :props="[
        'monthUrl' => route('admin.availabilities.month'),
        'updateUrl' => route('admin.availabilities.update', ['date' => '__DATE__']),
        'bulkUrl' => route('admin.availabilities.bulk'),
    ]">
        <p class="rounded-sm bg-white p-10 text-center text-sm text-brand-700">読み込んでいます…</p>
    </x-island>
</x-layouts.admin>
