{{--
    手順（ご予約の流れなど）。中に <x-step title="..."> を並べる。番号は自動で振られる。
--}}
<ol {{ $attributes->class('relative ml-6 space-y-10 border-l border-brand-300 pl-8 [counter-reset:step] sm:pl-12') }}>
    {{ $slot }}
</ol>
