@php
    $address = config('site.address');
    $contact = config('site.contact');
@endphp

<footer class="border-t border-brand-200 bg-white text-sm">
    <div class="mx-auto grid max-w-6xl gap-12 px-5 py-14 sm:px-6 md:grid-cols-[1.2fr_2fr] lg:px-8">
        <div>
            <a href="{{ route('home') }}" class="inline-block">
                <x-picture src="brand/logo" alt="{{ config('site.name') }}" sizes="200px" class="h-16 w-auto mix-blend-multiply" />
            </a>
            <p class="mt-3 text-xs tracking-widest text-brand-600">{{ config('site.tagline') }}</p>

            <address class="mt-6 leading-relaxed not-italic">
                〒{{ $address['postal_code'] }}<br>
                {{ $address['region'] }}{{ $address['locality'] }}{{ $address['street'] }}<br>
                <span class="text-xs text-brand-600">{{ $address['stations'][0] }}</span>
            </address>
            <p class="mt-3">
                <a href="mailto:{{ $contact['email'] }}" class="text-brand-700 underline underline-offset-4">{{ $contact['email'] }}</a>
            </p>

            <ul class="mt-6 flex items-center gap-4 text-brand-700">
                <li>
                    <a href="{{ $contact['instagram_url'] }}" target="_blank" rel="noopener" aria-label="Instagram" class="block transition hover:text-brand-900">
                        <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/></svg>
                    </a>
                </li>
                <li>
                    <a href="{{ $contact['line_url'] }}" target="_blank" rel="noopener" aria-label="LINE公式アカウント" class="block transition hover:text-brand-900">
                        <svg class="size-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 3C6.48 3 2 6.58 2 11c0 3.95 3.56 7.26 8.36 7.88.33.07.77.22.88.5.1.25.07.65.03.9l-.14.85c-.04.25-.2.98.86.53 1.06-.45 5.73-3.38 7.82-5.78C21.23 14.3 22 12.75 22 11c0-4.42-4.48-8-10-8Z"/></svg>
                    </a>
                </li>
                <li>
                    <a href="{{ $contact['pinterest_url'] }}" target="_blank" rel="noopener" aria-label="Pinterest" class="block transition hover:text-brand-900">
                        <svg class="size-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 0 0-3.64 19.31c-.09-.79-.17-2 .04-2.86l1.17-4.97s-.3-.6-.3-1.48c0-1.39.81-2.43 1.81-2.43.85 0 1.27.64 1.27 1.41 0 .86-.55 2.14-.83 3.33-.24 1 .5 1.81 1.48 1.81 1.78 0 3.15-1.88 3.15-4.58 0-2.39-1.72-4.07-4.18-4.07-2.85 0-4.52 2.14-4.52 4.35 0 .86.33 1.78.74 2.28a.3.3 0 0 1 .07.29l-.28 1.13c-.04.18-.15.22-.34.13-1.25-.58-2.03-2.41-2.03-3.88 0-3.16 2.3-6.06 6.62-6.06 3.47 0 6.17 2.47 6.17 5.78 0 3.45-2.18 6.23-5.2 6.23-1.01 0-1.97-.53-2.3-1.15l-.62 2.38c-.23.87-.84 1.96-1.25 2.62A10 10 0 1 0 12 2Z"/></svg>
                    </a>
                </li>
            </ul>
        </div>

        <nav aria-label="フッターメニュー" class="grid grid-cols-2 gap-8 sm:grid-cols-3">
            <div>
                <p class="font-display tracking-[0.2em] text-brand-500 uppercase italic">Plan</p>
                <ul class="mt-4 space-y-2">
                    <li><a href="{{ route('plans.index') }}" class="hover:text-brand-700">プラン・料金一覧</a></li>
                    @foreach (config('site.plans') as $key => $plan)
                        <li><a href="{{ route("plans.{$key}") }}" class="hover:text-brand-700">{{ $plan['name'] }}</a></li>
                    @endforeach
                    <li><a href="{{ route('items') }}" class="hover:text-brand-700">商品アイテム</a></li>
                </ul>
            </div>
            <div>
                <p class="font-display tracking-[0.2em] text-brand-500 uppercase italic">Studio</p>
                <ul class="mt-4 space-y-2">
                    <li><a href="{{ route('about') }}" class="hover:text-brand-700">Mellowについて</a></li>
                    <li><a href="{{ route('studio') }}" class="hover:text-brand-700">スタジオイメージ</a></li>
                    <li><a href="{{ route('gallery') }}" class="hover:text-brand-700">ギャラリー</a></li>
                    <li><a href="{{ route('access') }}" class="hover:text-brand-700">アクセス</a></li>
                    <li><a href="{{ route('recruit') }}" class="hover:text-brand-700">求人募集</a></li>
                </ul>
            </div>
            <div>
                <p class="font-display tracking-[0.2em] text-brand-500 uppercase italic">Reservation</p>
                <ul class="mt-4 space-y-2">
                    <li><a href="{{ route('schedule') }}" class="hover:text-brand-700">空き状況</a></li>
                    <li><a href="{{ route('flow') }}" class="hover:text-brand-700">ご予約の流れ</a></li>
                    <li><a href="{{ route('reservation') }}" class="hover:text-brand-700">ご予約フォーム</a></li>
                    <li><a href="{{ route('line') }}" class="hover:text-brand-700">LINEでのご予約</a></li>
                    <li><a href="{{ route('inquiry') }}" class="hover:text-brand-700">お問い合わせ</a></li>
                    <li><a href="{{ route('cancel-policy') }}" class="hover:text-brand-700">キャンセルポリシー</a></li>
                </ul>
            </div>
        </nav>
    </div>

    <p class="border-t border-brand-100 py-6 text-center text-xs tracking-widest text-brand-600">
        &copy; {{ date('Y') }} {{ config('site.name') }}
    </p>
</footer>
