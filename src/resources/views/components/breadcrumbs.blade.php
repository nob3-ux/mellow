@props(['items'])

<nav aria-label="パンくずリスト" class="mx-auto max-w-7xl px-5 pt-4 sm:px-6 lg:px-8">
    <ol class="flex flex-wrap items-center gap-x-2 text-xs text-brand-600">
        @foreach ($items as $item)
            <li class="flex items-center gap-2">
                @if ($loop->last)
                    <span aria-current="page" class="text-ink">{{ $item['name'] }}</span>
                @else
                    <a href="{{ $item['url'] }}" class="transition hover:text-brand-800">{{ $item['name'] }}</a>
                    <span aria-hidden="true">/</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
