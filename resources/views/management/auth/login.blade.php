<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>管理画面ログイン | Genba Log</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100">
    <main class="flex min-h-screen items-center justify-center px-4 py-10">
        <section class="w-full max-w-md rounded-2xl bg-white p-6 shadow-sm">
            <div class="mb-8">
                <p class="text-sm font-semibold text-blue-600">
                    Genba Log
                </p>

                <h1 class="mt-2 text-2xl font-bold text-slate-900">
                    管理画面ログイン
                </h1>

                <p class="mt-2 text-sm text-slate-600">
                    管理者または閲覧者のアカウントでログインしてください。
                </p>
            </div>

            <form
                method="POST"
                action="{{ route('management.login.store') }}"
                class="space-y-5"
            >
                @csrf

                <div>
                    <label
                        for="email"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        メールアドレス
                    </label>

                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        required
                        autofocus
                        class="w-full rounded-lg border border-slate-300 px-3 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    >

                    @error('email')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label
                        for="password"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        パスワード
                    </label>

                    <input
                        id="password"
                        name="password"
                        type="password"
                        autocomplete="current-password"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    >

                    @error('password')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <label class="flex items-center gap-2 text-sm text-slate-700">
                    <input
                        name="remember"
                        type="checkbox"
                        value="1"
                        class="rounded border-slate-300"
                    >

                    ログイン状態を保持する
                </label>

                <button
                    type="submit"
                    class="w-full rounded-lg bg-blue-600 px-4 py-3 font-semibold text-white hover:bg-blue-700"
                >
                    ログイン
                </button>
            </form>
        </section>
    </main>
</body>
</html>
