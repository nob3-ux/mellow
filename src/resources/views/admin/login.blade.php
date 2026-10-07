<x-layouts.admin title="ログイン">
    <div class="mx-auto mt-10 max-w-sm rounded-sm bg-white p-8 shadow-sm">
        <h1 class="text-lg tracking-widest text-brand-800">ログイン</h1>

        <form method="post" action="{{ route('admin.login') }}" class="mt-6 space-y-5">
            @csrf
            <div>
                <label for="email" class="text-sm text-brand-800">メールアドレス</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                       class="mt-1 block w-full rounded-sm border border-brand-300 px-3 py-2.5 focus:border-brand-700 focus:outline-none"
                       @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                @error('email')
                    <p id="email-error" class="mt-1.5 text-sm text-alert">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password" class="text-sm text-brand-800">パスワード</label>
                <input id="password" name="password" type="password" required autocomplete="current-password"
                       class="mt-1 block w-full rounded-sm border border-brand-300 px-3 py-2.5 focus:border-brand-700 focus:outline-none">
            </div>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="remember" value="1" class="accent-brand-700">
                ログイン状態を保持する
            </label>
            <button type="submit" class="w-full rounded-full bg-brand-700 py-3 text-sm tracking-widest text-white hover:bg-brand-800">ログイン</button>
        </form>
    </div>
</x-layouts.admin>
