{{-- スマートフォンで画面下に常に表示する予約導線 --}}
<nav aria-label="ご予約メニュー" class="fixed inset-x-0 bottom-0 z-40 grid grid-cols-3 border-t border-brand-200 bg-white/95 pb-[env(safe-area-inset-bottom)] text-[11px] tracking-wider backdrop-blur lg:hidden">
    <a href="{{ route('schedule') }}" class="flex flex-col items-center gap-1 py-2.5 text-brand-800">
        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="3.5" y="5" width="17" height="15" rx="2"/><path stroke-linecap="round" d="M3.5 10h17M8 3v4M16 3v4"/></svg>
        空き状況
    </a>
    <a href="{{ config('site.contact.line_url') }}" target="_blank" rel="noopener" class="flex flex-col items-center gap-1 bg-line py-2.5 text-white">
        <svg class="size-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 3C6.48 3 2 6.58 2 11c0 3.95 3.56 7.26 8.36 7.88.33.07.77.22.88.5.1.25.07.65.03.9l-.14.85c-.04.25-.2.98.86.53 1.06-.45 5.73-3.38 7.82-5.78C21.23 14.3 22 12.75 22 11c0-4.42-4.48-8-10-8Z"/></svg>
        LINEで予約
    </a>
    <a href="{{ route('reservation') }}" class="flex flex-col items-center gap-1 bg-brand-700 py-2.5 text-white">
        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M16.86 4.49a2.1 2.1 0 1 1 2.97 2.97L8.5 18.8l-4 1 1-4L16.86 4.49Z"/></svg>
        予約フォーム
    </a>
</nav>
