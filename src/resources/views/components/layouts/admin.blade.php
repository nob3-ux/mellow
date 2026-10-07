{{-- 管理画面のレイアウト（検索エンジンに載せない） --}}
@props(['title'])

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $title }}｜{{ config('site.name') }} 管理画面</title>
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>
<body class="min-h-screen bg-brand-50 font-sans">
    <header class="border-b border-brand-200 bg-white">
        <div class="mx-auto flex h-14 max-w-5xl items-center justify-between px-4">
            <p class="text-sm tracking-widest text-brand-800">{{ config('site.name') }} <span class="text-brand-500">管理画面</span></p>
            @auth
                <div class="flex items-center gap-4 text-sm">
                    <a href="{{ route('schedule') }}" target="_blank" class="text-brand-700 underline underline-offset-4">公開ページを見る</a>
                    <form method="post" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="text-brand-700">ログアウト</button>
                    </form>
                </div>
            @endauth
        </div>
    </header>

    <main class="mx-auto max-w-5xl px-4 py-8">
        {{ $slot }}
    </main>
</body>
</html>
