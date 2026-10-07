@php
    $navigation = collect(config('site.navigation'))->map(fn (array $item) => [
        'label' => $item['label'],
        'href' => $item['route'] ? route($item['route']) : null,
        'children' => collect($item['children'] ?? [])->map(fn (array $child) => [
            'label' => $child['label'],
            'href' => route($child['route']),
        ])->all(),
    ])->all();
@endphp

<header class="sticky top-0 z-40 border-b border-brand-200/70 bg-brand-50/90 backdrop-blur supports-[backdrop-filter]:bg-brand-50/75">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:h-20 lg:px-8">
        <a href="{{ route('home') }}" class="shrink-0" aria-label="{{ config('site.name') }} ホーム">
            <x-picture src="brand/logo" alt="{{ config('site.name') }}" sizes="160px" eager
                       class="h-12 w-auto mix-blend-multiply lg:h-16" />
        </a>

        <nav aria-label="メインメニュー" class="hidden lg:block">
            <ul class="flex items-center gap-1 text-sm">
                @foreach ($navigation as $item)
                    <li class="group relative">
                        @if ($item['href'])
                            <a href="{{ $item['href'] }}"
                               class="block px-3 py-2 tracking-wider text-ink transition hover:text-brand-700">{{ $item['label'] }}</a>
                        @else
                            <span class="block cursor-default px-3 py-2 tracking-wider text-ink">{{ $item['label'] }}</span>
                        @endif

                        @if ($item['children'])
                            <ul class="invisible absolute top-full left-1/2 w-56 -translate-x-1/2 translate-y-1 rounded-sm border border-brand-200 bg-white py-2 opacity-0 shadow-lg shadow-brand-900/5 transition group-focus-within:visible group-focus-within:translate-y-0 group-focus-within:opacity-100 group-hover:visible group-hover:translate-y-0 group-hover:opacity-100">
                                @foreach ($item['children'] as $child)
                                    <li>
                                        <a href="{{ $child['href'] }}"
                                           class="block px-5 py-2 text-sm text-ink transition hover:bg-brand-100 hover:text-brand-700">{{ $child['label'] }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </nav>

        <div class="flex items-center gap-2">
            <span class="hidden sm:contents">
                <x-button :href="config('site.contact.line_url')" variant="line" size="sm" target="_blank" rel="noopener">LINEで予約</x-button>
            </span>
            <x-button :href="route('reservation')" size="sm">ご予約</x-button>

            <x-island name="MobileMenu" class="lg:hidden" :props="[
                'navigation' => $navigation,
                'reservationUrl' => route('reservation'),
                'lineUrl' => config('site.contact.line_url'),
                'instagramUrl' => config('site.contact.instagram_url'),
            ]">
                <button type="button" class="p-2 text-brand-800" aria-label="メニューを開く" disabled>
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16" /></svg>
                </button>
            </x-island>
        </div>
    </div>
</header>
