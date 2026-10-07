{{--
    Reactコンポーネントの埋め込み口（resources/js/islands/{name}.jsx）
    中身（slot）はJSが読み込まれるまでの表示・検索エンジン向けの内容。
--}}
@props([
    'name',
    'props' => [],
])

<div data-island="{{ $name }}" data-props="{{ json_encode($props, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}" {{ $attributes }}>
    {{ $slot }}
</div>
