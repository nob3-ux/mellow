{{--
    写真ギャラリー（クリックで拡大表示）
    images:  画像キーの配列、または ['src' => キー, 'alt' => 説明, 'caption' => キャプション] の配列
    columns: PCでの列数（1 / 2 / 3 / 4。スマホでは最大2列）
    square:  正方形にトリミングして並べる
--}}
@props([
    'images',
    'columns' => 3,
    'square' => false,
    'label' => '写真ギャラリー',
])

@php
    $grid = [
        1 => 'grid-cols-1',
        2 => 'grid-cols-2',
        3 => 'grid-cols-2 sm:grid-cols-3',
        4 => 'grid-cols-2 sm:grid-cols-3 lg:grid-cols-4',
    ][$columns];
    $items = collect($images)->map(fn ($image) => is_string($image) ? ['src' => $image] : $image);
@endphp

<x-island name="Lightbox" {{ $attributes }}>
    <ul class="grid {{ $grid }} gap-2 sm:gap-3" aria-label="{{ $label }}">
        @foreach ($items as $item)
            <li>
                <figure>
                    <a href="{{ \App\Support\ImageManifest::url($item['src'], 1920) }}" data-lightbox
                       class="block overflow-hidden bg-brand-100 {{ $square ? 'aspect-square' : '' }}">
                        <x-picture :src="$item['src']" :alt="$item['alt'] ?? ''"
                                   sizes="(min-width: 1024px) {{ intdiv(100, $columns) }}vw, 50vw"
                                   class="size-full transition duration-500 hover:scale-105 {{ $square ? 'object-cover' : '' }}" />
                    </a>
                    @isset($item['caption'])
                        <figcaption class="mt-2 text-center text-xs tracking-wider text-brand-700">{{ $item['caption'] }}</figcaption>
                    @endisset
                </figure>
            </li>
        @endforeach
    </ul>
</x-island>
