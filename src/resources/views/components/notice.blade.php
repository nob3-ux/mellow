{{-- お知らせ・注意事項の囲み --}}
@props([
    'title' => 'お知らせ',
    'tone' => 'info',
])

<aside {{ $attributes->class([
    'rounded-sm border-l-4 p-5 text-sm leading-loose sm:p-6',
    'border-brand-500 bg-white' => $tone === 'info',
    'border-alert bg-[#fbf3f1]' => $tone === 'alert',
]) }}>
    <p @class(['mb-2 font-medium tracking-widest', 'text-brand-700' => $tone === 'info', 'text-alert' => $tone === 'alert'])>{{ $title }}</p>
    <div class="[&_p+p]:mt-3">{{ $slot }}</div>
</aside>
